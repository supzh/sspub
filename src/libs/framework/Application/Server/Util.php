<?php

namespace Application\Server;

class Util
{
    public static function apiReturn($code, $data = null)
    {
        $r = ['code' => $code];

        if (isset($data)) {
            $r['data'] = $data;
        }

        return $r;
    }

    public static function jsonEncode($s)
    {
        return json_encode($s, JSON_UNESCAPED_UNICODE);
    }

    public static function covNumberFormat($s)
    {
        if($s) {
            foreach($s as $k=>$v) {
                if(is_array($v)) {
                    $s[$k] = self::covNumberFormat($v);
                } elseif(is_numeric($v)) {
                    $s[$k] = (float) $v;
                }
            }
        }
        return $s;
    }

    public static function printLn($str, $log = true)
    {
        //echo '['.date('Y-m-d H:i:s').'] '.$str.PHP_EOL;
        //if ($log) {
            //Logger::getLogger()->log($str);
        //}
    }

    public static function cleanArr(&$arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (null === $v) {
                    unset($arr[$k]);
                }
            }
        }
    }

    public static function setParam($param, $out = null, $m = null)
    {
        $sets = array();
        foreach ($param as $k => $v) {
            if (true === is_array($out)) {
                if (in_array($k, $out)) {
                    continue;
                }
            }
            if ($m == ':') {
                $sets[':'.$k] = $v;
            } else {
                $sets[] = sprintf('%s=:%s', $k, $k);
            }
        }

        return $sets;
    }
    public static function parseResponse($resp)
    {
        $rp = explode("\r\n\r\n", $resp);

        $header = [];
        foreach ($h = explode("\n", $rp[0]) as $v) {
            if (!$l = explode(':', $v, 2)) {
                continue;
            }
            $t = str_replace("\r", '', $l[0]);
            if (isset($l[1])) {
                $c = trim($l[1]);
                $header[$t] = $c;
            }
        }

        $p['header'] = $header;
        $p['body'] = $rp[1];

        return $p;
    }
}
