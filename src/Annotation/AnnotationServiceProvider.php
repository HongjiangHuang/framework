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
namespace JYPHP\Core\Annotation;

use Illuminate\Support\ServiceProvider;

class AnnotationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(Annotation::class,Annotation::class);
        $this->app->bind("annotation",Annotation::class);
    }
}