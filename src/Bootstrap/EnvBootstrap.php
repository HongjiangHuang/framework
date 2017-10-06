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

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use JYPHP\Core\Interfaces\Application\IApplication;

class EnvBootstrap
{
    function bootstrap(IApplication $application)
    {
        try {
            (new Dotenv($application->basePath()))->load();
        } catch (InvalidPathException $exception) {
            //
        }
    }
}
