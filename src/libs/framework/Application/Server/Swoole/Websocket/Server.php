<?php

namespace Application\Server\Swoole\Websocket;

use Application\Server\Swoole\SwooleException;

if (false == defined('SWOOLE_VERSION')) {
    throw new SwooleException('Swoole没有安装。');
} else {
    if (1 == @ini_get('swoole.use_namespace')) {
        final class Server extends \Swoole\Websocket\Server {}
    } else {
        final class Server extends \swoole_websocket_server {}
    }
}
