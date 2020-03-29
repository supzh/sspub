<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>管理中心</li>
        </ol>
        <div style="float:left;margin-bottom:60px;">
	        <ol class="submenu">
	        	<li :class="{active:this.$route.name=='admincenter'}" @click="routerpush({name:'admincenter'})">管理中心</li>
	            <li :class="{active:this.$route.name=='giftmanage'}" @click="routerpush({name:'giftmanage'})">礼品管理</li>
	            <li :class="{active:this.$route.name=='supportmanage'}" @click="routerpush({name:'supportmanage'})">知识库管理</li>
	            <li :class="{active:this.$route.name=='ticketsmanage'}" @click="routerpush({name:'ticketsmanage'})">提问管理</li>
	            <li :class="{active:this.$route.name=='servermanage'}" @click="routerpush({name:'servermanage'})">服务器管理</li>
	            <li :class="{active:this.$route.name=='usermanage'}" @click="routerpush({name:'usermanage'})">用户管理</li>
	            <li :class="{active:this.$route.name=='friendcodemanage'}" @click="routerpush({name:'friendcodemanage'})">邀请码管理</li>
	            <li :class="{active:this.$route.name=='paymanage'}" @click="routerpush({name:'paymanage'})">支付管理</li>
	            <li :class="{active:this.$route.name=='sitemanage'}" @click="routerpush({name:'sitemanage'})">站点配置</li>
	        </ol>
	        </ol>
        </div>

          <div style="margin:0 auto;width:500px;">

             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              修改站点信息
                        </div>
                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input v-model="edit.sitename" type="text" class="form-control input"  placeholder="网站名称">
                            </div>
                        </div>



                         <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px;">
                            	网站Logo图片 ( 文件大小不能超过 5MB )
                                <input type="file" @change="sitelogofileUpload"  id="sitelogo-file-input" style="border:none;height:40px;font-size:12px;color:#666;line-height:20px;" class="form-control input"  placeholder="附件">
                          	<img v-if="edit.sitelogo" :src="edit.sitelogo" style="max-width:450px;">
                            </div>
                        </div>
                          <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px;">
                            	用户中心Logo图片 ( 文件大小不能超过 5MB )
                                <input type="file" @change="usercenterlogofileUpload"  id="usercenterlogo-file-input" style="border:none;height:40px;font-size:12px;color:#666;line-height:20px;" class="form-control input"  placeholder="附件">
                          	<img v-if="edit.usercenterlogo" :src="edit.usercenterlogo" style="max-width:450px;">
                            </div>
                        </div>
                         <div class="form-group" >

                            <div class="col-sm-12">
                                <textarea v-model="edit.analysis_code" class="form-control input" style="min-height:200px;"  placeholder="统计代码"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
							设置站点通知
                            </div>
                         <div class="form-group" >

                            <div class="col-sm-12">
                                <textarea  v-model="edit.homepage_msg"  class="form-control input" style="min-height:200px;"  placeholder="首页通知"></textarea>
                            </div>
                        </div>

                        <div class="form-group" >
                            <div class="col-sm-12">
                                <textarea   v-model="edit.usercenter_msg"  class="form-control input" style="min-height:200px;"  placeholder="用户中心通知"></textarea>
                            </div>
                        </div>

                        	<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
							注册管理
                            </div>
                        <div class="form-group" style="margin-top:15px;">
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                关闭注册
                                <select v-model="edit.is_open_register" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >
                                	<option value="1">否</option>
                                	<option value="0">是</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button @click="submit" type="button" style="width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认更新</button>
							</div>
                        </div>

                    </form>

             </div>

    </div>
