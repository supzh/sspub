<?php

namespace Application\Server\Swoole;

class ProcessResolver
{
    public $processes = array();

    public function __construct(array $processObjArr = null)
    {
        if (is_array($processObjArr)) {
            foreach ($processObjArr as $obj) {
                if ($obj instanceof Process) {
                    $this->processes[] = $obj;
                }
            }
        }
    }

    public function add(Process $obj, $key = null)
    {
        if (isset($key)) {
            $this->processes[$key] = $obj;
        } else {
            $this->processes[] = $obj;
        }

        return $this;
    }

    public function del($key)
    {
        if (isset($this->processes[$key])) {
            unset($this->processes[$key]);
        }
    }

    public function run()
    {
        if ($this->processes) {
            foreach ($this->processes as $obj) {
                $obj->start();
            }
        }
    }
}
