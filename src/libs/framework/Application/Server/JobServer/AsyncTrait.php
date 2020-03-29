<?php

namespace Application\Server\JobServer;

use Application\Server\JobServer\Proxy\ObjectProxy;
use APISystem\PublicFunction;

trait AsyncTrait
{		
// 	public $isAsync = false;
	
// 	public function setIsAsync($value){
// 		$this->isAsync = $value;
// 	}
	
// 	public function getIsAsync(){
// 		return $this->isAsync;
// 	}
	
	public static function async()
	{
		
		if(!PublicFunction::loadConfig('job','async', false)) {			
			return static::get();
		}
		
		return new class(static::class){
			
			public $ob;
			
			public function __construct($class)
			{
				$this->ob = new ObjectProxy($class);				
			}
			
			public function __call($func,$args)
			{
				$call = call_user_func_array([$this->ob, $func], $args);						
				$client = new JobClient($call);
				$res =  $client->getResult();	
				return $res;
			}
		};
		
	}	
	
}
