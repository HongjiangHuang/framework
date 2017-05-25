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

/**
 * 获得全局app对象
 */
if (!function_exists("app")) {

    /**
     * 获取Application实例或make
     * @param null $abstract
     * @param array $parameters
     * @return mixed|\JYPHP\Core\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract))
            return \JYPHP\Core\Application::getInstance();
        return empty($parameters)
            ? \JYPHP\Core\Application::getInstance()->make($abstract)
            : \JYPHP\Core\Application::getInstance()->makeWith($abstract, $parameters);

    }

}

/**
 * 获得全局response对象
 */
if (!function_exists("response")) {
    function response()
    {
        return app()->make('response');
    }
}

/**
 * 获得全局request对象
 */
if (!function_exists('request')) {
    function request()
    {
        return app()->make('request');
    }
}