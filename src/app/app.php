<?php

use Application\Application;
use Application\Route;
use APISystem\PublicFunction;

//初始化程序
Application::setAppPath(\APP_PATH);

$config = config('app');

$app = new Application($config);

//设置路由
require __DIR__.'/routes.php';

//返回给入口文件$app
return $app;

