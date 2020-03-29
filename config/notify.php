<?php

return [
		'processName' => 'php: Notify: Process',
		'logName' => 'notify',
		'isDameon' => true,
		'async' => true,
		'server' => [
				'name' => "php: Notify",
				'id'   => "Notify",
		],
		'swoole' => [
				'daemonize' => 0,  //此处需要为0，进程话由Daemon类实现
				'worker_num' => 3,  //工作进程数 prod 100
				'log_file' => '/tmp/notify_swoole.log',
				'task_worker_num' => 3,  //异步任务工作进程数 prod 20
				'max_conn'=>51000,  //最大连接数，还需要修改ulimit才能生效
		],
];

