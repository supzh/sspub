<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 2017/1/12
 * Time: 上午11:18
 */

namespace Application;


class Console
{
    public function __construct()
    {
        ob_get_clean();
    }

    protected function getArg($no = 2)
    {
        return isset($_SERVER['argv'][$no]) ? strtolower($_SERVER['argv'][$no]) : '';
    }
}