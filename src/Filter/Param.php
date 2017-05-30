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

use JYPHP\Core\Filter\Abstracts\Filter;
use JYPHP\Core\Http\Request;

class Param extends Filter
{
    private $field;

    /**
     * @var array
     */
    private $validate = true;

    /**
     * @var Request
     */
    private $request;

    private function executeRule($i)
    {
        $rule = [
            'require' => function () use ($i) {
                return $this->request->get($i) === null ? false : true;
            },
        ];
    }

    private function parser($params)
    {
        $this->field = array_shift($params);
        foreach ($params as $item) {
            $this->executeRule($item);
        }
    }

    public function handle(Request $request, \Closure $next, ...$params)
    {
        $this->request = $request;
        $this->parser($params);
        if ($this->validate === false){
            throw new HttpException("参数".$this->field."未通过验证","500");
        }
        return $next($request);
    }
}