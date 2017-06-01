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

use Illuminate\Support\Arr;
use JYPHP\Core\Exception\HttpException;
use JYPHP\Core\Filter\Abstracts\Filter;
use JYPHP\Core\Http\Request;

class Param extends Filter
{
    private $field;

    /**
     * @var array
     */
    private $validate = true;

    //private $data;

    /**
     * @var Request
     */
    private $request;

//    private function executeRule($i)
//    {
//        $rule = [
//            'string' => function () use ($i) {
//                return $this->request->get($i) === null ? false : true;
//            },
//            'int' => function () use ($i) {
//                return $this->request->get($i) === null ? false : true;
//            },
//        ];
//    }

    public function handle(Request $request, \Closure $next, ...$params)
    {
//        $this->request = $request;
//        $this->parser($params);
//        if ($this->validate === false) {
//
//        }
        //throw new HttpException("参数" . $this->field . "未通过验证", "500");
        return $next($request);
    }
}