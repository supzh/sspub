<?php 

namespace App\Controllers;

use Application\Database;

class SearchController
{
	public function search()
	{		
		$userId = session()->get('userinfo', 'userId');
		$type = session()->get('userinfo', 'type');				
		$keywords = trim(request()->input('keywords'));
		$allNum = 0;
		$t = Database::table('user_sub_gift_instance');
		if($type != 2) {
			$t->where('user_sub_gift_instance.user_id', $userId);
		} else {
			$t->join('user', ['user_sub_gift_instance.user_id', '=', 'user.id']);
			$t->select('user.email')->select('user.user_name');
		}
		$t->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id']);
		$t->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id']);
		$t->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id']);
		$t->whereLike('user_gift.name', '%'.$keywords.'%');
		$user_gift_instance =$t->findArray();				
		$allNum+= count($user_gift_instance);				
		return response()->json([
				'code'=>0,
				'data'=>[
				'allNum' => $allNum,
				'user_gift_instance' => $user_gift_instance,
			 ]
		]);		
		
	}
}