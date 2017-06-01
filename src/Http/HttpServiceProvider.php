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
namespace JYPHP\Core\Http;

use Illuminate\Support\ServiceProvider;
use JYPHP\Core\Interfaces\Http\IHttpKernel;
use JYPHP\Core\Interfaces\Http\IResponse;

class HttpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IHttpKernel::class,HttpKernel::class);
        $this->app->bind(IResponse::class,Response::class);
    }
}