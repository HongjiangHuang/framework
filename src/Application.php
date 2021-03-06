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
declare(strict_types = 1);
namespace JYPHP\Core;

use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use JYPHP\Core\Bootstrap\ConfigBootstrap;
use JYPHP\Core\Bootstrap\EnvBootstrap;
use JYPHP\Core\Bootstrap\FrameworkBootstrap;
use JYPHP\Core\Console\ConsoleProvider;
use JYPHP\Core\Http\Request;
use JYPHP\Core\Interfaces\Application\IApplication;
use JYPHP\Core\Interfaces\Http\IHttpKernel;
use JYPHP\Core\Interfaces\Http\IResponse;
use JYPHP\Core\Pipeline\PipelineServiceProvider;
use JYPHP\Core\Server\ServerProvider;

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
     * 引导
     * @var array
     */
    protected $bootstrap = [
        EnvBootstrap::class,
        ConfigBootstrap::class,
        FrameworkBootstrap::class
    ];

    protected $booted = false;

    /**
     * 已加载的服务
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * @var array
     */
    protected $bootingCallbacks = [];

    protected $bootedCallbacks = [];

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

    /**
     * 日志路径
     * @var string
     */
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
    protected function markAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    protected function registerBaseBindings(): void
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
    }

    protected function registerBaseProvider(): void
    {
        $this->register(PipelineServiceProvider::class);
        $this->register(CacheServiceProvider::class);
        $this->register(FilesystemServiceProvider::class);
        $this->register(DatabaseServiceProvider::class);
        $this->register(ConsoleProvider::class);
        $this->register(ServerProvider::class);
        $this->register(EventServiceProvider::class);
    }

    /**
     * 注册核心类库别名
     */
    public function registerCoreContainerAliases(): void
    {
        $this->alias('app', IApplication::class);
    }

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->registerBaseBindings();
        $this->registerBaseProvider();
        $this->registerCoreContainerAliases();
    }

    /**
     * 返回程序版本号
     * @return string
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * 返回应用根目录
     * @return mixed
     */
    public function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * 启动引导
     * @return $this
     */
    public function bootstrap($bootstrap = [])
    {
        $bootstrap = array_merge($this->bootstrap, $bootstrap);
        array_walk($bootstrap, function ($value) {
            $this->call([
                $this->make($value),
                "bootstrap"
            ]);
        });
        return $this;
    }

    public function register($provider, array $options = [], bool $force = false): ServiceProvider
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

        if ($this->booted) {
            $this->bootProvider($provider);
        }
        return $provider;
    }

    public function fireAppCallbacks(array $callbacks)
    {
        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }

    public function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            $provider->boot();
        }
    }

    public function boot()
    {
        if ($this->booted) {
            return;
        }

        // Once the application has booted we will also fire some "booted" callbacks
        // for any listeners that need to do work after this initial booting gets
        // finished. This is useful when ordering the boot-up processes we run.
        $this->fireAppCallbacks($this->bootingCallbacks);

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;

        $this->fireAppCallbacks($this->bootedCallbacks);
    }

    /**
     * 批量注册模块
     * @param array $modules
     */
    public function registerModules(array $modules): void
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


    /**
     * @param Request $request
     * @return IResponse
     */
    public function handle(Request $request): IResponse
    {
        return app()->make(IHttpKernel::class)->handle($request);
    }

    public function configPath(): string
    {
        return $this->configPath ?: $this->basePath() . "/config";
    }

    public function resolveProviderClass(string $provider): ServiceProvider
    {
        return new $provider($this);
    }

    /**
     * return storage path
     * 获得存储目录路径
     * @return string
     */
    public function storagePath(): string
    {
        return $this->storagePath ?: $this->basePath() . "/storage";
    }

    /**
     * return app path
     * @return string
     */
    public function appPath(): string
    {
        return $this->appPath ?: $this->basePath() . "/app";
    }

    /**
     * 获得资源路径
     * @return string
     */
    public function resourcesPath(): string
    {
        return $this->resourcesPath ?: $this->basePath() . "/resources";
    }

    /**
     * 日志文件
     * @return string
     */
    public function logFile(): string
    {
        return $this->logFile ?: $this->basePath() . "/runtime.log";
    }
}