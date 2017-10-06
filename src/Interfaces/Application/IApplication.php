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
namespace JYPHP\Core\Interfaces\Application;

use Illuminate\Support\ServiceProvider;
use JYPHP\Core\Http\Request;
use JYPHP\Core\Http\Response;
use JYPHP\Core\Interfaces\Http\IResponse;

interface IApplication
{
    /**
     * 获取应用程序版本号
     * @return string
     */
    public function version(): string;

    /**
     * 获取父级目录
     * @return string
     */
    public function basePath(): string;

    /**
     * 配置目录
     * @return string
     */
    public function configPath(): string;

    /**
     * 存储目录
     * @return string
     */
    public function storagePath(): string;

    /**
     * 应用程序目录
     * @return string
     */
    public function appPath(): string;

    /**
     * 日志文件
     * @return string
     */
    public function logFile(): string;

    /**
     * 资源目录
     * @return string
     */
    public function resourcesPath(): string;

    public function handle(Request $request): IResponse;

    /**
     * Boot the application's service providers.
     * @return void
     */
    public function boot();


    /**
     * Register a service provider with the application.
     * @param  \Illuminate\Support\ServiceProvider|string $provider
     * @param  array $options
     * @param  bool $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, array $options = [], bool $force = false): ServiceProvider;

    public function registerModules(array $providers);
}