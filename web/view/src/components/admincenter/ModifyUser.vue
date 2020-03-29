<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">
               <li v-if="this.id == null">新增用户</li>
            <li v-else>修改用户</li>
        </ol>
         <div style="float:left;clear:both;margin-bottom:30px;">
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                   	  </div>

           <div style="margin:0 auto;margin-top:50px;width:500px;">

             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写用户信息
                        </div>

                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input v-model="edit.email" type="text" class="form-control input"  placeholder="用户邮箱">
                            </div>
                        </div>


                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input v-model="edit.pass" type="text" class="form-control input"  placeholder="登录密码">
                            </div>
                        </div>


                        <div class="form-group" style="margin-top:15px;">
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                用户类型
                                <select v-model="edit.type" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >
                                	<option value="2">管理员</option>
                                	<option value="1">用户</option>
                                </select>
                            </div>
                        </div>
                         <div class="form-group" style="margin-top:15px;">
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                允许登录
                                <select v-model="edit.disabled" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >
                                	<option value="0">是</option>
                                	<option value="1">否</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <button @click="submit" type="button" style="margin-top:15px;width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认</button>
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
    .gift-list {}
    .gift {float:left;}
    .confirm {}
</style>
<script>
export default{
    data () {
        return {
        	gdata: gdata,
        	id:null,
            edit:{
            	type:null,
            	email:null,
            	pass:null,
            	disabled:null
            }
        }
    },
    created(){
    	if(this.$route.params.id){
    		this.id = this.$route.params.id
    		this.getdata()
    	}
    },
    methods: {
    	goback(){
    		this.$router.go(-1)
    	},
        submit(){
    		if( ! this.edit.email) {
    			swal('登录邮箱不能为空');
    			return
    		}
    		if( (! this.edit.pass) && this.id == null ) {
    			swal('登录密码不能为空');
    			return
    		}
    		if( ! this.edit.type) {
    			swal('请您选择用户类型');
    			return
    		}
    		if( ! this.edit.disabled) {
    			swal('请您选择是否允许登录');
    			return
    		}
        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newuser'
        	} else {
        		api = '/admin/modifyuser/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {
                if (response.data.code == 0) {
                	this.$router.push({name: 'usermanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/user/'+this.id).then(function (response) {
                     if (response.data.code == 0) {
                    	 this.edit = response.data.data
                     }
                 })
        	}
        }
    }
}
</script>
