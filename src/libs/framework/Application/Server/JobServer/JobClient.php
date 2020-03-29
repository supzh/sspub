<?php

namespace Application\Server\JobServer;

use Application\Server\JobServer\Proxy\Transporter;
use Application\Server\Swoole\Client as SwooleClient;
use \RedisClient as Redis;
use APISystem\PublicFunction;
use Application\Log;

class JobClient
{
	private $driver;
	private $result;

	public function __construct($proxyObject, $type = 'job')
	{
		//Log::info(json_encode($proxyObject), 'job-client');
		$nodeInfo = $this->getOnlineJobServer($type);
	
		$this->driver = new SwooleClient(SWOOLE_SOCK_TCP);
		$this->driver->connect($nodeInfo['ip'], $nodeInfo['port']);

		$this->driver->send(Transporter::encode($proxyObject));
		$result = $this->driver->recv();
		$this->driver->close();

		$this->result = Transporter::decode($result);	
		$this->result['node'] = $nodeInfo['node'];
	}
	
	public function getOnlineJobServer($type = 'job')
	{
		if(!$list = Redis::smembers($type.'nodelist')) {
			throw new \Exception('没有在线'.$type.'Server');
		}
		$node = $list[array_rand($list)];
		if(!$nodelist = PublicFunction::loadConfig('nodelist', $type)) {
			throw new \Exception('JobServer配置不正确');
		}
		$nodeInfo = $nodelist[$node];
		$nodeInfo['node'] = $node;
		return $nodeInfo;
	}
	
	public function getResult()
	{
		return $this->result;
	}
}
