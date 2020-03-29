<?php 

namespace App\Utils;

class Tools
{
	/**
	 * 根据流量值自动转换单位输出
	 * @param int $value
	 * @return string
	 */
	public static function flowAutoShow($value = 0)
	{
		$kb = 1024;
		$mb = 1048576;
		$gb = 1073741824;
		if (abs($value) > $gb) {
			return round($value / $gb, 2) . "GB";
		} else if (abs($value) > $mb) {
			return round($value / $mb, 2) . "MB";
		} else if (abs($value) > $kb) {
			return round($value / $kb, 2) . "KB";
		} else {
			return round($value, 2);
		}
	}
	
}


