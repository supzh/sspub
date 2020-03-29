<?php 

namespace App\Controllers;

use Application\Response;
use Application\Verify;
use Application\Database;
use Application\AppAPIException;
use Application\Request;

class AdminAPIController
{	
	public function __construct()
	{
		Response::setHeader('Access-Control-Allow-Origin', '*');
	}
	//post
	public function exportpayrecord()
	{
		
	}
	public function authorize($request, $next)
	{
		if( ! session()->get('userinfo', 'userId')) {
			response()->json(['code' => 3, 'msg' => '没有登录']);
		}
		if(  session()->get('userinfo', 'type') != ($isAdmin = 2)) {
			response()->json(['code' => 3, 'msg' => '没有权限']);
		}
		return $next($request);
	}
	//post
	public function newgift()
	{
		$request = request();
		$name = $request->input('name');
		$gift_class = 'ss';
		$mo_price =  $request->input('mo_price');
		$max_instance =  $request->input('max_instance');
		$discount =  $request->input('discount');
		$is_show =  $request->input('is_show');
		$allow_use_friendcode =  $request->input('allow_use_friendcode');
		$mo_bandwidth =  $request->input('mo_bandwidth');
		$created_at = date('Y-m-d H:i:s');
		
		$user_gift = Database::table('user_gift')->create();
		$user_gift->name = $name;
		$user_gift->gift_class = $gift_class;
		$user_gift->mo_price = $mo_price;
		$user_gift->max_instance = $max_instance;
		$user_gift->discount = $discount;
		$user_gift->is_show = $is_show;
		$user_gift->allow_use_friendcode = $allow_use_friendcode;
		$user_gift->created_at = $created_at;
		if( ! $user_gift->save()) {
			throw new AppAPIException("new_gift_failed_1");
		}
		$user_gift_id = $user_gift->id();
		$ss = Database::table('user_gift_class_ss')->create();
		$ss->mo_bandwidth = $mo_bandwidth;
		$ss->user_gift_id = $user_gift_id;
		$ss->created_at = $created_at;
		if( ! $ss->save()) {
			throw new AppAPIException("new_gift_failed_2");
		}
		
		return response()->json(['code'=>0]);
	}
	
