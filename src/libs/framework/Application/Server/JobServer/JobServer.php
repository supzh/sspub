<?php

namespace Application\Server\JobServer;

use Application\Application;
use APISystem\ORM;
use Application\Server\Swoole\Server as SwooleServer;
use Application\Server\Util;
use Application\Server\Server;
use Application\Server\JobServer\Proxy\Transporter;
use Application\Server\JobServer\Proxy\ObjectProxyService;
use Application\Log;

class JobServer extends Server
{	
	public $isAsync;
	public $serverType;
	
	public function __construct($conf, $isAsync = true, $type = 'job')
	{
		$this->serverType = $type;
		$this->isAsync = $isAsync;
		parent::__construct($conf, SwooleServer::class, $type);
	}
	
	protected function start()
	{
		try {
			$class = $this->serverClass;
			$this->swooleServerObject = new $class($this->swHost, $this->swPort);			
			$this->swooleServerObject->on('Start', [$this, 'onMasterStart']);
			$this->swooleServerObject->on('WorkerStart', [$this, 'onWorkerStart']);
			$this->swooleServerObject->on('ManagerStart', [$this, 'onManagerStart']);
			$this->swooleServerObject->on('Receive', [$this, 'onReceive']);
			$this->swooleServerObject->on('Close', [$this, 'onClose']);
			$this->swooleServerObject->on('Task', [$this, 'onTask']);
			$this->swooleServerObject->on('Finish', [$this, 'onFinish']);
			$this->swooleServerObject->on('WorkerStop', [$this, 'onShutdown']);			
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
			$this->swooleServerObject->serverSetType = $this->serverType;
			$this->swooleServerObject->start();
		} catch (ServerException $e) {
			$handler = new ServerExceptionHandler($e);
			$handler->viewExit();
		}
	}	

	public function onTask($serv, $task_id, $from_id, $data)
	{
		if($transData = Transporter::decode($data)) {		
			$service = new ObjectProxyService($transData);
			$result = $service->getResult();
			if($result instanceof \Exception) {
				$resultLog =  ['type'=>'exception', 'result'=> [
						'message'=>$result->getMessage(),
						'file'=>$result->getFile(),
						'line'=>$result->getLine(),
						'code'=>$result->getCode(),]						
				];
				Log::$isJob = true;
				Log::$jobType = $serv->serverSetType;
				Log::error(['task_id'=>$task_id, 'res'=> $resultLog], 'job-res');
			} else {
				if(is_array($result)) {
					$resultLog = ['type'=>'array', 'result'=>$result];
				} elseif(is_object($result)) {
					$resultLog = ['type'=>'object', 'result'=> print_r($result, true)];
				} else {
					$resultLog = $result;
				}
				Log::info(['task_id'=>$task_id, 'res'=> $resultLog], 'job-res');
			}
			
			if(!$this->isAsync) {
				$serv->finish(Transporter::encode($result));
			}
		}
	}

	public function onReceive($serv, $fd, $from_id, $data)
	{
		if($this->isAsync) {
			$result = $serv->task($data);
			$serv->send($fd, Transporter::encode(['task_id'=>$result]));
		} else {
			$result = $serv->taskwait($data);
			$serv->send($fd, $result);
		}
		//Util::printLn('Receive' . $reqId);
	}

	public function onClose($serv, $fd, $from_id)
	{
		//Util::printLn('Close');
	}

	public function onStart($serv)
	{
	}

	public function onFinish($serv, $data)
	{
	}

	public function onShutdown($serv)
	{
	}
}