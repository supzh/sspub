<?php

namespace Application\Server;

use Application\Application;
use APISystem\PublicFunction;

class Server
{
    public $swSeted = false;
    public $swHost;
    public $swPort;
    public $swSet;
    protected $swooleServerObject;
    protected $conf;
    protected $sets;
    protected $logPath;
    protected $appPath;
    protected $ons;
    protected $serverClass;

    public function __construct($conf, $class, $nodeType)
    {
        $this->conf = $conf;
        $this->serverClass = $class;
        
        $nodelist = PublicFunction::loadConfig('nodelist', $nodeType);
        $nodeInfo = $nodelist[PublicFunction::loadConfig('app','node')];
        if (!is_numeric($port = $nodeInfo['port'])) {
            throw new ServerException('没有配置服务端口。');
        }
        $this->swPort = $port;

        if (false === isset($conf['server']['host'])) {
            $this->swHost = '0.0.0.0';
        } else {
            $this->swHost = $conf['server']['host'];
        }
        $this->swSet = $conf['swoole'];
    }

    public function printConfig()
    {
        Util::printLn('服务器配置：');
        foreach ($this->conf['server'] as $n => $v) {
            Util::printLn('-- '.$n.'：'.$v);
        }
        Util::printLn('');
        Util::printLn('Swoole配置：');
        foreach ($this->swSet as $n => $v) {
            Util::printLn('-- '.$n.'：'.$v);
        }
        Util::printLn('');
    }

    protected static function listen($name)
    {
        Util::printLn(sprintf('已启动 %s；', $name));
    }

    protected function start()
    {
        try {
            $class = $this->serverClass;
            $this->swooleServerObject = new $class($this->swHost, $this->swPort);
            $this->swooleServerObject->on('start', [$this, 'onMasterStart']);
            $this->swooleServerObject->on('workerStart', [$this, 'onWorkerStart']);
            $this->swooleServerObject->on('managerStart', [$this, 'onManagerStart']);
            if ($this->ons) {
                foreach ($this->ons as $m => $c) {
                    $this->swooleServerObject->on($m, $c);
                    Util::printLn('执行前置方法 On：'.$m);
                }
            }
            if ($this->sets) {
                foreach ($this->sets as $i => $setCall) {
                    Util::printLn('执行前置方法 Set：'.$ii = ($i + 1));
                    $setCall($this);
                }
            }
            if(!$this->swSeted) {
                $this->swooleServerObject->set($this->swSet);
            }
            $this->printConfig();
            $this->swooleServerObject->start();
        } catch (ServerException $e) {
            $handler = new ServerExceptionHandler($e);
            $handler->viewExit();
        }
    }

    public function set($call)
    {
        $this->sets[] = $call;
        return $this;
    }

    public function on($method, $call)
    {
        $this->ons[$method] = $call;
        return $this;
    }

    public function run()
    {
        try {
            $cmd = isset($_SERVER['argv'][2]) ? strtolower($_SERVER['argv'][2]) : 'help';
            switch ($cmd) {
                case 'stop':
                    Util::printLn('终止服务器');
                    $this->shutdown();
                    break;
                case 'start':
                    Util::printLn('启动服务器');
                    $this->start();
                    break;
                case 'reload':
                    Util::printLn('重载服务器');
                    $this->reload();
                    break;
                case 'restart':
                    Util::printLn('重启服务器');
                    $this->shutdown();
                    sleep(2);
                    $this->start();
                    break;
                case 'status':
                    Util::printLn(' 查看状态');
                    $this->status();
                    break;
                default:
                    Util::printLn('您输入的命令 '.$cmd.' 没有匹配的服务，目前支持如下命令：');
                    Util::printLn('start | stop | reload | restart | status | help');
                    break;
            }
        } catch (ServerException $e) {
            $handler = new ServerExceptionHandler($e);
            $handler->viewExit();
        }

    }

    public function getServer()
    {
        return $this->swooleServerObject;
    }

    public function onWorkerStart($serv, $worker_id)
    {
        if ($worker_id >= $serv->setting['worker_num']) {
            swoole_set_process_name($this->getProcessNamePrefix().sprintf('%s: taskpool', $this->conf['server']['name']));
        } else {
            swoole_set_process_name($this->getProcessNamePrefix().sprintf('%s: eventworker', $this->conf['server']['name']));
        }
    }

