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
namespace JYPHP\Core\Console;

use Illuminate\Support\ServiceProvider;
use JYPHP\Core\Interfaces\Console\IApplication;

class ConsoleProvider extends ServiceProvider
{
    public function register()
    {
        $console_application = new Application();
        $this->app->instance(IApplication::class, $console_application);
        $this->app->instance('console', $console_application);
    }
}