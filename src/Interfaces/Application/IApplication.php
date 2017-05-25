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
namespace JYPHP\Core\Interfaces\Application;

use JYPHP\Core\Http\Request;
use JYPHP\Core\Http\Response;

interface IApplication
{
    /**
     * 获取应用程序版本号
     * @return string
     */
    public function version();

    /**
     * 获取父级目录
     * @return string
     */
    public function basePath();

    /**
     * 配置目录
     * @return mixed
     */
    public function configPath();

    /**
     * 存储目录
     * @return mixed
     */
    public function storagePath();

    /**
     * 应用程序目录
     * @return mixed
     */
    public function appPath();

    /**
     * 日志文件
     * @return mixed
     */
    public function logFile();

    /**
     * 资源目录
     * @return mixed
     */
    public function resourcesPath();

    public function handle(Request $request) : Response;

    /**
     * Register all of the configured providers.
     * @return void
     */
    public function registerConfiguredProviders();

    /**
     * Register a service provider with the application.
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  array  $options
     * @param  bool   $force
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false);

    /**
     * Register a deferred provider and service.
     * @param  string  $provider
     * @param  string  $service
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null);
}