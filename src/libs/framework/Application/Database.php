<?php

namespace Application;

use \ORM;

class Database
{    
	public static $isConfigured = false;    

    //配置数据库连接
    public static function configure()
    {
        $allConfig = config('database');		
        if( ! isset($allConfig['default'])) {
        	throw new \Exception("没有配置默认数据库。");
        }
        $connectionName = $allConfig['default'];  
        if(!isset($allConfig['connections'][$connectionName])) {
            throw new \Exception(sprintf("找不到%s的数据库配置信息。", $connectionName));
        }
        $config = $allConfig['connections'][$connectionName];        		
        ORM::configure('driver_options', array(\PDO::ATTR_PERSISTENT => $config['pconnect']));        
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['hostname'], $config['database'], $config['charset']);
        $ormConfig = ['connection_string' => $dsn, 'username' => $config['username'], 'password' => $config['password'],];
        ORM::configure($ormConfig);        
        foreach( $allConfig['connections'] as $k=> $v) {
        	ORM::configure('driver_options', array(\PDO::ATTR_PERSISTENT => $v['pconnect']), $k);        	
        	$dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $v['hostname'], $v['database'], $v['charset']);
        	$ormConfig = ['connection_string' => $dsn, 'username' => $v['username'], 'password' => $v['password'],];
        	ORM::configure($ormConfig, null, $k);
        }        
        static::$isConfigured = true;
    }
    
    public static function table($table, $primary = 'id', $connectionName = 'default')
    {
    	if(!static::$isConfigured) {
    		static::configure();
    	}
    	ORM::reset_db();
        $tableOrm =  ORM::for_table($table, $connectionName);        
        if($primary) {
        	$tableOrm->use_id_column($primary);
        }
        
        return $tableOrm;
    }
    
    public static function getDb($connectionName = 'default')
    {
    	if(!static::$isConfigured) {
    		static::configure();
    	}    
    	ORM::reset_db();
    	return ORM::get_db($connectionName);
    }    
}