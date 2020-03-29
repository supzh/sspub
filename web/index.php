<?php
define('APP_PATH', __DIR__.'/../');
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../src/app/app.php';

$response = $app->handle(
    $request = Application\Request::make()
);

$app->run($request, $response);

