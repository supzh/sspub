<?php

return [
		'processName' => 'php: Job: Process',
		'logName' => 'job',
		'isDameon' => true,
		'async' => true,
		'server' => [
				'name' => "php: Job",
				'id'   => "job",
		],
		'swoole' => [
				'daemonize' => 0,  //此处需要为0，进程话由Daemon类实现
				'worker_num' => 20,  //工作进程数 prod 100
				'log_file' => '/tmp/job_swoole.log',
				'task_worker_num' => 20,  //异步任务工作进程数 prod 20
				'max_conn'=>51000,  //最大连接数，还需要修改ulimit才能生效
		],
];

