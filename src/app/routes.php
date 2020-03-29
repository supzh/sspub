<?php

use Application\Route;
use Application\Database;

Route::request('/', function(){
	redirect('/view/index.html');
});


Route::post('/api/sendresetpwdverifymail', 'App\Controllers\APIController@sendresetpwdverifymail');
Route::post('/api/confirmresetpass', 'App\Controllers\APIController@confirmresetpass');
Route::get('/api/loginstate', 'App\Controllers\APIController@loginstate');
Route::post('/api/login', 'App\Controllers\APIController@login');
Route::post('/api/register', 'App\Controllers\APIController@register');
Route::get('/api/logout', 'App\Controllers\APIController@logout');
Route::get('/api/giftlist', 'App\Controllers\APIController@giftlist')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/newsub', 'App\Controllers\APIController@newsub')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/mysub', 'App\Controllers\APIController@mysub')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/subdetail/(id)', 'App\Controllers\APIController@subdetail')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/sub/(id)/cancel', 'App\Controllers\APIController@subcancel')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/sub/(id)/delete', 'App\Controllers\APIController@subdelete')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/sub/(id)/resetpassword', 'App\Controllers\APIController@resetsubpassword')->middleware('App\Controllers\APIController@authorize');

Route::post('/api/sub/usefriendcode/(id)', 'App\Controllers\APIController@usefriendcode')->middleware('App\Controllers\APIController@authorize');
Route::get('/userpay', 'App\Controllers\UserPayController@pay');

Route::any('/api/editor', 'App\Controllers\EditorController@editor');
Route::post('/api/uploadfile', 'App\Controllers\EditorController@uploadfile');
Route::post('/api/search', 'App\Controllers\SearchController@search');
Route::get('/api/sub/(id)/getclientdownload', 'App\Controllers\APIController@getclientdownload')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/sub/(id)/getmacclientdownload', 'App\Controllers\APIController@getmacclientdownload')->middleware('App\Controllers\APIController@authorize');

Route::get('/api/trafficlog/(id)', 'App\Controllers\APIController@trafficlog')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/servers', 'App\Controllers\APIController@servers')->middleware('App\Controllers\APIController@authorize');

Route::get('/api/bill', 'App\Controllers\APIController@bill')->middleware('App\Controllers\APIController@authorize');

Route::get('/api/category', 'App\Controllers\APIController@category');
Route::get('/api/popularquestion', 'App\Controllers\APIController@popularquestion');
Route::get('/api/guidedocs', 'App\Controllers\APIController@guidedocs');
Route::get('/api/docsbycategory', 'App\Controllers\APIController@docsbycategory');
Route::get('/api/doc', 'App\Controllers\APIController@docbytitle');



Route::get('/api/tickets', 'App\Controllers\APIController@tickets')->middleware('App\Controllers\APIController@authorize');
Route::get('/api/ticket/(id)', 'App\Controllers\APIController@ticket')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/newticket', 'App\Controllers\APIController@newticket')->middleware('App\Controllers\APIController@authorize');

Route::get('/api/userinfo', 'App\Controllers\APIController@getuserinfo')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/modifyuserinfo', 'App\Controllers\APIController@modifyuserinfo')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/changeuseremail', 'App\Controllers\APIController@changeuseremail')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/changepassword', 'App\Controllers\APIController@changepassword')->middleware('App\Controllers\APIController@authorize');

Route::get('/api/notifysetting', 'App\Controllers\APIController@notifysetting')->middleware('App\Controllers\APIController@authorize');
Route::post('/api/changenotify', 'App\Controllers\APIController@changenotify')->middleware('App\Controllers\APIController@authorize');

//admin center
Route::request('/api/admin/newgift', 'App\Controllers\AdminAPIController@newgift')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/modifygift/(id)', 'App\Controllers\AdminAPIController@modifygift')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/deletegift/(id)', 'App\Controllers\AdminAPIController@deletegift')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/gift/(id)', 'App\Controllers\AdminAPIController@getgift')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/gifts', 'App\Controllers\AdminAPIController@gifts')->middleware('App\Controllers\AdminAPIController@authorize');

Route::get('/api/admin/category', 'App\Controllers\AdminAPIController@admingetcategory')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/docs', 'App\Controllers\AdminAPIController@admingetdocs')->middleware('App\Controllers\AdminAPIController@authorize');

