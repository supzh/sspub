<?php 

use Application\Application;
use Application\Session;
use Application\Config;

if( ! function_exists('app')) {

	function app()
	{
		return Application::getInstance();
	}
}

if( ! function_exists('appPath')) {

	function appPath($path = '')
	{
		return Application::getAppPath($path);
	}
}

if( ! function_exists('session')) {
	
	function session()
	{		
		return new class(){
			public function get($key, $arrkey = null)
			{				
				$res =  Session::get($key);
				if($arrkey && is_array($res) && isset($res[$arrkey])) {
					return $res[$arrkey];
				}
				return $res;
			}
			
			public function set($key, $value)
			{
				return Session::set($key, $value);
			}
			
			public function id()
			{
				return Session::id();
			}
			
			public function destroy()
			{
				return Session::destroy();
			}
		};
	}
}

if( ! function_exists('config')) {

	function config($file, $key = '', $default = '', $reload = false)
	{		
		return Config::get($file, $key, $default, $reload);
	}
}

if( ! function_exists('response')) {

	function response()
	{
		return app()->getModule('response');
	}
}

if( ! function_exists('request')) {

	function request()
	{		
		return app()->getModule('request');
	}
}

if( ! function_exists('redirect')) {
	
	function redirect($to)
	{
		return app()->getModule('response')->redirect($to);
	}
}
if( ! function_exists('humanReadSize')) {
		
	function humanReadSize($bytes, $s = 0)
	{
		$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
		$base = 1024;
		$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
		
		return sprintf('%1.'.$s.'f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
	}

}

/**
 * 生成随机码
 * randomStr().
 *
 * @param int    $len      长度
 * @param string $prefix   前缀
 * @param bool   $isString 是否是数字 true/false
 *
 * @author zhangle
 * @date 2015-04-23
 */

if( ! function_exists('randomStr')) {

	function randomStr($len = 6, $prefix = '', $isString = false)
	{
		if ($isString == true) {
			$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1256789';
		} elseif ($isString == false) {
			$chars = '1256789';
		}
		for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; ++$i) {
			$str .= $chars[rand(0, $lc)];
		}
		
		return $prefix.$str;
	}	
}

if( ! function_exists('mkPath')) {

	/**
	 * @mkPath()创建多级目录
	 *
	 * @param  $mkPath  string  路径
	 * @param  $mode    string  权限
	 *
	 * @author	zhangle
	 * @date	2015-04-24
	 */
	function mkPath($mkPath, $mode = 0777)
	{
		$pathArray = explode('/', $mkPath);
		foreach ($pathArray as $value) {
			if (!empty($value)) {
				if (empty($path)) {
					$path = $value;
				} else {
					$path .= '/'.$value;
				}
				$path= '/'.$path;
				if (is_dir($path)) {
					continue;
				}
				@mkdir($path, $mode, true);
			}
		}
	}	
}


function readAllFiles($root = '.')
{
	$files  = array('files'=>array(), 'dirs'=>array());
	$directories  = array();
	$last_letter  = $root[strlen($root)-1];
	$root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR;
	
	$directories[]  = $root;
	
	while (sizeof($directories)) {
		$dir  = array_pop($directories);
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				$file  = $dir.$file;
				if (is_dir($file)) {
					$directory_path = $file.DIRECTORY_SEPARATOR;
					array_push($directories, $directory_path);
					$files['dirs'][]  = $directory_path;
				} elseif (is_file($file)) {
					$files['files'][]  = $file;
				}
			}
			closedir($handle);
		}
	}
	
	return $files;
}