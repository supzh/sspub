<?php

namespace Application;

use \RedisClient as Redis;

class Log
{
    public static $logFile = 'app-log';
    public static $dontLog = false;
    
    public static $reqId;
    public static $isJob = false;
    public static $jobType;

    public static function info($msg = null, $logFile = null)
    {
        static::writeFile('info', $msg, $logFile);
    }

    public static function error($msg = null, $logFile = null)
    {
        if($logFile) {
            $logFile.='-error';
        } else {
            $logFile = static::$logFile.'-error';
        }
        static::writeFile('error', $msg, $logFile);
    }

    public static function prepare($msg)
    {
        if(is_string($msg)) {
            return $msg;
        } elseif (is_array($msg)){
        	return json_encode($msg);
            //return static::getArrayMsg($msg);
        } elseif ( $msg instanceof \Exception){    	
        	return $msg = str_replace("\n", "", (string)$msg);
        } else {
            return $msg;
        }
    }

    public static function getArrayMsg($msg)
    {
        $ret = "\r\n";
        if(is_array($msg)) {
            foreach ($msg as $k => $v) {
                $ret.= '  ' . $k . ' : ' . static::getArrayMsg($v) . "\r\n";
            }
            return $ret;
        } else {
            return $msg;
        }
    }

    public static function write($msg, $logFile)
    {
    	if(static::$dontLog) {
    		return;
    	}
    	static $handle;

        if(!$logFile) {
            $logFile =  static::$logFile;
        }
        $logMode = config('app', 'log_mode', 'file');
        
        if($logMode == 'redis') {
        	Redis::rpush('applog', $msg);
        }else if($logMode == 'both') {
        	Redis::rpush('applog', $msg);
        	$handleFile = static::getHandleFile($logFile);
        	$handle[$handleFile] = @fopen($handleFile, 'a+');
        	@fwrite($handle[$handleFile], $msg);
        	@fclose($handle[$handleFile]);
        } else {
        	$handleFile = static::getHandleFile($logFile);
        	$handle[$handleFile] = @fopen($handleFile, 'a+');
        	@fwrite($handle[$handleFile], $msg);
        	@fclose($handle[$handleFile]);
        }
    }
    
    public static function getHandleFile($logFile)
    {
    	$node = config('app', 'node');
    	$node = str_replace(':','-',$node);
    	$path = 'log/'.$node.'/'.$logFile.'/'.date('Y').'/'.date('m').'/'.date('d');
    	//创建目录
    	if (!is_dir(appPath('/'.$path))) {
    		mkPath($path);
    	}
    	$f = $path.'/'.date('H').'.txt';
    	$f = str_replace('\\', '', $f);
    	return $handleFile = appPath('/'.$f);
    }
    
    public static function getReqId()
    {
    	if (!static::$reqId) {
    		static::$reqId =  md5(date('YmdHis').uniqid().microtime());
    	}
    	return static::$reqId;
    }
    
    public static function refreshReqId()
    {
    	return static::$reqId =  md5(date('YmdHis').uniqid().microtime());
    }

    public static function writeFile($type, $msg, $logFile = null)
    {
        $msg = static::prepare($msg);
        static $appConfig;
        if(!$appConfig) {
        	$appConfig = config('app');
        }

        $remoteAddr = (isset($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
        $requestUri = (isset($_SERVER['REQUEST_URI']))?$_SERVER['REQUEST_URI']:'';
        $logId = null;
        $textArr = [
          // 'log_id' => $logId,
        	'log_name' => $logFile,
            'req_id' => static::getReqId(),
            'log_time' =>date('Y-m-d H:i:s'),        	
            'log_type' =>$type,
            'remote' =>$remoteAddr,
            'req_uri' =>$requestUri,
        	'req_data' => $_REQUEST,
        	'app_name' => $appConfig['name'],
        	'app_node' => $appConfig['node'],
        	'app_env' => $appConfig['env'],
        	'server_ip' => $appConfig['server_ip'],
            'msg' => $msg
        ];
        
        $text = json_encode($textArr)."\r\n";
		
        static::write($text, $logFile);
		
        $dontSendMail = ['app-handle-error', 'app-mail-error'];
        
        if($type =='error' && in_array($logFile, $dontSendMail) == false) {
            try {
            	$subject = $appConfig['server_ip'].'('.$appConfig['env'].')'.'['.$appConfig['node'].']'.$logFile;
            	if(static::$isJob and static::$jobType == 'notify') {
            		(new \Mailer())->send($subject, $text);
             	} else {
             		\Mailer::notify($subject, $text);
             	}
              
            } catch(\Exception $e) {
                static::write(json_encode([$e->getMessage(), $text]), 'mail-error');
            }
        }
    }
}