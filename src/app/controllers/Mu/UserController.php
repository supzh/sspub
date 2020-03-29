<?php

namespace App\Controllers\Mu;

use Application\Database;
use App\Utils\Tools;

class UserController
{
	// User List
	public function index()
	{
		if($users = Database::table('user')		
			->select('user_sub_gift_instance_class_ss.user_sub_gift_instance_id', 'id')
			->select('user_sub_gift_instance_class_ss.passwd')
			->select('user_sub_gift_instance_class_ss.port')
			->select('user_sub_gift_instance_class_ss.t')
			->select('user_sub_gift_instance_class_ss.u')
			->select('user_sub_gift_instance_class_ss.d')
			->select('user_sub_gift_instance_class_ss.transfer_enable')
			->select('user_sub_gift_instance_class_ss.protocol')
			->select('user_sub_gift_instance_class_ss.obfs')
			->select('user_sub_gift_instance_class_ss.switch')
			->select('user_sub_gift_instance_class_ss.enable')
			->select('user_sub_gift_instance_class_ss.type')
			->select('user_sub_gift_instance_class_ss.method')			
			->join('user_sub_gift_instance', ['user_sub_gift_instance.user_id', '=', 'user.id'])
			->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])			
			->where('user_sub_gift_instance.state', $stateEnabled = 1)
			->where('user_sub_gift_instance_class_ss.enable', $stateEnabled = 1)
			->findArray()) {
				foreach($users as &$v) {
					$v['id'] = (int)$v['id'];
					$v['port'] = (int)$v['port'];
					$v['transfer_enable'] = (int)$v['transfer_enable'];
					$v['enable'] = (int)$v['enable'];
					$v['u'] = (int)$v['u'];
					$v['d'] = (int)$v['d'];
				}
			}
		
		$res = [
				"msg" => "ok",
				"data" => $users
		];
		return response()->json($res);
	}
	
	// Update Traffic
	public function addTraffic($id)
	{
		$u = request()->input('u');
		$d = request()->input('d');
		$nodeId = request()->input('node_id');
		$rate = 1;		
// 		$user = Database::table('user')->select('user_sub_gift_instance_class_ss.id')
// 			->join('user_sub_gift_instance', ['user_sub_gift_instance.user_id', '=', 'user.id'])
// 			->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
// 			//->where('user_sub_gift_instance.state', $stateEnabled = 1)
// 			->where('user.id', $id)
// 			->findOne();		
		$user = Database::table('user_sub_gift_instance_class_ss')->where('user_sub_gift_instance_id', $id)->findOne();
		$user->t = time();
		$user->u = $user->u + ($u * $rate);
		$user->d = $user->d + ($d * $rate);
		if (!$user->save()) {
			$res = [
				"msg" => "update failed",
			];
			return response()->json($res, 400);
		}
		// log
		
		$ins = Database::table('user_sub_gift_instance')->where('id', $id)->findOne();
		
		$totalTraffic = Tools::flowAutoShow(($u + $d) * $rate);
		$traffic = Database::table('user_traffic_log')->create();
		$traffic->user_id = $ins->user_id;
		$traffic->user_sub_gift_instance_id = $id;
		$traffic->u = $u;
		$traffic->d = $d;
		$traffic->node_id = $nodeId;
		$traffic->traffic = $totalTraffic;
		$traffic->log_time = time();
		$traffic->save();
		
		$res = [
				"ret" => 1,
				"msg" => "ok",
		];
		return response()->json($res);
	}
}