	public function modifygift($id)
	{
		$request = request();
		$name = $request->input('name');
		$gift_class = 'ss';
		$mo_price =  $request->input('mo_price');
		$max_instance =  $request->input('max_instance');
		$discount =  $request->input('discount');
		$is_show =  $request->input('is_show');
		$allow_use_friendcode =  $request->input('allow_use_friendcode');
		$user_sub_max_mo = $request->input('user_sub_max_mo');
		$mo_bandwidth =  $request->input('mo_bandwidth');
		
		if(!$user_gift = Database::table('user_gift')->where('id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_1");
		}
		
		$user_gift->name = $name;
		$user_gift->gift_class = $gift_class;
		$user_gift->mo_price = $mo_price;
		$user_gift->max_instance = $max_instance;
		$user_gift->user_sub_max_mo= $user_sub_max_mo;
		$user_gift->discount = $discount;
		$user_gift->is_show = $is_show;
		$user_gift->allow_use_friendcode = $allow_use_friendcode;
		$user_gift->last_modify_at =  date('Y-m-d H:i:s');
		if( ! $user_gift->save()) {
			throw new AppAPIException("operation_failed_2");
		}		
		if(!$ss = Database::table('user_gift_class_ss')->where('user_gift_id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_3");
		}
		$ss->mo_bandwidth = $mo_bandwidth;
		$ss->last_modify_at= date('Y-m-d H:i:s');
		if( ! $ss->save()) {
			throw new AppAPIException("new_gift_failed_4");
		}
		
		return response()->json(['code'=>0]);
	}
	
	public function deletegift($id)
	{			
		if(!$user_gift = Database::table('user_gift')->where('id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_1");
		}
		$user_gift->delete();
		if(!$ss = Database::table('user_gift_class_ss')->where('user_gift_id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_1");
		}
		$ss->delete();
		return response()->json(['code'=>0]);
	}
	
	public function getgift($id)
	{
		$data = Database::table('user_gift')
		->select('user_gift.*')->select('user_gift_class_ss.mo_bandwidth')
		->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id','=','user_gift.id'])
		->where('user_gift.id', $id)
		->findOne();				
		if(!$data) {
			throw new AppAPIException("failed");
		}		
		return response()->json(['code'=>0, 'data' =>$data->asArray()]);
	}	
	//get
	public function gifts()
	{
		$request = request();
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;		
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_gift')
			->select('user_gift.*')->select('user_gift_class_ss.mo_bandwidth')
			->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id','=','user_gift.id'])->offset($pn * $ps)->limit($ps)->findArray();				
		if($data) {
			foreach($data as $k=>$v) {
				$data[$k]['used_num'] = Database::table('user_sub_gift_instance')->where('user_gift_id', $v['id'])->count();
			}
		}
		$datanum = Database::table('user_gift')->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id','=','user_gift.id'])->count();
		$pnum = ceil($datanum / $ps);
		
		return response()->json(['code'=>0, 'data' =>['data'=> $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum]]);
	}
	
	//get
	public function admingetcategorybyid($id)
	{	
		if(!$data = Database::table('support_category')->where('id', $id)->findOne()) {
			throw new AppAPIException("failed");
		}
		return response()->json(['code'=>0, 'data' => $data->asArray()]);
	}
	
	//get
	public function admingetdocbyid($id)
	{		
		if(!$data = Database::table('support_doc')->select('support_doc.*')->select('support_category.name', 'support_category_name')
			->join('support_category', ['support_category.id'  ,'=', 'support_doc.support_category_id'])
			->where('support_doc.id', $id)
			->findOne()) {
			throw new AppAPIException("failed");
		}
		
		return response()->json(['code'=> 0, 'data' => $data->asArray()]);											
	}
		
	//get
	public function admindeletecategorybyid($id)
	{
		if(!$data = Database::table('support_category')->where('id', $id)->findOne()) {
			throw new AppAPIException("failed");
		}
		$data->delete();
		return response()->json(['code'=>0]);
	}
	
	//get
	public function admindeletedocbyid($id)
	{
		if(!$data = Database::table('support_doc')
				->where('id', $id)
			->findOne()) {
			throw new AppAPIException("failed");
		}
		$data->delete();
		return response()->json(['code'=> 0]);
	}
	
	//get
	public function admingetcategory(Request $request)
	{
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('support_category')->offset($pn*$ps)->limit($ps)->findArray();
		$datanum = Database::table('support_category')->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);
	}

	//get
	public function admingetdocs()
	{
		$request = request();
		$pn = ($request->input('pn')) ? $request->input('pn') : 0;
		$ps = ($request->input('ps')) ? $request->input('ps') : 10;
		$data = Database::table('support_doc')->select('support_doc.*')->select('support_category.name')
			->join('support_category', ['support_category.id'  ,'=', 'support_doc.support_category_id'])
			->offset($pn * $ps)->limit($ps)
			->findArray();
		
		$datanum = Database::table('support_doc')
			->join('support_category', ['support_category.id'  ,'=', 'support_doc.support_category_id'])			
			->count();
			$pnum = ceil($datanum / $ps);
			
		return response()->json(['code'=>0, 'data' => ['data'=>$data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum]]);
	}
	//post
	public function adminnewcategory()
	{
		$request = request();
		$name = $request->input('name');
		$category = Database::table('support_category')->create();
		$category->name = $name;
		$category->created_at = date('Y-m-d H:i:s');
		if(!$category->save()) {
			throw new AppAPIException("add_failed");
		}		
		return response()->json(['code'=>0]);
	}
	//post
	public function adminnewdoc()
	{
		$request = request();
		$support_category_id= $request->input('support_category_id');
		$title = $request->input('title');
		$content = $request->input('content');
		$author= $request->input('author');
		if( ! $sort = $request->input('sort')){
			$sort = 10;
		}
		$created_at = date('Y-m-d H:i:s');
				
		$support_doc = Database::table('support_doc')->create();
		$support_doc->support_category_id= $support_category_id;
		$support_doc->title= $title;
		$support_doc->content= $content;
		$support_doc->author= $author;
		$support_doc->sort= $sort;
		$support_doc->created_at = $created_at;		
		
		if(!$support_doc->save()) {
			throw new AppAPIException("add_failed");
		}
		return response()->json(['code'=>0]);
	}
	
	public function adminmodifycategory($id)
	{
		$request = request();
		$name = $request->input('name');		
		
		if(!$d = Database::table('support_category')->where('id', $id)->findOne()) {
			throw new AppAPIException("modify_failed");
		}
		$d->name= $name;
		$d->last_modify_at= date('Y-m-d H:i:s');	
		
		if(!$d->save()) {
			throw new AppAPIException("modify_failed");
		}
		return response()->json(['code'=>0]);
	}
	
	public function adminmodifydoc($id)
	{
		$request = request();
		$support_category_id= $request->input('support_category_id');
		$title = $request->input('title');
		$content = $request->input('content');
		$author= $request->input('author');		
		if( ! $sort = $request->input('sort')){
			$sort = 10;
		}
		
		if(!$d = Database::table('support_doc')->where('id', $id)->findOne()) {
			throw new AppAPIException("modify_failed");
		}
		$d->title = $title;
		$d->content = $content;
		$d->author= $author;
		$d->sort = $sort;
		$d->last_modify_at= date('Y-m-d H:i:s');		
		if(!$d->save()) {
			throw new AppAPIException("modify_failed");
		}
		return response()->json(['code'=>0]);
	}
	
	//get
	public function admingettickets()
	{
		$request = request();;				
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_ticket')
			->select('user.email')->select('user.user_name')
			->select('user_ticket.*')->join('user', ['user.id', '=', 'user_ticket.user_id'])
			->offset($pn * $ps)->limit($ps)
			->findArray();
		$datanum = Database::table('user_ticket')->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);
	}
	public function admingetticket($id)
	{
		if(!$data = Database::table('user_ticket')
			->select('user.email')->select('user.user_name')
			->select('user_ticket.*')->join('user', ['user.id', '=', 'user_ticket.user_id'])
			->where('id', $id)
			->findOne()) {
			throw new AppAPIException('failed');
		}		
		return response()->json(['code'=>0, 'data' => $data->asArray()]);
	}
	//post
	public function adminreplyticket($id)
	{
		$request = request();		
		$reply_content = $request->input('reply_content');
		if(!$w = Database::table('user_ticket')->where('id', $id)->findOne()) {
			throw new AppAPIException('reply_failed_1');
		}
		$w->reply_content = $reply_content;
		$w->last_reply = date('Y-m-d H:i:s');
		$w->state = 2;
		if(!$w->save()) {
			throw new AppAPIException('reply_failed_2');
		}
		return response()->json(['code'=>0]);
	}
	//post
	
	public function deleteserver($id)
	{
		if(!$l = Database::table('ss_node')->where('id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_1");
		}
		$l->delete();
		return response()->json(['code'=>0]);
	}
	public function newserver()
	{
		$request = request();
		$name = $request->input('name');
		$server = $request->input('server');
		$state = $request->input('state');
		$is_show= $request->input('is_show');
		if(!$sort = $request->input('sort')) {
			$sort = 10;
		}
						
		$max_bandwidth_mo = $request->input('max_bandwidth_mo');		
		$ssh_port= $request->input('ssh_port');
		$ssh_user= $request->input('ssh_user');
		$ssh_password= $request->input('ssh_password');
		
		$c = Database::table('ss_node')->create();
		$c->name = $name;
		$c->server= $server;
		$c->state= $state;
		$c->sort = $sort;
		$c->is_show = $is_show;
	
		$c->max_bandwidth_mo= $max_bandwidth_mo;		
		$c->ssh_port= $ssh_port;
		$c->ssh_user= $ssh_user;
		$c->ssh_password= $ssh_password;						
		$c->created_at = date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('add_failed');
		}
		return response()->json(['code'=>0]);
		
	}
	
	public function modifyserver($id)
	{
		$request = request();
		$name = $request->input('name');
		$server = $request->input('server');
		$state = $request->input('state');
		$is_show= $request->input('is_show');
		if(!$sort = $request->input('sort')) {
			$sort = 10;
		}
	
		$max_bandwidth_mo = $request->input('max_bandwidth_mo');		
		$ssh_port= $request->input('ssh_port');
		$ssh_user= $request->input('ssh_user');
		$ssh_password= $request->input('ssh_password');
		
		$c = Database::table('ss_node')->where('id', $id)->findOne();
		
		$c->name = $name;
		$c->server= $server;
		$c->state= $state;
		$c->sort = $sort;
		$c->is_show= $is_show;
		$c->max_bandwidth_mo= $max_bandwidth_mo;		
		$c->ssh_port= $ssh_port;
		$c->ssh_user= $ssh_user;
		$c->ssh_password= $ssh_password;
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('modify_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	//get
	public function servers()
	{
		$request = request();
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('ss_node')->offset($pn * $ps)->limit($ps)->findArray();
		$datanum = Database::table('ss_node')->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);						
	}
	public function server($id)
	{		
		if(!$data = Database::table('ss_node')->where('id', $id)->findOne()) {
			throw new AppAPIException('failed');
		}				
		return response()->json(['code'=>0, 'data'=>  $data->asArray()]);
	}
	public function deleteuser($id)
	{
		if(!$l = Database::table('user')->where('id', $id)->findOne()) {
			throw new AppAPIException("operation_failed_1");
		}
		$l->delete();
		return response()->json(['code'=>0]);
	}
	//post
	public function newuser()
	{
		$request = request();
		$type = $request->input('type');		
		$email= $request->input('email');
		$user_name = explode('@',$email);
		$user_name = $user_name[0];
		$pass= $request->input('pass');		
		$reg_ip = $request->server('REMOTE_ADDR');
		$disabled = $request->input('disabled');
		
		if(Database::table('user')->where('email', $email)->findOne()) {
			return response()->json(['code'=>1]);
		}		
		$c = Database::table('user')->create();
		$c->type = $type;	
		$c->user_name= $user_name;
		$c->email= $email;
		$c->disabled= $disabled;		
		$c->pass= md5($pass);
		$c->reg_date=  date('Y-m-d H:i:s');
		$c->reg_ip= $reg_ip;
		
		if(!$c->save()) {
			throw new AppAPIException('reply_failed_2');
		}
		return response()->json(['code'=>0]);
				
	}
	public function modifyuser($id)
	{
		$request = request();
		$type = $request->input('type');		
		$email= $request->input('email');
		$pass= $request->input('pass');
		$disabled = $request->input('disabled');		
		
		$c = Database::table('user')->where('id', $id)->findOne();		
		$c->type = $type;	
		$c->email= $email;
		if($pass) {
			$c->pass= md5($pass);
		}
		$c->disabled= $disabled;		
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('modify_failed');
		}
		return response()->json(['code'=>0]);
	}
	//get
	public function users()
	{
		$request = request();		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		if($data = Database::table('user')				
				->offset($pn * $ps)->limit($ps)
				->findArray()) {
			foreach($data as $k=>$v){
				unset($data[$k]['pass']);
				if(!$data[$k]['paid_money'] = Database::table('user_pay_record')->where('user_id', $v['id'])->sum('pay_mount')) {
					$data[$k]['paid_money'] = '暂无';
				}
				
				if(!$data[$k]['used_instance'] = Database::table('user_sub_gift_instance')->where('state', $enabled=1)->where('user_id', $v['id'])->count()) {
					$data[$k]['used_instance'] = '暂无';
				}
			}
		}
		$datanum = Database::table('user')->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);	
	}
	public function user($id)
	{
		if(!$data = Database::table('user')->where('id', $id)->findOne()) {
			throw new AppAPIException('failed');
		}
		unset($data['pass']);
		return response()->json(['code'=>0, 'data'=>  $data->asArray()]);
	}
	//post
	public function newfriendcode()
	{
		$request = request();		
		$limit_times= $request->input('limit_times');
		$disabled= $request->input('disabled');
		$discount= $request->input('discount');
		
		$c = Database::table('user_friendcode')->create();
		$c->created_at = date('Y-m-d H:i:s');
		$c->code = randomStr(16, '', true);
		$c->disabled= $disabled;
		$c->discount= $discount;
		$c->limit_times = $limit_times;
		if(!$c->save()) {
			throw new AppAPIException('add_failed');
		}
		return response()->json(['code'=>0]);
	}
	public function modifyfriendcode($id)
	{
		$request = request();
		$limit_times= $request->input('limit_times');
		$disabled= $request->input('disabled');
		$discount= $request->input('discount');
		
		$c = Database::table('user_friendcode')->where('id', $id)->findOne();
		$c->disabled= $disabled;
		$c->discount= $discount;
		$c->limit_times = $limit_times;
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('modify_failed');
		}
		return response()->json(['code'=>0]);
	}
	//get
	public function friendcodes()
	{
		$request = request();		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_friendcode')->offset($pn * $ps)->limit($ps)->findArray();
		$datanum = Database::table('user_friendcode')->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);	
		
	}
	public function friendcode($id)
	{
		if(!$data = Database::table('user_friendcode')->where('id', $id)->findOne()) {
			throw new AppAPIException();
		}		
		return response()->json(['code'=>0, 'data' =>$data->asArray()]);
		
	}
	public function friendcodeusedrecord()
	{
		$request = request();		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_friendcode_used_record')
			->join('user', ['user.id', '=', 'user_friendcode.user_id'])
			->join('user_friendcode', ['user_friendcode.id', '=', 'user_friendcode_used_record.user_friendcode_id'])
			->offset($pn * $ps)->limit($ps)->findArray();
		$datanum =  Database::table('user_friendcode_used_record')
			->join('user', ['user.id', '=', 'user_friendcode.user_id'])
			->join('user_friendcode', ['user_friendcode.id', '=', 'user_friendcode_used_record.user_friendcode_id'])
			->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);
		
		
	}	
	//get
	public function payrecords()
	{
		$request = request();
		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_pay_record')
			->select('user_pay_record.*')
			->select('user_gift.name')
			->select('user.user_name')
			->select('user.email')			
			->join('user_gift', ['user_pay_record.user_gift_id', '=', 'user_gift.id'])
			->join('user', ['user_pay_record.user_id', '=', 'user.id'])
			->offset($pn * $ps)->limit($ps)->findArray();
		$datanum =  Database::table('user_pay_record')
			->join('user_gift', ['user_pay_record.user_gift_id', '=', 'user_gift.id'])->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);		
	}
	//post
	public function updatesiteinfo()
	{
		$request = request();
		$sitename= $request->input('sitename');
		$analysis_code= $request->input('analysis_code');
		$homepage_msg= $request->input('homepage_msg');
		$usercenter_msg= $request->input('usercenter_msg');
		$is_open_register= $request->input('is_open_register');
		$sitelogo = $request->input('sitelogo');
		$usercenterlogo= $request->input('usercenterlogo');
						
		Database::table('sp_config')->where('key', 'sitename')->findOne()->set('value', $sitename)->save();
		Database::table('sp_config')->where('key', 'sitelogo')->findOne()->set('value', $sitelogo)->save();
		Database::table('sp_config')->where('key', 'usercenterlogo')->findOne()->set('value', $usercenterlogo)->save();
		Database::table('sp_config')->where('key', 'analysis_code')->findOne()->set('value', $analysis_code)->save();
		Database::table('sp_config')->where('key', 'homepage_msg')->findOne()->set('value', $homepage_msg)->save();
		Database::table('sp_config')->where('key', 'usercenter_msg')->findOne()->set('value', $usercenter_msg)->save();
		Database::table('sp_config')->where('key', 'is_open_register')->findOne()->set('value', $is_open_register)->save();
		return response()->json(['code'=>0]);
	}
	//get
	public function siteinfo()
	{	
		$data['sitename'] = Database::table('sp_config')->select('value')->where('key', 'sitename')->findArray()[0]['value'];
		$data['sitelogo'] = Database::table('sp_config')->select('value')->where('key', 'sitelogo')->findArray()[0]['value'];
		$data['usercenterlogo'] = Database::table('sp_config')->select('value')->where('key', 'usercenterlogo')->findArray()[0]['value'];
		$data['analysis_code'] = Database::table('sp_config')->select('value')->where('key', 'analysis_code')->findArray()[0]['value'];
		$data['homepage_msg'] = Database::table('sp_config')->select('value')->where('key', 'homepage_msg')->findArray()[0]['value'];
		$data['usercenter_msg'] = Database::table('sp_config')->select('value')->where('key', 'usercenter_msg')->findArray()[0]['value'];
		$data['is_open_register'] = Database::table('sp_config')->select('value')->where('key', 'is_open_register')->findArray()[0]['value'];
		return response()->json(['code'=>0, 'data' => $data]);
	}
	//get
	public function admincenterinfo()
	{		
		$data['all_user_num'] = Database::table('user')->count();
		
		$data['recent_online_user_num'] = 0;
		if($nodes = Database::table('ss_node')->find_array()) {
			foreach($nodes as $node) {
				$online = Database::table('ss_node_online_log')->whereGte('log_time',time()-60*60)->where('node_id', $node['id'])->orderByDesc('id')->findOne();				
				$data['recent_online_user_num']+=$online->online_user;
			}
		}
		
		$data['recent_online_generate_traffic_num'] = humanReadSize(Database::table('user_traffic_log')->whereGte('log_time',time()-60*60)->sum('d'));
		
		$data['sspub_version'] = config('app','version');
		$data['php_version']= PHP_VERSION;
		
		return response()->json(['code'=>0, 'data' => $data]);
	}	
	
}