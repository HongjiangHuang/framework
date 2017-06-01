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
namespace JYPHP\Core\Filter;

use JYPHP\Core\Filter\Abstracts\FilterProvider;

class FilterServiceProvider extends FilterProvider
{

    public function register()
    {
        $this->bind("csrf",Csrf::class);
        $this->bind("param",Param::class);
    }
}