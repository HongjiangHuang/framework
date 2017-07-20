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

use JYPHP\Core\Exception\HttpException;
use JYPHP\Core\Filter\Abstracts\Filter;
use JYPHP\Core\Http\Request;
use JYPHP\Core\Validate;

final class Param extends Filter
{
    private $field;

    public function handle(Request $request, \Closure $next, ...$params)
    {
        $this->field = str_replace("$","",array_pop($params));
        $rule = [
            $this->field => array_pop($params)
        ];
        $validate = new Validate($rule);
        if (!$validate->check([$this->field => $request->get($this->field)])) {
            throw new HttpException($validate->getError()?:"参数" . $this->field . "未通过验证", 500);
        }
        return $next($request);
    }
}