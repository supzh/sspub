<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
           	<li>我的账户</li>
           	<li>个人信息</li>
        </ol>
             <div style="margin:0 auto;width:500px;">

             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              修改个人信息
                        </div>
                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input v-model="edit.user_name" type="text" class="form-control input" :value="gdata.userName"  placeholder="昵称">
                            </div>
                        </div>
                        <div class="form-group" style="">
                            <div class="col-sm-12" style="text-align:right;height:70px;">
                                <input type="text" class="form-control input" :value="gdata.email" disabled  placeholder="邮箱">
                                <!--<p style="color:#0084df;font-size:12px;padding:2px;cursor:pointer;">修改邮箱地址</p>-->
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:-10px;">

                            <div class="col-sm-12">
                                <input v-model="edit.phone_number" type="text" class="form-control input"  placeholder="电话号码">
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
                edit:{
                	user_name: null,
                	email: null,
                	phone_number:null
                }
            }
        },
        created(){
        	this.getdata()
        },
        methods: {
            submit () {
        		if( ! this.edit.user_name) {
        			swal('昵称不能为空');
        			return
        		}

        		var params = this.edit
                this.$http.post('/modifyuserinfo', params).then(function (response) {
                    if (response.data.code == 0) {
                    	swal('更新成功！')
                    }
                })
            },
            getdata(){
           		 this.$http.get('/userinfo').then(function (response) {
                        if (response.data.code == 0) {
                       		 this.edit = response.data.data
                        }
                  })
            }
        }
    }
</script>
