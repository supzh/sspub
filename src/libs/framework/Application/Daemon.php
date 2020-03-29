<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 2017/1/12
 * Time: 上午11:18
 */

namespace Application;

use \Swoole\Process;
use APISystem\PublicFunction;
use Application\Application;

trait Daemon
{
    public $processName;
    public $isDameon = false;

    public function getProcessName()
    {
        return $this->processName;
    }

    public function setProcessName($name)
    {
    	$appConfig = PublicFunction::loadConfig('app');
    	$prefix = $appConfig['server_ip'].'('.$appConfig['env'].')'.'['.$appConfig['node'].']';
        $this->processName = $prefix.$name;
    }

    public function setIsDameon($set)
    {
        $this->isDameon = $set;
    }

    public function getIsDameon()
    {
        return $this->isDameon;
    }

    protected function start($call)
    {
        ob_start();
        if($this->status()) {
            echo ob_get_clean();
            return;
        }
        ob_get_clean();
        $process = new Process(function(Process $worker) use ($call){
            $worker->name($this->getProcessName());
            Log::info("启动 ".$this->getProcessName(), 'daemon-log');
            $call($worker);
        });

        $pid = $process->start();
        $this->setPid($pid);
        if($this->getIsDameon()) {
            Process::daemon();
        }
        Process::wait();
        echo ": 启动\033[32;40m [成功] \033[0m".PHP_EOL;
    }

    protected function status()
    {
    	$pid = $this->getPid();
    	if (!$pid) {
    		echo '状态: 无服务。'.PHP_EOL;
    		return false;
    	} elseif (!posix_kill($pid, 0)) {
    		echo "状态: ".$pid."服务没有运行。 [31;40m [失败] \033[0m".PHP_EOL;
    		return false;
    	}
    	echo "状态: 服务已经在运行 \033[32;40m [正常] \033[0m".PHP_EOL;
    	echo 'PID : '.$pid.PHP_EOL;
    	return true;
    }

    public function getStatus()
    {
        $pid = $this->getPid();
        if (!$pid) {
            return false;
        }
        return true;
    }

    protected function stop()
    {
        $pid = $this->getPid();
        if (!$pid) {
            echo '[警告]: 找不到 PID。'.PHP_EOL;
            echo "[警告]: 终止\033[31;40m [失败] \033[0m".PHP_EOL;
            return false;
        } elseif (!posix_kill($pid, 15)) {
            echo '[警告]: 发送信号至进程失败。'.PHP_EOL;
            echo "[警告]: 终止\033[31;40m [失败] \033[0m".PHP_EOL;
            return false;
        }
        if (false == unlink($this->getPidLocation())) {
            echo '[注意]: 删除'.$this->getPidLocation().'文件失败。'.PHP_EOL;
        }
        usleep(50000);
        echo ": 终止\033[32;40m [成功] \033[0m".PHP_EOL;
        Log::info("终止 ".$this->getProcessName(), 'daemon-log');
        return true;
    }

    protected function setPid($pid)
    {
        file_put_contents($this->getPidLocation(), $pid);
    }

    protected function getPid()
    {
        $pid = false;
        if (file_exists($this->getPidLocation())) {
            $pid = file_get_contents($this->getPidLocation());
        }

        return (int)$pid;
    }

    protected function getPidLocation()
    {
    	$processName = $this->getProcessName();
    	$processName = str_replace(" ", '', $processName);
    	$processName = str_replace(":", '_', $processName);
    	
        return '/tmp/swoole_daemon_' . $processName . '.pid';
    }
}