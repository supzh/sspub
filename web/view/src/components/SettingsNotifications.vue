<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
           	<li>我的账户</li>
           	<li>通知设置</li>            
        </ol>                                                          
             <div style="margin:0 auto;width:500px;">             	
             	<form class="form-horizontal" role="form">
							<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
							流量使用通知
                            </div>
                               
                             <div class="form-group" style="margin-top:15px;">                                                                              
	                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
	                                每月发送流量使用邮件通知
	                                <select v-model="send_traffic_log_email_notify" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
	                                	<option value="1">开</option>        
	                                	<option value="0">关</option>                                	                        	
	                                </select>                            
	                            </div>
	                        </div>     
                                                  
                        	<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">							
							订阅时长通知
                            </div>
                            
                             <div class="form-group" style="margin-top:15px;">                                                                              
	                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
	                                订阅即将过期时发送邮件通知
	                                <select v-model="send_sub_expire_email_notify" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
	                                	<option value="1">开</option>
	                                	<option value="0">关</option>                                	                        	
	                                </select>                            
	                            </div>
	                        </div>
                            
	                                                                 
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button @click="submit" type="button" style="width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认</button>
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
    .btn-p {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#1e88e5;border:1px solid #1e88e5;}
    .btn-p:hover {background:#1e88e5;border:1px solid #1e88e5;}
    .btn-d {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#fff;border:1px solid #ddd;color:#333;}
    .btn-d:hover {background:#fff;border:1px solid #ccc;}
     .btn-pc {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#d9534f;border:1px solid #d9534f;}
     .btn-pc:hover{background:#d9534f;border:1px solid #d9534f;}
    .inf_box {float:left;border-radius:3px;border:1px solid #e6e9eb;height:89px;width:350px;padding-left:20px;margin-right:30px;cursor:pointer;}
    .inf_box:hover {border:1px solid #1e88e5}
   .inf_box .ltitle {font-size:13px;height:20px;line-height:20px;margin-top:20px;color:#999;}
   .inf_box .ltext {line-height:30px;height:30px;margin-top:-10px;font-size:18px;color:#999;}
   .inf_box .ltext .lweight {font-size:25px;color:#333;}
   .detail-dl {width:280px;}
    .detail-dl dd {float:left;font-weight:bold;clear:left; width:80px;line-height:32px;height:32px!important;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
    .detail-dl dt {float:left;font-weight:normal;width:200px;line-height:32px;height:32px;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
                send_traffic_log_email_notify:null,
                send_sub_expire_email_notify:null,                
            }
        },
        created(){
        	this.getdata()
        },
        methods: {
        	submit(){
        		if(!this.send_traffic_log_email_notify){
        			swal('请您选择是否开启每月发送流量使用邮件通知')
        			return
        		}
        		if(!this.send_sub_expire_email_notify){
        			swal('请您选择是否开启订阅即将过期时发送邮件通知')
        			return
        		}
        		var params = {
        				send_traffic_log_email_notify : this.send_traffic_log_email_notify,
        				send_sub_expire_email_notify : this.send_sub_expire_email_notify
        		}
                this.$http.post('/changenotify' , params).then(function (response) {                    
                    if (response.data.code == 0) {
                    	swal('修改成功！')
                    } else if(response.data.code == 1) {
                    	swal('修改失败')
                    }
                })
        	},
            getdata(){
          		 this.$http.get('/notifysetting').then(function (response) {                    
                       if (response.data.code == 0) {
                      		this.send_traffic_log_email_notify = response.data.data.send_traffic_log_email_notify
                      		this.send_sub_expire_email_notify = response.data.data.send_sub_expire_email_notify
                       }
                 })            	
           }
        }
    }
</script>
