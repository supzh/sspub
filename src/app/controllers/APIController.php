<?php 

namespace App\Controllers;

use Application\Response;
use Application\Verify;
use Application\Database;
use Application\AppAPIException;
use App\Utils\Tools;
use Application\Request;

class APIController
{
	
	public function __construct()
	{
		Response::setHeader('Access-Control-Allow-Origin', '*');
	}
	
	
	public function getmacclientdownload($id)
	{
		$userId = session()->get('userinfo', 'userId');
		if(!$ins = Database::table('user_sub_gift_instance')
				->where('user_sub_gift_instance.user_id', $userId)
				->where('user_sub_gift_instance.id', $id)
				->select('user_sub_gift_instance_class_ss.port')
				->select('user_sub_gift_instance_class_ss.passwd')
				->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
				->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id'])
				->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id'])
				->findOne()) {
					return response()->json(['code'=>1,'msg'=>'处理异常']);
				}				
				$configfilepath = appPath('storage/clients/mac/usersub/ssspub_subid_'.$id.'_shadowsocks-ng-client.app');
				$configfilepath = '/ssuserclient/sub'.$id.'_'.date('YmdHis').'_ssng-client.app';
				$downloadpath = 'storage/clients/mac/usersub_'.date('YmdHis').'_'.$id.'_shadowsocks-ng-client.app.zip';
				$outZipPath =  appPath($downloadpath);				
				$filename = 'some-server-gui-config.json';
				$config = $configfilepath.'/Contents/Resources/'.$filename;
				
				$appfileinfo = 	readAllFiles( appPath( 'storage/clients/ShadowsocksX-NG.app'));
				
				if($appdirs = $appfileinfo['dirs']) {
					foreach($appdirs as $v) {
						$dir = explode('ShadowsocksX-NG.app', $v);
						
						mkPath($configfilepath . $dir[1]);	
						chmod ($configfilepath . $dir[1], 0777);
					}
				}
				
				if($appfiles = $appfileinfo['files']) {
					foreach($appfiles as $v) {
						$newfilepath = explode('ShadowsocksX-NG.app', $v);
						copy($v,  $configfilepath . $newfilepath[1]);
						chmod( $configfilepath . $newfilepath[1], $mode = 0777);
					}
				}
				
				$fp = fopen($config,'w');
				
				if(!$node = Database::table('ss_node')->where('state', $isnodeenabled = 1)->findOne()) {
					$nodeserver = '127.0.0.1';
				} else {
					$nodeserver = $node->server;
				}
				
				$content = '{
    "index": 0,
    "random": false,
    "global": false,
    "enabled": true,
    "shareOverLan": false,
    "isDefault": false,
    "localPort": 1080,
    "pacUrl": null,
    "useOnlinePac": false,
    "reconnectTimes": 3,
    "randomAlgorithm": 0,
    "TTL": 0,
    "proxyEnable": false,
    "proxyType": 0,
    "proxyHost": null,
    "proxyPort": 0,
    "proxyAuthUser": null,
    "proxyAuthPass": null,
    "authUser": null,
    "authPass": null,
    "autoban": false,
    "configs": [{
                "remarks": "SSPUB autogen",
                "server": "'.$nodeserver.'",
                "server_port": '.$ins->port.',
                "method": "rc4-md5",
                "remarks_base64": "'.base64_encode('SSPUB autogen').'",
                "password": "'.$ins->passwd.'",
                "tcp_over_udp": false,
                "udp_over_tcp": false,
                "enable": true
                }]
}
';
				fwrite($fp, $content);
				fclose($fp);
				
				//\HZip::zipDir($configfilepath, $outZipPath);
				
				//echo "zip -r $outZipPath $configfilepath";exit;
				exec("zip -r $outZipPath $configfilepath");
				
