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

use Illuminate\Config\Repository;
use JYPHP\Core\Interfaces\Application\IApplication;

class ConfigBootstrap
{
    public function bootstrap(IApplication $application)
    {
        $items = [];
        $config_path = $application->configPath();
        if (!is_dir($path = $config_path)) {
            throw new \Exception("目录" . $config_path . "不存在");
        }
        $dir = opendir($path);
        while (($file = readdir($dir)) !== false) {
            if (preg_match("/.php$/", $file)) {
                $file_path = $config_path . "/" . $file;
                $namespace = str_replace(".php", "", $file);
                $items[$namespace] = require $file_path;
            }
        }
        $config = new Repository($items);
        $application->instance('config', $config);
    }
}