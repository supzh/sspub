<?php

namespace App\Controllers\Mu;

use App\Utils\Tools;
use Application\Database;

class NodeController
{
    public function users($id)
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
	    	->join('ss_node_gift', ['user_sub_gift_instance.user_gift_id' , '=' , 'ss_node_gift.user_gift_id'])
	    	->where('user_sub_gift_instance.state', $stateEnabled = 1)
	    	->where('ss_node_gift.ss_node_id', $id)
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

    public function onlineUserLog($node_id)
    {       
        $count = request()->input('count');
        $log = Database::table('ss_node_online_log')->create();
        $log->node_id = $node_id;
        $log->online_user = $count;
        $log->log_time = time();
        if (!$log->save()) {
            $res = [
                "ret" => 0,
                "msg" => "update failed",
            ];
            return response()->json($res);
        }
        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }

    public function info($node_id)
    {
        $load = request()->input('load');
        $uptime = request()->input('uptime');

        $log = Database::table('ss_node_info_log')->create();
        $log->node_id = $node_id;
        $log->load = $load;
        $log->uptime = $uptime;
        $log->log_time = time();
        if (!$log->save()) {
            $res = [
                "ret" => 0,
                "msg" => "update failed",
            ];
            return response()->json($res);
        }
        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }

    public function postTraffic($nodeId)
    {        
        $rate = 1;

        $input = request()->raw();//getBody()
        $datas = json_decode($input, true);
        
        foreach ($datas as $data) {
                           
//             $user = Database::table('user')->select('user_sub_gift_instance_class_ss.id')
//             ->join('user_sub_gift_instance', ['user_sub_gift_instance.user_id', '=', 'user.id'])
//             ->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
//             //->where('user_sub_gift_instance.state', $stateEnabled = 1)
//             ->where('user.id', $data['user_id'])
//             ->findOne();
        	$insId=  $data['user_id'];
        	$user = Database::table('user_sub_gift_instance_class_ss')->where('user_sub_gift_instance_id', $insId)->findOne();	            	           
	            
            $user->t = time();
            $user->u = $user->u + ($data['u'] * $rate);
            $user->d = $user->d + ($data['d'] * $rate);
            $user->save();

            // log
            $totalTraffic = Tools::flowAutoShow(($data['u'] + $data['d']) * $rate);
            $traffic = Database::table('user_traffic_log')->create();
            
            
            $ins = Database::table('user_sub_gift_instance')->where('id', $insId)->findOne();
            
            $traffic->user_id = $ins->user_id;
            
            
            $traffic->u = $data['u'];
            $traffic->d = $data['d'];
            $traffic->node_id = $nodeId;
            $traffic->user_sub_gift_instance_id= $insId;
            $traffic->traffic = $totalTraffic;
            $traffic->log_time = time();
            $traffic->save();
        }

        $res = [
            "ret" => 1,
            "msg" => "ok",
        ];
        return response()->json($res);
    }
}