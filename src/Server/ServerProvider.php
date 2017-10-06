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
namespace JYPHP\Core\Server;

use JYPHP\Core\Server\Console\HttpServerCommand;
use JYPHP\Core\ServiceProvider;
use JYPHP\Core\Interfaces\Server\IHttpServer;

class ServerProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands(HttpServerCommand::class);
    }

    public function register()
    {
        $this->app->singleton(IHttpServer::class, HttpServer::class);
    }
}