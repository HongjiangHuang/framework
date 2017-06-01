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
namespace JYPHP\Core\Interfaces\Http;

interface IResponse
{
    /**
     * 发送消息
     * @return mixed
     */
    public function send();

    /**
     * 设置请求头
     * @param string $header_string
     * @param string $header_value
     * @return mixed
     */
    public function header(string $header_string, string $header_value);

    /**
     * 设置cookie
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed
     */
    public function cookie(string $key, string $value = "", int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false);

    /**
     * 设置HTTP 状态码
     * @param int $http_status_code
     * @return mixed
     */
    public function status(int $http_status_code);

    /**
     * 启用gzip压缩
     * @param int $level
     * @return mixed
     */
    public function gzip(int $level = 1);

    /**
     * 发送分段数据
     * @param string $data
     * @return bool
     */
    public function write(string $data): bool;

    /**
     * 发送文件
     * @param string $filename 文件名
     * @param int $offset 开始偏移
     * @param int $length 发送文件的大小
     * @return mixed
     */
    public function sendfile(string $filename, int $offset = 0, int $length = 0);
}