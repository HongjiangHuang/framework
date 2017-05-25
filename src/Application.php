<?php
// +----------------------------------------------------------------------
// | JYPHP [ JUST YOU ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2017 http://www.jyphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Albert <albert_p@foxmail.com>
// +----------------------------------------------------------------------
namespace JYPHP\Core;

use Illuminate\Container\Container;
use Illuminate\Pipeline\PipelineServiceProvider;
use Illuminate\Support\Arr;
use JYPHP\Core\Http\Request;
use JYPHP\Core\Http\Response;
use JYPHP\Core\Interfaces\Application\IApplication;
use Illuminate\Database\Capsule\Manager as Db;

class Application extends Container implements IApplication
{
    protected $version = "0.1 beta";

    /**
     * 根目录
     * @var string
     */
    protected $basePath;

    /**
     * 已注册的服务
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * 已加载的服务
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * 存储目录
     * @var string
     */
    protected $storagePath;

    /**
     * 配置目录
     * @var string
     */
    protected $configPath;

    /**
     * 应用程序目录
     * @var string
     */
    protected $appPath;

    protected $logFile;

    /**
     * 资源目录
     * @var string
     */
    protected $resourcesPath;

    /**
     * Mark the given provider as registered.
     * @param  \Illuminate\Support\ServiceProvider $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    protected function registerBaseProvider()
    {
        //$this->register(RoutingServiceProvider::class);
        //$this->register(FilesystemServiceProvider::class);
        $this->register(PipelineServiceProvider::class);
    }

    /**
     * 注册核心类库别名
     */
    public function registerCoreContainerAliases()
    {
        $this->alias('app', IApplication::class);
    }

    /**
     * 初始化Eloquent ORM
     */
    protected function initDb()
    {
        $db = new Db();
        $databases = require $this->configPath() . "/databases.php";
        foreach ($databases as $name => $config) {
            $db->addConnection($config, $name);
        }
        $db->setAsGlobal();
        $db->bootEloquent();
    }

    public function __construct($basePath)
    {
        $this->registerBaseBindings();
        $this->registerBaseProvider();
        $this->registerCoreContainerAliases();
        $this->initDb();
    }

    /**
     * 返回程序版本号
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * 返回应用根目录
     * @return mixed
     */
    public function basePath()
    {
        return $this->basePath;
    }

    public function registerConfiguredProviders()
    {
        // TODO: Implement registerConfiguredProviders() method.
    }


    public function register($provider, $options = [], $force = false)
    {
        if (($registered = $this->getProvider($provider)) && !$force)
            return $registered;
        if (is_string($provider))
            $provider = $this->resolveProviderClass($provider);
        if (method_exists($provider, 'register'))
            $provider->register();
        foreach ($options as $key => $value) {
            $this[$key] = $value;
        }
        $this->markAsRegistered($provider);
        return $provider;
    }

    /**
     * 批量注册模块
     * @param array $modules
     */
    public function registerModules(array $modules)
    {
        foreach ($modules as $item)
            $this->register($item);
    }


    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::first($this->serviceProviders, function ($value) use ($name) {
            return $value instanceof $name;
        });
    }

    public function registerDeferredProvider($provider, $service = null)
    {
        // TODO: Implement registerDeferredProvider() method.
    }

    public function handle(Request $request) : Response
    {
        return new Response("哈哈哈");
    }

    public function configPath() : string
    {
        return $this->configPath?:$this->basePath()."/config";
    }

    public function resolveProviderClass(string $provider)
    {
        return new $provider($this);
    }

    /**
     * return storage path
     * 获得存储目录路径
     * @return string
     */
    public function storagePath() : string
    {
        return $this->storagePath?:$this->basePath()."/storage";
    }

    /**
     * return app path
     * @return string
     */
    public function appPath() : string
    {
        return $this->appPath?:$this->basePath()."/app";
    }

    /**
     * 获得资源路径
     * @return string
     */
    public function resourcesPath() : string
    {
        return $this->resourcesPath?:$this->basePath()."/resources";
    }

    /**
     * 日志文件
     * @return string
     */
    public function logFile() : string
    {
        return $this->logFile?:$this->basePath()."/runtime.log";
    }
}