				$url = 'http://'. request()->server('HTTP_HOST').'/'.$downloadpath;
				return response()->json(['code'=>0,'data'=> ['url'=> $url ]]);
	}
	
	public function getclientdownload($id)
	{		
		$userId = session()->get('userinfo', 'userId');		
		
		if(!$ins = Database::table('user_sub_gift_instance')
			   ->where('user_sub_gift_instance.user_id', $userId)
				->where('user_sub_gift_instance.id', $id)
				->select('user_sub_gift_instance_class_ss.port')
				->select('user_sub_gift_instance_class_ss.passwd')
 				->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
 				->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id'])
 				->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id'])
				->findOne()) {
			return response()->json(['code'=>1,'msg'=>'处理异常']);
		}
					
		$configfilepath = appPath('storage/clients/windows/usersub/ssspub_subid_'.$id.'_client');
		$downloadpath = 'storage/clients/windows/usersub_'.date('YmdHis').'_'.$id.'.zip';
		$outZipPath =  appPath($downloadpath);
		
		$filename = 'gui-config.json';		
		$config = $configfilepath.'/'.$filename;
			
		mkPath($configfilepath);	
		
		
		copy(appPath('storage/clients/Shadowsocks-3.2/Shadowsocks.exe'), $configfilepath.'/Shadowsocks.exe');
		copy(appPath('storage/clients/Shadowsocks-3.2/statistics-config.json'), $configfilepath.'/statistics-config.json');		

		$fp = fopen($config,'w');
			
		if(!$node = Database::table('ss_node')->where('state', $isnodeenabled = 1)->findOne()) {
			$nodeserver = '127.0.0.1';
		} else {
			$nodeserver = $node->server;
		}
		
		$content = '
{
  "configs": [
    {
      "server": "'.$nodeserver.'",
      "server_port": '.$ins->port.',
      "password": "'.$ins->passwd.'",
      "method": "rc4-md5",
      "remarks": "SSPUB autogen",
      "auth": true
    }
  ],
  "strategy": null,
  "index": 0,
  "global": false,
  "enabled": true,
  "shareOverLan": false,
  "isDefault": false,
  "localPort": 1080,
  "pacUrl": null,
  "useOnlinePac": false,
  "availabilityStatistics": false,
  "autoCheckUpdate": true,
  "isVerboseLogging": false,
  "logViewer": null,
  "useProxy": false,
  "proxyServer": null,
  "proxyPort": 0
}
';		
		fwrite($fp, $content);
		fclose($fp);
	
		\HZip::zipDir($configfilepath, $outZipPath);
		
		$url = 'http://'. request()->server('HTTP_HOST').'/'.$downloadpath;		
		return response()->json(['code'=>0,'data'=> ['url'=> $url ]]);
	}
	
	public function sendresetpwdverifymail(Request $request)
	{
		if(!$email = trim($request->input('email'))) {
			return response()->json(['code'=>1,'msg'=>'请您输入注册邮箱']);
		}
		if(!Verify::isEmail($email)) {
			return response()->json(['code'=>1,'msg'=>'注册邮箱格式有误']);
		}		
		
		$token = randomStr(6);
		
		$c = Database::table('sp_email_verify')->create();
		$c->email = $email;
		$c->token = $token;
		$c->created_at = date('Y-m-d H:i:s');
		$c->expire_at= time()+86400;
		if(!$c->save()) {
			return response()->json(['code'=>1,'msg'=>'处理失败']);
		}
		
		if(!$user = Database::table('user')->where('email', $email)->findOne()) {
			return response()->json(['code'=>1,'msg'=>'注册邮箱不存在']);
		}
		
		self::handlesendresetpwdverifymail($email, $token,$user);		
			return response()->json(['code'=>0]);							
	}
	
	public static function handlesendresetpwdverifymail($email, $token, $user)
	{
		$subject = 'SSPUB 密码恢复验证邮件';
		$content = "
<p>尊敬的SSPUB用户{$user->user_name}，您好：</p>
<br />
<p>您于最近进行了恢复密码动作，以下为您的恢复密码所需验证码：
<br />
<b>$token</b>
<br />
该验证码24小时内有效，使用后即作废，请妥善保管。</p>
<br />
<p>非常感谢！</p>
<br />
<p>SSPUB
<br />
".date('Y年m月d日 H时i分s秒')."
<br />
(邮件由系统发出，请勿回复)
</p>
";
		\Mailer::send($subject, $content, $email);
	}
	
	public function confirmresetpass(Request $request)
	{
		if(!$email = trim($request->input('email'))) {
			return response()->json(['code'=>1,'msg'=>'请您输入注册邮箱']);
		}
		if(!Verify::isEmail($email)) {
			return response()->json(['code'=>1,'msg'=>'注册邮箱格式有误']);
		}
		if(!$code = $request->input('code')) {
			return response()->json(['code'=>1,'msg'=>'请您输入验证码']);
		}		
		if(!$new_pass= $request->input('new_pass')) {
			return response()->json(['code'=>1,'msg'=>'请您输入新密码']);
		}		
		if(! $verify = Database::table('sp_email_verify')->where('email', $email)->where('token', $code)->whereGte('expire_at', time())->findOne()) {
			return response()->json(['code'=>1,'msg'=>'验证码不正确或已失效']);
		} 		
		if(!$user = Database::table('user')->where('email', $email)->findOne()) {
			return response()->json(['code'=>1,'msg'=>'找不到注册邮箱用户']);
		}
		$user->pass = md5($new_pass);
		$user->last_modify_at = date('Y-m-d H:i:s');
		if($user->save()) {			
			Database::table('sp_email_verify')->where('email', $email)->find_result_set()->delete();			
			return response()->json(['code'=>0,'msg'=>'恢复密码成功']);
		} else {
			return response()->json(['code'=>1,'msg'=>'处理失败']);
		}
		
	}
	
	public function notifysetting()
	{
		$userId = session()->get('userinfo', 'userId');
		if(!$data = Database::table('user')
				->select('send_traffic_log_email_notify')
				->select('send_sub_expire_email_notify')
				->where('id', $userId)->findOne()) {
			throw new AppAPIException('failed');
		}		
		return response()->json(['code'=>0,'data'=>$data->asArray()]);
	}
	
	public function changenotify()
	{
		$userId = session()->get('userinfo', 'userId');
		
		$send_traffic_log_email_notify = request()->input('send_traffic_log_email_notify');
		$send_sub_expire_email_notify = request()->input('send_sub_expire_email_notify');
		
		if(!$data = Database::table('user')			
				->where('id', $userId)->findOne()) {
					throw new AppAPIException('failed');
				}				
		$data->last_modify_at = date('Y-m-d H:i:s');
		$data->send_traffic_log_email_notify = $send_traffic_log_email_notify;
		$data->send_sub_expire_email_notify = $send_sub_expire_email_notify;
		$data->save();
				
		return response()->json(['code'=>0]);		
	}
	
	public function loginstate()
	{		
		response()->json([
				'code' => 0,
				'userId' => session()->get('userinfo', 'userId'),
				'userName' => session()->get('userinfo', 'userName'),
				'type' => session()->get('userinfo', 'type'),				
				'email' => session()->get('userinfo', 'email'),
				'sessionId' => session()->id(),
		]);
	}
	
	public function login()
	{				
		$email = request()->input('email');
		$password = request()->input('password');		
		if ( ! Verify::isNotEmpty($email)) {
			response()->json(['code' => 3, 'msg' => '邮箱不能为空']);
		}
		if ( ! Verify::isNotEmpty($password)) {
			response()->json(['code' => 3, 'msg' => '密码不能为空']);			
		}
		
		if ( ! Verify::isEmail($email)) {
			response()->json(['code' => 3, 'msg' => '用户名格式有误']);
		}		
		$login = function($email, $password){
			$user = Database::table('user')->where('email', $email)->findOne();
			if(!$user) {
				return false;
			}
			if($user->pass != md5($password)) {
				return false;
			}
			
			$user->last_login_at = date('Y-m-d H:i:s');
			$user->save();
			
			return $user;
		};
		if( ! $user = $login($email, $password)) {
			response()->json(['code' => 3, 'msg' => '您输入的用户名或密码有误']);		
		}			
		if($user->disabled) {
			response()->json(['code' => 3, 'msg' => '您的账户目前无法登录']);		
		}
		session()->set('userinfo', [
				'userId' =>  $user->id,
				'userName' => $user->user_name,
				'type' =>  $user->type,
				'email' =>  $user->email,
		]);		
		response()->json([
				'code' => 0,
				'userId' => $user->id,
				'userName' =>  $user->user_name,
				'email' => $user->email,
				'type' => $user->type,
				'sessionId' => session()->id(),				
		]);		
	}
	
	public function register()
	{
		if(!Database::table('sp_config')->select('value')->where('key', 'is_open_register')->findArray()[0]['value']) {
			response()->json(['code' => 3, 'msg' => '暂停注册中..']);
		}
		$email = request()->input('email');		
		$password = request()->input('password');
		$username = request()->input('username');
		if ( ! $username) {
			response()->json(['code' => 3, 'msg' => '昵称不能为空']);
		}
		if ( ! Verify::isNotEmpty($password)) {
			response()->json(['code' => 3, 'msg' => '密码不能为空']);
		}		
		if ( ! Verify::isEmail($email)) {
			response()->json(['code' => 3, 'msg' => '邮箱格式有误']);
		}		
		$isEmailhasBeenTaken = function($email) {
			return Database::table('user')->where('email', $email)->findOne();
		};
		$register = function($email, $password, $username){
			$user = Database::table('user')->create();
			$user->email = $email;
			$user->pass = md5($password);
			$user->user_name = $username;
			$user->reg_date = date('Y-m-d H:i:s');
			$user->reg_ip = request()->server('REMOTE_ADDR');
			return $user->save();
		};
		if($isEmailhasBeenTaken($email)) {
			response()->json(['code' => 3, 'msg' => '邮箱已被注册']);
		}				
		if( ! $register($email, $password, $username)) {
			response()->json(['code' => 3, 'msg' => '注册失败']);
		}
		response()->json(['code' => 0]);
	}
	
	public function logout()
	{
		session()->set('userinfo', [
				'userId' =>  '',
				'userName' => ''
		]);
		response()->json(['code' => 0]);
	}
	
	public function authorize($request, $next)
	{
		if( ! session()->get('userinfo', 'userId')) {
			response()->json(['code' => 3, 'msg' => '没有登录']);
		}
		return $next($request);
	}
	
	public function giftlist()
	{
		$getGiftList = function(){
			return Database::table('user_gift')
				->select('user_gift.*')
				->select('user_gift_class_ss.mo_bandwidth')
				->where('user_gift.gift_class', 'ss')
				->join('user_gift_class_ss', ['user_gift.id', '=', 'user_gift_class_ss.user_gift_id'])
				->findArray();
		};		
		response()->json(['code' => 0, 'data' => $getGiftList()]);
	}
	
	public function newsub()
	{
		$userId = session()->get('userinfo', 'userId');
		$giftId = request()->input('giftId');
		$mo = request()->input('mo');				
		
		if( ! Verify::isNumRange($mo, 3, 1, 12)) {
			response()->json(['code' => 3, 'msg' => '订阅时长无效']);
		}
		
		$isGiftValid = function($giftId){			
			
			return Database::table('user_gift')
				->select('user_gift.*')
				->select('user_gift_class_ss.mo_bandwidth')
				->where('user_gift.id', $giftId)
				->where('user_gift.gift_class', 'ss')
				->join('user_gift_class_ss', ['user_gift.id', '=', 'user_gift_class_ss.user_gift_id'])
				->findOne();
		};
		
		if( ! $gift =  $isGiftValid($giftId)) {
			response()->json(['code' => 3, 'msg' => '订阅内容无效']);
		}
		
		$newsub = function($userId, $gift, $mo){
			$sub = Database::table('user_sub_gift_instance')->create();
			$sub->user_id = $userId;
			$sub->user_gift_id = $gift->id;
			$sub->mo = $mo;
			
			$sub->cost_money = $gift->mo_price * $mo * $gift->discount;
			$sub->instance_discount= $gift->discount;
			$sub->created_at = date('Y-m-d H:i:s');
			if( ! $sub->save()) {
				return false;
			}
			$subclass = Database::table('user_sub_gift_instance_class_ss')->create();
			$subclass->user_sub_gift_instance_id = $sub->id();
			$subclass->passwd = randomStr();
			$subclass->transfer_enable = $gift->mo_bandwidth * 1025 * 1025 * 1025;
			$subclass->method = 'rc4-md5';
			$subclass->port = 1025 +  $sub->id();
			$subclass->created_at = date('Y-m-d H:i:s');
			if(!$subclass->save()) {
				$sub->delete();
				return false;
			}
			return $sub->id();
		};
		
		$subId = $newsub($userId, $gift, $mo);
		response()->json(['code' => 0, 'subId' => $subId]);		
	}
	
	public function usefriendcode($id)
	{
		$userId = session()->get('userinfo', 'userId');
		if(!$friendcode = request()->input('friendcode')) {
			response()->json(['code' => 1, 'msg'=>'请您输入邀请码']);
		}
		if(!$instance = Database::table('user_sub_gift_instance')
			->where('user_sub_gift_instance.user_id', $userId)
			->where('user_sub_gift_instance.id', $id)
			->whereNotEqual('user_sub_gift_instance.state', $deleted = 3)									
			->findOne()) {
				response()->json(['code' => 2, 'msg'=>'使用失败']);
		}
		
		if($instance->friendcode) {
			response()->json(['code' => 6, 'msg'=>'该订阅已使用邀请码，无法重复使用']);
		}
		
		if(!$f = Database::table('user_friendcode')->where('code', $friendcode)->where('disabled', 0)->findOne()) {
			response()->json(['code' => 3, 'msg'=>'邀请码无法使用']);
		}
		if($f->disabled) {
			response()->json(['code' => 7, 'msg'=>'邀请码目前无法使用']);
		}		
		if($f->used_times >= $f->limit_times ) {
			response()->json(['code' => 4, 'msg'=>'邀请码无法使用']);
		}
		$f->used_times++;
		$f->save();
		$instance->cost_money = $new_cost = $instance->cost_money * (float)$f->discount;		
		$instance->friendcode = $f->id;
		
		if(!$instance->save()) {
			response()->json(['code' => 5, 'msg' => '使用失败']);
		}		
		$c = Database::table('user_friendcode_used_record')->create();
		$c->user_id= $userId;
		$c->user_friendcode_id = $f->id;
		$c->user_sub_gift_instance_id= $id;
		$c->created_at= date('Y-m-d H:i:s');
		$c->save();
				
		response()->json(['code' => 0, 'data' => ['cost_money' => $new_cost, 'discount'=>(float)$f->discount]]);		
	}
	
	public function mysub()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		if($mysub =  Database::table('user_sub_gift_instance')
				->where('user_sub_gift_instance.user_id', $userId)
				->offset($pn * $ps)->limit($ps)
				->whereNotEqual('user_sub_gift_instance.state', $deleted = 3)
				->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
				->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id'])
				->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id'])
				->findArray()) {
			foreach($mysub as &$v) {
				$v['transfer_enable'] = humanReadSize($v['transfer_enable']);
			}
		}
							
		$datanum = Database::table('user_sub_gift_instance')
		->whereNotEqual('user_sub_gift_instance.state', $deleted = 3)
		->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
		->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id'])
		->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id'])
		->where('user_id', $userId)->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $mysub, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);		
	}
	
	public function subdetail($subId)
	{
		if( ! Verify::isNumFormat($subId)) {
			response()->json(['code' => 3, 'msg' => '订阅ID无效']);
		}
		$userId = session()->get('userinfo', 'userId');
		$getSubDetail = function($subId, $userId){		
			return Database::table('user_sub_gift_instance')
				->where('user_sub_gift_instance.user_id', $userId)
				->where('user_sub_gift_instance.id', $subId)
				->whereNotEqual('user_sub_gift_instance.state', $deleted = 3)
	 			->join('user_sub_gift_instance_class_ss', ['user_sub_gift_instance.id', '=', 'user_sub_gift_instance_class_ss.user_sub_gift_instance_id'])
	 			->join('user_gift', ['user_sub_gift_instance.user_gift_id', '=', 'user_gift.id'])
	 			->join('user_gift_class_ss', ['user_gift_class_ss.user_gift_id', '=', 'user_sub_gift_instance.user_gift_id'])
				->findOne();
		};
		
		if($detail = $getSubDetail($subId, $userId)) {
			$detail->transfer_enable = humanReadSize($detail->transfer_enable);
			$detail->d = humanReadSize($detail->d);
			
			response()->json(['code' => 0, 'data' => $detail->asArray()]);
		} else {
			response()->json(['code' => 3, 'msg' => '找不到订阅ID相关内容']);
		}
	}
	
	public function tickets()
	{
		$userId = session()->get('userinfo', 'userId');		
		$request = request();
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_ticket')
			->offset($pn * $ps)->limit($ps)->where('user_id', $userId)->findArray();
		$datanum = Database::table('user_ticket')->where('user_id', $userId)->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);
	}
	
	public function ticket($id)
	{
		$userId = session()->get('userinfo', 'userId');	
		if(!$data = Database::table('user_ticket')->where('id', $id)
					->where('user_id', $userId)->findOne()) {
						throw new AppAPIException('failed');
					}				
		return response()->json(['code'=>0, 'data'=>  $data->asArray()]);
	}
	
	public function subcancel($id)
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');	
		$c = Database::table('user_sub_gift_instance')->where('id', $id)->where('user_id', $userId)->findOne();	
		$c->state = 2;//cancel
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('cancel_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	public function subdelete($id)
	{
		$request = request();		
		$userId = session()->get('userinfo', 'userId');	
		$c = Database::table('user_sub_gift_instance')->where('id', $id)->where('user_id', $userId)->findOne();
		$c->state = 3;//deleted
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('delete_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	public function resetsubpassword($id)
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');	
		if(!$c = Database::table('user_sub_gift_instance')->where('id', $id)		
			->where('user_sub_gift_instance.user_id', $userId)
			->findOne()){
			throw new AppAPIException('opeartion_failed_1');
		}
		
		$c = Database::table('user_sub_gift_instance_class_ss')->where('user_sub_gift_instance_id', $id)			
			->findOne();
		$c->passwd = $newpasswd =  randomStr(6);
		$c->last_modify_at= date('Y-m-d H:i:s');
		if(!$c->save()) {
			throw new AppAPIException('opeartion_failed_2');
		}
		return response()->json(['code'=>0, 'data'=> $newpasswd]);
	}
	
	public function trafficlog($id)
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');	
		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		if($data = Database::table('user_traffic_log')
			->offset($pn * $ps)->limit($ps)
			->where('user_sub_gift_instance_id', $id)->where('user_id', $userId)->order_by_desc('id')->findArray()) {
				foreach($data as &$v) {
					$node_name = Database::table('ss_node')->where('id', $v['node_id'])->findOne();
					$v['node_name'] = $node_name->name;
					$v['log_time']  = date('Y-m-d H:i:s', $v['log_time']);
				}
			}
							
			$datanum = Database::table('user_traffic_log')->where('user_sub_gift_instance_id', $id)->where('user_id', $userId)->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);	
	}
	
	public function servers()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		if($data = Database::table('ss_node')->offset($pn * $ps)->limit($ps)->order_by_asc('sort')->where('is_show', $show = 1)->findArray()) {
			foreach($data as &$v) {
				if($online_user = Database::table('ss_node_online_log')->where('node_id', $v['id'])->order_by_desc('id')->findOne()) {
					$v['online_user'] = $online_user['online_user'];
				} else {
					$v['online_user'] = '没有数据';
				}
			}
		}
		$datanum =  Database::table('ss_node')->where('is_show', $show = 1)->count();		
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);
	}
	
	public function bill()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');
		
		$pn = ($request->input('pn')) ? (int)$request->input('pn') : 0;
		$ps = ($request->input('ps')) ? (int)$request->input('ps') : 10;
		$data = Database::table('user_pay_record')
			->select('user_pay_record.*')
			->select('user_gift.name')
			->select('user.user_name')
			->select('user.email')
			->select('user_sub_gift_instance.mo')
			->select('user_sub_gift_instance.id', 'user_sub_gift_instance_id')
			->join('user_gift', ['user_pay_record.user_gift_id', '=', 'user_gift.id'])
			->join('user_sub_gift_instance', ['user_sub_gift_instance.id', '=', 'user_pay_record.user_sub_gift_instance_id'])
			->join('user', ['user_pay_record.user_id', '=', 'user.id'])
			->where('user_id', $userId)
			->whereNotEqual('user_pay_record.state', $invalid = 2)
			->offset($pn * $ps)->limit($ps)->orderByDesc('user_pay_record.id')->findArray();
		$datanum =  Database::table('user_pay_record')
				
		->join('user_sub_gift_instance', ['user_sub_gift_instance.id', '=', 'user_pay_record.user_sub_gift_instance_id'])
		->join('user', ['user_pay_record.user_id', '=', 'user.id'])
		->where('user_id', $userId)
		->whereNotEqual('user_pay_record.state', $invalid = 2)
		->join('user_gift', ['user_pay_record.user_gift_id', '=', 'user_gift.id'])->count();
		$pnum = ceil($datanum / $ps);
		return response()->json(['code'=>0, 'data' => [ 'data'=>  $data, 'ps'=>$ps, 'pn'=>$pn, 'pnum'=>$pnum, 'datanum'=>$datanum ]]);	
	}
	
	public function category()
	{
		$request = request();
		if(!$limit = $request->input('limit')) {
			$limit = 99;
		}
		$data = Database::table('support_category')->limit($limit)->findArray();
		return response()->json(['code'=>0, 'data'=> $data]);
	}
	
	public function popularquestion()
	{
		$request = request();
		if(!$limit = $request->input('limit')) {
			$limit = 99;
		}
		
		
		
		$data = Database::table('support_doc')->limit($limit)
			->select('support_category.name')
			->select('support_doc.*')
			->join('support_category', ['support_doc.support_category_id','=', 'support_category.id'])
			->where('support_category.name', 'FAQ')
			->findArray();
		return response()->json(['code'=>0, 'data'=> $data]);
	}
	
	public function docsbycategory()
	{				
		$name = request()->input('name');		
		$data = Database::table('support_doc')
						
			->select('support_doc.id')
			->select('support_doc.title')
			->join('support_category', ['support_doc.support_category_id','=', 'support_category.id'])
			->where('support_category.name', $name)						
			->order_by_asc('sort')->findArray();				
		return response()->json(['code'=>0, 'data'=> $data]);
	}
	
	public function guidedocs()
	{
		$data = Database::table('support_doc')
			->select('support_doc.id')
			->select('support_doc.title')
			->join('support_category', ['support_doc.support_category_id','=', 'support_category.id'])
			->whereNotEqual('support_category.name', 'FAQ')
			->order_by_asc('sort')->findArray();
		
		return response()->json(['code'=>0, 'data'=> $data]);
	}
	
	public function docbytitle()
	{
		$title = request()->input('title');
		if($data = Database::table('support_doc')		
			->select('support_category.name')
			->select('support_doc.*')
			->join('support_category', ['support_doc.support_category_id','=', 'support_category.id'])
			->where('support_doc.title', $title)->findOne()) {
				$data->click_num++;
				$data->save();
			$data = $data->asArray();
		}
		return response()->json(['code'=>0, 'data'=> $data]);
	}
	
	public function newticket()
	{
		$userId = session()->get('userinfo', 'userId');			
		$subject= request()->input('subject');
		$type= request()->input('type');
		$content = request()->input('content');
		$file= request()->input('file');
					
		$user = Database::table('user_ticket')->create();
		$user->subject = $subject;
		$user->type= $type;
		$user->user_id= $userId;
		$user->content= $content;
		$user->file= $file;	
		$user->state = 1;
		$user->created_at = date('Y-m-d H:i:s');
		if(!$user->save()) {
			throw new AppAPIException('operation_failed');
		}
		response()->json(['code' => 0]);
	}
	
	public function getuserinfo()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');	
		if( ! $data = Database::table('user')->where('id', $userId)->findOne()) {
			throw new AppAPIException('failed');
		}
		return response()->json(['code'=>0, 'data'=> $data->asArray()]);
	}
	
	public function modifyuserinfo()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');		
		$user_name= request()->input('user_name');
		$phone_number= request()->input('phone_number');
		
		$data = Database::table('user')->where('id', $userId)->findOne();
		$data->user_name = $user_name;
		$data->phone_number = $phone_number;
		if(!$data->save()) {
			throw new AppAPIException('operation_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	public function changeuseremail()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');
		$email= request()->input('email');
		$data = Database::table('user')->where('id', $userId)->findOne();
		$data->email= $email;
		if(!$data->save()) {
			throw new AppAPIException('operation_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	public function changepassword()
	{
		$request = request();
		$userId = session()->get('userinfo', 'userId');
		$new_pass= request()->input('new_pass');
		$current_pass= request()->input('current_pass');
		$data = Database::table('user')->where('id', $userId)->findOne();		
		if($data->pass != md5($current_pass)) {
			return response()->json(['code'=>1]);
		}		
		$data->pass= md5($new_pass);
		$data->last_modify_at =date('Y-m-d H:i:s');
		if(!$data->save()) {
			throw new AppAPIException('operation_failed');
		}
		return response()->json(['code'=>0]);
	}
	
	//get
	public function info()
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
	
}