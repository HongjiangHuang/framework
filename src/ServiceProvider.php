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
namespace JYPHP\Core;

use Illuminate\Support\Arr;
use \Illuminate\Support\ServiceProvider as LaravelServiceprovider;

class ServiceProvider extends LaravelServiceprovider
{
    public function commands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        foreach ($commands as $command) {
            $this->app->make('console')->add(
                $this->app->make($command)
            );
        }
    }
}