<?php

namespace Application\Server\Swoole;

if (false == defined('SWOOLE_VERSION')) {
    throw new SwooleException('Swoole没有安装。');
} else {
    if (1 == @ini_get('swoole.use_namespace')) {
        final class Server extends \Swoole\Server {}
    } else {
        final class Server extends \swoole_server {}
    }
}