Route::get('/api/admin/category/(id)', 'App\Controllers\AdminAPIController@admingetcategorybyid')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/doc/(id)', 'App\Controllers\AdminAPIController@admingetdocbyid')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/deletecategory/(id)', 'App\Controllers\AdminAPIController@admindeletecategorybyid')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/deletedoc/(id)', 'App\Controllers\AdminAPIController@admindeletedocbyid')->middleware('App\Controllers\AdminAPIController@authorize');

Route::post('/api/admin/newcategory', 'App\Controllers\AdminAPIController@adminnewcategory')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/newdoc', 'App\Controllers\AdminAPIController@adminnewdoc')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/modifycategory/(id)', 'App\Controllers\AdminAPIController@adminmodifycategory')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/modifydoc/(id)', 'App\Controllers\AdminAPIController@adminmodifydoc')->middleware('App\Controllers\AdminAPIController@authorize');

Route::get('/api/admin/tickets', 'App\Controllers\AdminAPIController@admingettickets')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/ticket/(id)', 'App\Controllers\AdminAPIController@admingetticket')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/replyticket/(id)', 'App\Controllers\AdminAPIController@adminreplyticket')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/newserver', 'App\Controllers\AdminAPIController@newserver')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/modifyserver/(id)', 'App\Controllers\AdminAPIController@modifyserver')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/deleteserver/(id)', 'App\Controllers\AdminAPIController@deleteserver')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/servers', 'App\Controllers\AdminAPIController@servers')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/server/(id)', 'App\Controllers\AdminAPIController@server')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/newuser', 'App\Controllers\AdminAPIController@newuser')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/users', 'App\Controllers\AdminAPIController@users')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/modifyuser/(id)', 'App\Controllers\AdminAPIController@modifyuser')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/deleteuser/(id)', 'App\Controllers\AdminAPIController@deleteuser')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/user/(id)', 'App\Controllers\AdminAPIController@user')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/newfriendcode', 'App\Controllers\AdminAPIController@newfriendcode')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/friendcodes', 'App\Controllers\AdminAPIController@friendcodes')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/friendcode/(id)', 'App\Controllers\AdminAPIController@friendcode')->middleware('App\Controllers\AdminAPIController@authorize');
Route::request('/api/admin/modifyfriendcode/(id)', 'App\Controllers\AdminAPIController@modifyfriendcode')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/friendcodeusedrecord', 'App\Controllers\AdminAPIController@friendcodeusedrecord')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/exportpayrecord', 'App\Controllers\AdminAPIController@exportpayrecord')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/payrecords', 'App\Controllers\AdminAPIController@payrecords')->middleware('App\Controllers\AdminAPIController@authorize');
Route::post('/api/admin/updatesiteinfo', 'App\Controllers\AdminAPIController@updatesiteinfo')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/siteinfo', 'App\Controllers\AdminAPIController@siteinfo')->middleware('App\Controllers\AdminAPIController@authorize');
Route::get('/api/admin/admincenterinfo', 'App\Controllers\AdminAPIController@admincenterinfo')->middleware('App\Controllers\AdminAPIController@authorize');
//Mu
Route::get('/mu/v2/users', 'App\Controllers\Mu\UserController@index')->middleware('App\Controllers\Mu\MuMiddleware@handle');
Route::post('/mu/v2/users/(id)/traffic', 'App\Controllers\Mu\UserController@addTraffic')->middleware('App\Controllers\Mu\MuMiddleware@handle');
Route::get('/mu/v2/nodes/(id)/users', 'App\Controllers\Mu\NodeController@users')->middleware('App\Controllers\Mu\MuMiddleware@handle');
Route::post('/mu/v2/nodes/(id)/online_count', 'App\Controllers\Mu\NodeController@onlineUserLog')->middleware('App\Controllers\Mu\MuMiddleware@handle');
Route::post('/mu/v2/nodes/(id)/info', 'App\Controllers\Mu\NodeController@info')->middleware('App\Controllers\Mu\MuMiddleware@handle');
Route::post('/mu/v2/nodes/(id)/traffic', 'App\Controllers\Mu\NodeController@postTraffic')->middleware('App\Controllers\Mu\MuMiddleware@handle');
 

