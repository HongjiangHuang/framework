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
namespace JYPHP\Core\Bootstrap;

use JYPHP\Core\Interfaces\Application\IApplication;
use \Illuminate\Support\Facades\Facade;

class FrameworkBootstrap
{
    public function bootstrap(IApplication $application)
    {
        Facade::setFacadeApplication($application);
    }
}