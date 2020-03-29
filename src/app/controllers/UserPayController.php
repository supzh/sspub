<?php 

namespace App\Controllers;

use Application\Database;
use Application\Request;

class UserPayController
{
	public function pay(Request $request)
	{		
		$type = $request->input('type');
		$subid = $request->input('subid');
		
		if(!$ins = Database::table('user_sub_gift_instance')->where('id', $subid)->findOne()) {
			return "无效的订阅，请您检查输入参数是否正确。";
		}
	
		$payrecord = Database::table('user_pay_record')->create();
		$payrecord->user_id = $ins->user_id;
		$payrecord->user_sub_gift_instance_id= $subid;
		$payrecord->user_gift_id= $ins->user_gift_id;
		$payrecord->created_at= date('Y-m-d H:i:s');
		$payrecord->pay_mount= $ins->cost_money;
		$payrecord->pay_type= (int)$type;
		if(!$payrecord->save()) {
			return "处理失败，请您稍后重试。";
		}		
		$payid = $payrecord->id();				
		$istest = '';		
		if(config('app', 'env') != 'prod') {
			$istest = '【测试支付，请回到订阅页点击支付已完成即可完成支付】<br />';
			
			$payrecord->state = 1;
			$payrecord->last_modify_at= date('Y-m-d H:i:s');
			$payrecord->save();
			$ins->state = 1;
			$ins->last_modify_at =  date('Y-m-d H:i:s');
			$ins->started_at=  date('Y-m-d H:i:s');			
			$ins->expired_at=  date('Y-m-d H:i:s', time() + $ins->mo*86400*31);
			$ins->save();
			
			if($inss = Database::table('user_sub_gift_instance_class_ss')->where('user_sub_gift_instance_id', $subid)->findOne()) {
				$inss->enable = 1;
				$inss->last_modify_at = date('Y-m-d H:i:s');
				$inss->save();
			}
			
			Database::table('user_pay_record')
				->where('user_id', $ins->user_id)
				->where('user_sub_gift_instance_id', $subid)
				->where('state', $notpay = 0)->find_result_set()->set('state', $invalid = 2)->save();
			
		}
		
		return $istest . "支付ID $payid 正在跳转到支付页面..";
		
	}
}