</template>
<style>
    body{
        //background-color:#ff0000;
    }
    .submenu {list-style:none;margin:0;padding:0;margin-top:-20px;}
    .submenu li.active {color:#1e88e5;}
    .submenu li {float:left;height:20px;line-height:20px;font-size:18px;padding-right:25px;color:#999;padding-top:20px;cursor:pointer;}

    .btn-p {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#1e88e5;border:1px solid #1e88e5;}
    .btn-p:hover {background:#1e88e5;border:1px solid #1e88e5;}
    .btn-d {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#fff;border:1px solid #ddd;color:#333;}
    .btn-d:hover {background:#fff;border:1px solid #ccc;}
     .btn-pc {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#d9534f;border:1px solid #d9534f;}
     .btn-pc:hover{background:#d9534f;border:1px solid #d9534f;}
    .big_inf_box {float:left;border-radius:3px;border:1px solid #e6e9eb;height:200px;width:310px;margin-right:50px;cursor:pointer;}
    .big_inf_box:hover {border:1px solid #1e88e5}
   .big_inf_box .ltitle {font-size:26px;height:20px;line-height:20px;margin-top:70px;color:#1e88e5;text-align:center;}
   .big_inf_box .ltext {line-height:30px;height:30px;margin-top:10px;font-size:15px;color:#666;text-align:center;}
   .big_inf_box .ltext .lweight {font-size:25px;color:#333;}
   .detail-dl {width:280px;}
    .detail-dl dd {float:left;font-weight:bold;clear:left; width:80px;line-height:32px;height:32px!important;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
    .detail-dl dt {float:left;font-weight:normal;width:200px;line-height:32px;height:32px;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
</style>
<script>
import Storage from './../../storage.js'
    export default{
        data () {
            return {
                gdata:gdata,
               	edit:{
               		sitename:null,
               		sitelogo:null,
               		analysis_code:null,
               		homepage_msg:null,
               		usercenter_msg:null,
               		usercenterlogo:null,
               		is_open_register:null,
               	}
            }
        },
        created(){
        	 this.getdata()
        },
        methods: {
        	sitelogofileUpload() {
	            var vm = this
	            var file = document.querySelector('#sitelogo-file-input')
	            file = file.files[0]
	            var url =  gdata.serverhost + '/api/uploadfile?sessid='+Storage.get('sessid');
	            var xhr = new XMLHttpRequest();
	            var fd = new FormData();
	            xhr.open("POST", url, true);
	            xhr.onreadystatechange = function() {
	                if (xhr.readyState == 4 && xhr.status == 200) {
	                    // Every thing ok, file uploaded
	                    var res = JSON.parse(xhr.responseText); // handle response.
	                    if(res.code != 0) {
	                         swal({title:'消息',confirmButtonText:'关闭',text:res.msg})
	                    } else {
	                        vm.edit.sitelogo = res.data.url
	                    }
	                }
	            };
	            fd.append("file", file);
	            fd.append("sessid", Storage.get('sessid'));
	            xhr.send(fd);
	        },
	        usercenterlogofileUpload() {
	            var vm = this
	            var file = document.querySelector('#usercenterlogo-file-input')
	            file = file.files[0]
	            var url =  gdata.serverhost + '/api/uploadfile?sessid='+Storage.get('sessid');
	            var xhr = new XMLHttpRequest();
	            var fd = new FormData();
	            xhr.open("POST", url, true);
	            xhr.onreadystatechange = function() {
	                if (xhr.readyState == 4 && xhr.status == 200) {
	                    // Every thing ok, file uploaded
	                    var res = JSON.parse(xhr.responseText); // handle response.
	                    if(res.code != 0) {
	                         swal({title:'消息',confirmButtonText:'关闭',text:res.msg})
	                    } else {
	                        vm.edit.usercenterlogo = res.data.url
	                    }
	                }
	            };
	            fd.append("file", file);
	            fd.append("sessid", Storage.get('sessid'));
	            xhr.send(fd);
	        },
        	routerpush(pushroutename){
        		this.$router.push(pushroutename)
        	},
            submit () {
        		if( ! this.edit.sitename) {
        			swal('站点不能为空');
        			return
        		}
        		if( ! this.edit.is_open_register) {
        			swal('请您选择是否关闭注册');
        			return
        		}
        		var params = this.edit
                this.$http.post('/admin/updatesiteinfo' , params).then(function (response) {
                    if (response.data.code == 0) {
                    	swal('更新成功！')
                    }
                })
            },
            getdata(){
           		 this.$http.get('/admin/siteinfo').then(function (response) {
                        if (response.data.code == 0) {
                       		 this.edit = response.data.data
                        }
                  })
            }

        }
    }
</script>
