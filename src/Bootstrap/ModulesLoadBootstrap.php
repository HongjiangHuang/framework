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
use Modules\OAuth\OAuthModule;

class ModulesLoadBootstrap
{
    /**
     * @var IApplication
     */
    private $app;

    private $modules = [];

    private function readModule(string $module_dir)
    {
        $module_class = "${module_dir}Module";
        $module_namepsace = config("app.modules_namespace", "Modules\\");
        $this->modules[] = "${module_namepsace}${module_dir}\\${module_class}";
    }

    public function bootstrap(IApplication $application)
    {
        $this->app = $application;

        $modules_path = $application->modulesPath();
        dir_each($modules_path, function ($file) {
            if ($file != "" && (strpos($file, ".") === false)) {
                $this->readModule($file);
            }
        });
        $application->registerModules(array_merge($this->modules, config("service", [])));
    }
}