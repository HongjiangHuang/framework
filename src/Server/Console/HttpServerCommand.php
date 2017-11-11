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
namespace JYPHP\Core\Server\Console;

use Illuminate\Session\SessionServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use JYPHP\Core\Console\Command;
use JYPHP\Core\Http\HttpServiceProvider;
use JYPHP\Core\Interfaces\Application\IApplication;
use JYPHP\Core\Interfaces\Server\IHttpServer;
use Symfony\Component\Console\Input\InputArgument;

class HttpServerCommand extends Command
{
    protected $name = "server:http";

    protected $desc = "操作HTTP服务";

    protected $files = [];

    public function configure()
    {
        parent::configure(); // TODO: Change the autogenerated stub
        $this->addArgument(
            "operation",
            InputArgument::REQUIRED,
            "操作类型有: start 开启http server , dev 开发者模式开启http server, restart 重启http服务 , kill 关闭http服务"
        );
    }

    public function handle()
    {
        $operation = $this->input->getArgument('operation');
        $operations = [
            'start',
            'restart',
            'kill',
            'dev'
        ];
        if (!(method_exists($this, $operation) && in_array($operation, $operations))) {
            throw new \Exception("'" . $operation . "'" . "  not operation");
        }
        $this->output->writeln($operation . "ing ...");

        $this->app->register(SessionServiceProvider::class);
        $this->app->register(HttpServiceProvider::class);
        //执行
        $this->app->call([
            $this,
            $operation
        ]);
    }

    public function start()
    {
        ($this->app->make(IHttpServer::class))
            ->daemonize()
            ->run();
    }

    public function dev()
    {
        $server = $this->app->make(IHttpServer::class);
        $server->init();
        $process = new \swoole_process(function ($worker) use (&$server) {
            foreach (config("redhot") as $dir) {
                $this->read($dir);
            }
            while (true) {
                clearstatcache();
                sleep(2);
                if ($this->inotify() === true) {
                    $server->reload();
                }
            }
        }, false, false);
        $process->start();
        $server->run();
    }

    public function inotify(): bool
    {
        $files = &$this->files;
        foreach ($files as $key => $item) {
            if (is_file($item['file'])) {
                $mtime = stat($item['file'])['mtime'];
                if ($mtime > $item['time']) {
                    $files[$key]['time'] = $mtime;
                    return true;
                }
            }
        }
        return false;
    }

    public function restart()
    {
        try {
            exec("kill " . Cache::pull('http_server_pid', 0));
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        sleep(1);
        $this->start();
    }

    /**
     * @param $dir
     */
    public function read($dir)
    {
        dir_each($dir, function ($file) use ($dir) {
            $files = [];
            if ($file != "" && $file != "." && $file != "..") {
                $file = $dir . "/" . $file;
                if (is_file($file)) {
                    $files[] = [
                        'file' => $file,
                        'time' => stat($file)['mtime']
                    ];
                } else if (is_dir($file)) {
                    $this->read($file);
                }
            }
            unset($dir);
            $this->files = array_merge($this->files, $files);
        });
    }

    public function kill()
    {
        try {
            exec("kill " . Cache::pull(IHttpServer::KEY, 0));
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        $this->output->writeln("kill success");
    }
}