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
declare(strict_types=1);
namespace JYPHP\Core\Annotation;

use JYPHP\Core\Interfaces\Application\IApplication;

class Annotation
{

    /**
     * @var string
     */
    private $metadata;
    private $text = "";
    private $filter = [];
    private $isParser = false;
    private $app;

    /**
     * Annotation constructor.
     * @param string $metadata
     * @param IApplication $application
     */
    public function __construct(string $metadata,IApplication $application)
    {
        $this->metadata = $metadata;
        $this->app = $application;
    }

    /**
     * @param $filter_metadata
     */
    private function parserFilter(string $filter_metadata): void
    {
        //@validate user_id require
        $filter_metadata = str_replace('@', '',rtrim($filter_metadata));
        $filter = explode(' ', $filter_metadata,2);
        $class = array_shift($filter);
        $this->filter[$class][] = str_replace(' ',',',end($filter));
    }

    /**
     * 得到过滤器
     * @param $annotation
     * @return bool|array
     */
    public function getFilter(?string $annotation = null)
    {
        if ($annotation === null)
            return $this->filter;
        return $this->filter[$annotation]??false;
    }

    /**
     * @return self
     */
    public function parser(): self
    {
        if ($this->isParser) {
            return $this;
        }
        if (empty($this->metadata)) {
            return $this;
        }
        $string = preg_replace('/\/\*\*|\*\//', "", $this->metadata);
        $filter_array = explode('*', $string);
        foreach ($filter_array as $key => $value) {
            $value = ltrim($value);
            if (strpos($value, "@") === false) {
                $this->text .= $value;
            } else {
                $this->parserFilter($value);
            }
        }
        return $this;
    }
}