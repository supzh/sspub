<?php

namespace Application;

class Response
{
    public $request;
    public static $headers;    
    public static $instance;

    public function __construct(Request $request = null)
    {
    	if( ! $request) {
    		$request = new Request();
    	}
    	
        $this->request = $request;
    }

    public static function create($request = null)
    {              
        return static::getInstance($request);
    }
    
    public static function getInstance(Request $request = null)
    {
    	if(static::$instance === null) {
    		static::$instance = new static($request);
    	}
    	
		return static::$instance;
    }

    public static function setStatus($code, $msg = null)
    {
        static::$headers[] = function() use ($code, $msg) {
            header(sprintf('HTTP/1.1 %s %s', $code, $msg));
        };
    }
    
    public static function redirect($to)
    {   
    	self::setStatus(302);
    	self::setHeader('location', $to);
    	app()->finish();
    }
    
    public static function setHeader($name, $value)
    {
    	$headerValue = $name . ': ' . $value;
    	static::$headers[] = function() use ($headerValue) {
    		header($headerValue);
    	};
    }

    public function end($content)
    {    	
        $headers = static::$headers;        
        if($headers) {
            foreach($headers as $setHeaderCallback) {
                $setHeaderCallback();
            }
        }
        echo $content;
    }
    
    public static function finish($content)
    {
    	app()->finish($content);
    }
    
    public static function json($content, $httpcode = null)
    {
    	Response::setHeader('Access-Control-Allow-Origin', '*');
    	Response::setHeader('Content-Type', 'application/json; charset=utf-8');
    	if($httpcode) {
    		self::setStatus($httpcode, '');
    	}
    	app()->finish(json_encode($content, JSON_UNESCAPED_UNICODE));
    }
    
    public static function download($path_name, $save_name)
    {
    	ob_end_clean();
    	$hfile = fopen($path_name, "rb") or die("Can not find file: $path_name\n");
    	header("Content-type: application/octet-stream");
    	header("Content-Transfer-Encoding: binary");
    	header("Accept-Ranges: bytes");
    	header("Content-Length: ".filesize($path_name));
    	header("Content-Disposition: attachment; filename=\"$save_name\"");
    	while (!feof($hfile)) {
    		echo fread($hfile, 32768);
    	}
    	fclose($hfile);
    	exit;
    }
}