    public function __call($method, $args)
    {
       return call_user_func_array([$this->swooleServerObject, $method], $args);
    }
    
    public function getProcessNamePrefix()
    {
    	$appConfig = Application::getApp()->getConfig();
    	return $appConfig['server_ip'].'('.$appConfig['env'].')'.'['.$appConfig['node'].']';
    }

    public function onManagerStart($server)
    {
        swoole_set_process_name($this->getProcessNamePrefix().sprintf('%s: manager', $this->conf['server']['name']));
        file_put_contents('/tmp/swoole_manager_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid', $server->manager_pid);
    }

    public function onMasterStart($server)
    {
        swoole_set_process_name($this->getProcessNamePrefix().sprintf('%s: master', $this->conf['server']['name']));
        file_put_contents('/tmp/swoole_master_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid', $server->master_pid);
        //echo '['.date('Y-m-d H:i:s').'] 注意 Server is running now'.PHP_EOL,  $this->logPath;
    }

    protected function shutdown()
    {
        $master_id = $this->getMasterPid();
        if (!$master_id) {
            echo '[警告]: 找不到 Master PID。'.PHP_EOL;
            echo "[警告]: 终止\033[31;40m [失败] \033[0m".PHP_EOL;

            return false;
        } elseif (!posix_kill($master_id, 15)) {
            echo '[警告]: 发送信号至Master失败。'.PHP_EOL;
            echo "[警告]: 终止\033[31;40m [失败] \033[0m".PHP_EOL;

            return false;
        }
        if (false == unlink('/tmp/swoole_manager_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid')) {
            echo '[注意]: 删除/tmp/swoole_manager_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid文件失败。'.PHP_EOL;
        }
        if (false == unlink('/tmp/swoole_master_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid')) {
            echo '[注意]: 删除/tmp/swoole_master_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid文件失败。'.PHP_EOL;
        }
        usleep(50000);
        echo $this->getProcessNamePrefix().$this->conf['server']['name'].": 终止\033[32;40m [成功] \033[0m".PHP_EOL;

        return true;
    }

    protected function reload()
    {
        $manager_id = $this->getManagerPid();
        if (!$manager_id) {
            echo '[警告]: 找不到Manager PID文件。'.PHP_EOL;
            echo "[警告]: 重载\033[31;40m [失败] \033[0m".PHP_EOL;

            return false;
        } elseif (!posix_kill($manager_id, 10)) {
            echo '[警告]: 发送信号至Manager失败。'.PHP_EOL;
            echo "[警告]: 终止\033[31;40m [失败] \033[0m".PHP_EOL;

            return false;
        }
        echo $this->getProcessNamePrefix().$this->conf['server']['name'].": 重载\033[32;40m [OK] \033[0m".PHP_EOL;

        return true;
    }

    protected function status()
    {
        $pid = $this->getMasterPid();
        echo '*****************************************************************'.PHP_EOL;
        if (!$pid) {
            echo '状态: 无服务。'.PHP_EOL;

            return false;
        } elseif (!posix_kill($pid, 0)) {
            echo "状态: 服务没有运行。 [31;40m [失败] \033[0m".PHP_EOL;

            return false;
        }
        echo "状态: 服务正在运行 \033[32;40m [正常] \033[0m".PHP_EOL;
        echo 'Master PID : '.$this->getMasterPid().PHP_EOL;
        echo 'Manager PID : '.$this->getManagerPid().PHP_EOL;
        echo '*****************************************************************'.PHP_EOL;
    }

    protected function getMasterPid()
    {
        $pid = false;
        if (file_exists('/tmp/swoole_master_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid')) {
            $pid = file_get_contents('/tmp/swoole_master_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid');
        }

        return $pid;
    }

    protected function getManagerPid()
    {
        $pid = false;
        if (file_exists('/tmp/swoole_manager_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid')) {
            $pid = file_get_contents('/tmp/swoole_manager_'.$this->getProcessNamePrefix().$this->conf['server']['id'].'.pid');
        }

        return $pid;
    }
}
