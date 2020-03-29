<template>
    <div>
        <div class="modal fade" id="forgotpwdModal" tabindex="-1" role="dialog" aria-labelledby="forgotpwdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="forgotpwdModalLabel">恢复密码</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control input" id="femail" v-model="fEmail" placeholder="请输入您的注册邮箱">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" style="height:50px;" id="fsendcode" @click="fSendCode" type="button">发送验证邮件</button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">验证码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input" v-model="fCode" placeholder="请输入邮件中的验证码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">新密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input" v-model="fNewPassword" placeholder="新密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">再次输入新密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input" v-model="fNewPasswordAgain" placeholder="再次输入新密码">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                     	<button type="button" class="btn-p btn btn-primary" @click="fSubmit">确认</button>
                        <button type="button" class="btn-d btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

		 <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="editModalLabel">注册</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">昵称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input" v-model="edit.userName" placeholder="昵称">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input" v-model="edit.email" placeholder="邮箱">
                                </div>
                            </div>
                              <div class="form-group">
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input" v-model="edit.password" placeholder="密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">重复密码</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control input" v-model="edit.passwordAgain" placeholder="重复密码">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button"class="btn-p btn btn-primary" @click="submitData">确认</button>
                        <button type="button" class="btn-d btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

      <div class="container" style="width:450px;">

            <div class="panel panel-default login-box" >

                <div class="panel-body">
                    <div class="row">

                        <div class="col-sm-12"  style="margin-bottom:50px;">

                            <h3 style="text-align:center;color:#1e88e5;font-family:'avenir';font-size:36px">

                             <img src="img/logo.png" style="width:196px;height:49px;margin:10px;" >
                            </h3>
                        </div>
                    </div>
                    <form class="form-horizontal" role="form">

                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input type="text" v-focus @keyup.enter="login" class="form-control input" v-model="email" placeholder="邮箱">
                            </div>
                        </div>
                        <div class="form-group" style="">

                            <div class="col-sm-12">
                                <input type="password" @keyup.enter="login"  class="form-control input" v-model="password" placeholder="密码">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <button type="button" style="width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn btn-primary" @click="login">登 录</button>
							</div>
                        </div>

                         <div class="form-group" style="padding-bottom:20px;">
                            <div class="col-sm-12" style="text-align:center;">

								<a href="#" style="border:none;color:#0084df;float:left;" data-target="#editModal" data-toggle="modal">注册</a>

                                <a href="#" style="border:none;color:#ccc;float:right;" data-target="#forgotpwdModal" data-toggle="modal">忘记密码?</a>
                            </div>
                        </div>


                    </form>
                </div>
            </div></div></div>


</template>
<style>
    body{
        background-color:#fff;;
    }
    .input {border-radius:3px;line-height:50px;height:50px;border:1px solid #ebebeb;color:#333;box-shadow:none;padding-left:15px;}
    .control-label {color:#666;font-weight:normal}
    .login-user-type-list {list-style:none;margin-left:0;padding:0;}
    .login-user-type-list li {cursor:pointer;float:left;margin-right:10px;font-size:16px;color:#aaa;padding:2px 5px;margin:0;}
    .login-user-type-list li.active {color:#666;background:#fff;border-bottom:2px solid #ddd;}
    .login-box {margin-top:15px;}
</style>
<script>
	import Storage from './../storage.js'
    export default{
        data(){
            return {
                gdata:gdata,
                email:null,
                password:null,
                fEmail:null,
                fCode:null,
                fNewPassword:null,
                fNewPasswordAgain:null,
                edit: {
                    userName:null,
 					email:null,
 					password:null,
 					passwordAgain:null
                }
            }
        },
        created(){
            if(gdata.isLogin) {
                this.$router.push({name:'home'})
            }
        },
        methods:{
        	submitData() {
        	   if(this.edit.password !=this.edit.passwordAgain) {
                    swal({title:'消息',confirmButtonText:'关闭',text:'两次输入的密码不一致'})
                    return
                }
                var params =  {
                        'username': this.edit.userName,
                        'email': this.edit.email,
                        'password': this.edit.password,
                }
                this.$http.post('/register', params).then((response)=> {
                    if(response.data.code == 0) {
                    	this.email = this.edit.email
                    	this.password = this.edit.password;
                       	this.login();
                        $('#editModal').modal('hide')
                    }
                })
            },
            login(){
                this.$alert.hide();
                this.$http.post('/login',{
                    email:this.email,
                    password:this.password,
                }).then((response) => {
                    if(response.data.code == 0) {
                        gdata.isLogin = true
                        gdata.userId = response.data.userId
                        gdata.userName = response.data.userName
                        gdata.email = response.data.email
                        gdata.type = response.data.type
                        this.$router.push({name:'home'})
                    }
                })
            },
            fSendCode(){
                if(!this.fEmail) {
                    swal({title:'消息',confirmButtonText:'关闭',text:'请您输入注册邮箱'})
                    return;
                }
                var params = {
                	email:this.fEmail
                }

                this.$http.post('/sendresetpwdverifymail', params).then((response)=>{
                    if(response.data.code == 0) {
                    	$("#femail").attr('readonly','readonly')
                    	$("#fsendcode").html('邮件已发送')
                        $("#fsendcode").attr('disabled','disabled')
                        $("#fsendcode").attr('disabled','disabled')
                    } else {
                    	swal(response.data.msg)
                    }
                })

            },
            fSubmit(){
                if(!this.fEmail) {
                    swal({title:'消息',confirmButtonText:'关闭',text:'请您输入注册邮箱'})
                    return;
                }
                if(!this.fCode) {
                     swal({title:'消息',confirmButtonText:'关闭',text:'请您输入验证码'})
                    return;
                }
                if(!this.fNewPassword) {
                     swal({title:'消息',confirmButtonText:'关闭',text:'请您输入新密码'})
                    return;
                }
                if(!this.fNewPasswordAgain) {
                    swal({title:'消息',confirmButtonText:'关闭',text:'请您再次输入新密码'})
                    return;
                }
                if(this.fNewPassword !=this.fNewPasswordAgain) {
                     swal({title:'消息',confirmButtonText:'关闭',text:'两次输入的新密码不一致'})
                    return;
                }
                var params = {
                    	email:this.fEmail,
                    	code:this.fCode,
                    	new_pass:this.fNewPassword,
                 }

                 this.$http.post('/confirmresetpass', params).then((response)=>{
                     if(response.data.code == 0) {
                     	$("#femail").removeAttr('readonly')
                     	$("#fsendcode").html('发送验证邮件')
                        $("#fsendcode").removeAttr('disabled');
                        $("#fsendcode").removeAttr('disabled');
                    	swal({title:'消息',confirmButtonText:'关闭',text:response.data.msg})
                     } else {
                     	swal({title:'消息',confirmButtonText:'关闭',text:response.data.msg})
                     }
                 })

            }
        }
    }
</script>
