<?php
// +----------------------------------------------------------------------
// | PHP [ JUST YOU ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2017 http://www.jyphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Albert <albert_p@foxmail.com>
// +----------------------------------------------------------------------
declare(strict_types = 1);
namespace JYPHP\Core\Http;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use JYPHP\Core\Annotation\Annotation;
use JYPHP\Core\Controller;
use JYPHP\Core\Exception\Handler;
use JYPHP\Core\Exception\HttpParamException;
use JYPHP\Core\Filter\Abstracts\FilterProvider;
use JYPHP\Core\Interfaces\Http\IHttpKernel;
use JYPHP\Core\Interfaces\Http\IResponse;
use JYPHP\Core\Traits\Controller\Msgpack;

class HttpKernel implements IHttpKernel
{
    /**
     * 存储控制器class_name
     * @var array
     */
    protected static $controllers = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $pathInfo;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $module;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * 获取控制器
     * key为空时获取所以已注册的控制器
     * 否则获取key相对应的控制器
     * @param null|string|null $key
     * @return array|bool|mixed|null
     */
    protected function getController(?string $key = null)
    {
        if ($key === null) {
            return self::$controllers;
        }
        if (Arr::exists(self::$controllers, $key)) {
            return self::$controllers[$key] === null ? false : app()->make(self::$controllers[$key]);
        }
        return null;
    }

    /**
     * 写
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function setController($key, $value)
    {
        app()->bind($key, $value);
        self::$controllers[$key] = $value;
        return true;
    }

    protected function registerController($path_info, $controller)
    {
        if ($this->getController($path_info) === null) {
            $this->setController($path_info, $controller);
        }
        return $controller;
    }

    /**
     * 实例化控制器
     * @param string $path_info
     * @return Controller|null
     */
    public function controller(string $path_info): ?Controller
    {
        $par_path_info = explode("/", ltrim($path_info, "/"));
        if (empty($par_path_info[0]) || empty($par_path_info[1])) {
            return null;
        }
        $this->module = $par_module = $par_path_info[0];
        $par_controller = $par_path_info[1];
        $controller = $this->getController($path_info);
        if ($controller === null) {
            //如果为空则注册一遍控制器
            $class = config("app.modules_namespace", "Modules\\") . ucfirst($par_module) . "\\Controllers\\" . ucfirst($par_controller);
            $this->registerController($path_info, $class);
            $controller = $this->getController($path_info);
        } else if ($controller === false) {
            return null;
        }
        $this->controller = $controller;

        //执行顺序
        //用户指定action?
        //请求类型mothod?
        //默认action?
        //都没有则404
        $this->action = $par_path_info[2] ?? (method_exists($controller, $this->request->getMethod())
                ? $this->request->getMethod()
                : config()->get('default_action', "index"));
        return $this->controller;
    }

    /**
     * 开始处理
     * @return mixed
     */
    public function callController()
    {
        $controller = $this->controller;
        $action = $this->action;
        if (!method_exists($controller, $action)) {
            $action = "missMethod";
        }
        $reflection = new \ReflectionMethod($controller, $action);
        $annotation = new Annotation($reflection->getDocComment(), app());
        $annotation->parser();
        $pipeline = app()->make('pipeline');
        $filters = [];
        foreach ($annotation->getFilter() as $filter => $params) {
            if (FilterProvider::has($filter)) {
                foreach ($params as $param)
                    $filters[] = $filter . ":" . $param;
            }
        }

        //开始处理
        //向管道发送request对象
        //将所有过滤器加入管道中
        //随后自动注入请求参数
        return $content = $pipeline->send($this->request)->through($filters)->then(function ($request) use ($controller, $action, $reflection) {
            $params = $reflection->getParameters();
            foreach ($params as $param) {
                if (is_null($param->getType())) {
                    $p = $this->request->get($param->name);
                    if (!isset($p) && !$param->isDefaultValueAvailable()) {
                        throw new HttpParamException($param->name);
                    }
                    $this->params[$param->name] = $this->request->get($param->name) ?: $param->getDefaultValue();
                }
            }
            return app()->call([$controller, $action], $this->params);
        });
    }

    /**
     * 处理
     * @param Request $request
     * @return IResponse
     */
    public function handle(Request $request): IResponse
    {
        $session_id = '';
        if (config()->get('app.debug', false)) {
            //调试模式
            $session_id = $request->get(config('session.cookie'));
        }

        if ($request->header('csrf-token')) {
            $session_id = $request->header('csrf-token');
        }

        if ($session_id) {
            Session::setId($session_id);
            Session::start();
        }

        $this->request = $request;

        $controller = $this->controller($this->pathInfo = $request->getPathInfo());

        if ($request->header('php-rpc')) {
            $controller = new class extends Controller
            {
                use Msgpack;
            };
        }
        try {
            if (is_null($controller)) {
                $response = app()->makeWith(IResponse::class, ["content" => "资源不存在(Controller Not Fount)", 'status' => 404]);
            } else {
                $content = $this->callController();
                if ($content instanceof IResponse) {
                    $response = $content;
                } else {
                    $response = app()->makeWith(IResponse::class, ['content' => $controller->toResponse($content)]);
                }
            }
        } catch (\Exception | \EngineException $exception) {
            $response = app()->make((config("app.exception_render", Handler::class) ?: Handler::class))->render($request, $exception);
            if (!($response instanceof IResponse)) {
                $response = app()->makeWith(IResponse::class, ["content" => $controller->toResponse($response)]);
            }
        } finally {
            if ($controller) {
                $response->setContentType($controller->getContentType());
            }
            if ($session_id) {
                Session::save();
                Session::flush();
            }
            return $response;
        }
    }
}