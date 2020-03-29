<?php

namespace Application\Server\Swoole;

if (false == defined('SWOOLE_VERSION')) {
    throw new SwooleException('Swoole没有安装。');
} else {
    if (1 == @ini_get('swoole.use_namespace')) {
        final class Process extends \Swoole\Process {}
    } else {
        final class Process extends \swoole_process {}
    }
}
