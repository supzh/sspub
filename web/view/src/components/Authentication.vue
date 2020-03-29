<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
           	<li>我的账户</li>
           	<li>账户安全</li>            
        </ol>                                                          
             <div style="margin:0 auto;width:500px;">
             	
             	<form class="form-horizontal" role="form">
							<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              修改密码
                            </div>		 
                        <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="current_pass" type="password" class="form-control input" placeholder="现有密码">
                            </div>
                        </div>
                         <div class="form-group" >
                            <div class="col-sm-12">
                                <input v-model="new_pass" type="password" class="form-control input" placeholder="新密码">
                            </div>
                        </div>
                         <div class="form-group" >
                            <div class="col-sm-12">
                                <input v-model="new_pass_again" type="password" class="form-control input" placeholder="确认新密码">
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button @click="submit" type="button" style="width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认修改</button>
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
                current_pass:null,
                new_pass:null,
                new_pass_again:null
            }
        },
        created(){
        	
        },
        methods: {
        	submit(){
        		if(!this.current_pass){
        			swal('请您输入现有密码')
        			return
        		}
        		if(!this.new_pass){
        			swal('请您输入新密码')
        			return
        		}	
        		if(!this.new_pass_again){
        			swal('请您输入确认新密码')
        			return
        		}
        		if(this.new_pass != this.new_pass_again) {
        			swal('您输入的新密码与确认新密码不一致')
        			return
        		}
        		var params = {
        				new_pass : this.new_pass,
        				current_pass : this.current_pass
        		}
                this.$http.post('/changepassword' , params).then(function (response) {                    
                    if (response.data.code == 0) {
                    	swal('修改成功！')
                    } else if(response.data.code == 1) {
                    	swal('修改失败，您输入的现有密码不正确')
                    }
                })
        	}
        }
    }
</script>
