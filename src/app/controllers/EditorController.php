<?php 

namespace App\Controllers;

use \UeditorController;

class EditorController
{
	public function editor()
	{
		UeditorController::run();
	}
	
	public function uploadfile()
	{
		if(!$attachment= request()->file('file')) {
			return response()->json(['code'=>1]);
		}
		$savepath = 'storage/uploadfiles/'.date('YmdHis').'_'.$attachment['name'];
		$attachmentfilepath = appPath($savepath);			
		if(!move_uploaded_file($attachment['tmp_name'], $attachmentfilepath)){
			return response()->json(['code'=>2]);
		}
		
		return response()->json(['code'=>0, 'data'=>['url'=>$savepath]]);
	}
}