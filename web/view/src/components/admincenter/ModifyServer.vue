<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">       	       		
             <li v-if="this.id == null">新增服务器</li>
            <li v-else>修改服务器</li>           
        </ol> 
         <div style="float:left;clear:both;margin-bottom:30px;">                	
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                   	  </div>
        
           <div style="margin:0 auto;margin-top:50px;width:500px;">
             	
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写服务器信息
                        </div>
                        
                        <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.name" type="text" class="form-control input"  placeholder="服务器名称">
                            </div>
                        </div>                                           
                      
                        <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.server" type="text" class="form-control input"  placeholder="服务器地址">
                            </div>
                        </div>
                        
                         <div class="form-group" style="margin-top:15px;">                                                                              
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                状态
                                <select v-model="edit.state" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
                                	<option value="1">已启用</option>
                                	<option value="0">未启用</option>
                                </select>
                            
                            </div>
                        </div>                        

                       <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.max_bandwidth_mo" type="number" class="form-control input"  placeholder="每月最大流量(GB)">
                            </div>
                        </div>
                        
                         <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.used_bandwidth_mo" type="number" class="form-control input" readonly  placeholder="当月已使用流量(GB)">
                            </div>
                        </div>
                        
                                                                        
                        <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.ssh_port" type="text" class="form-control input"  placeholder="SSH端口">
                            </div>
                        </div>
                          <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.ssh_user" type="text" class="form-control input"  placeholder="SSH用户名">
                            </div>
                        </div>
                        
                          <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.ssh_password" type="text" class="form-control input"  placeholder="SSH密码">
                            </div>
                        </div>
                                                                   
                        
                        <div class="form-group" style="margin-top:15px;">                                                                              
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                是否显示
                                <select v-model="edit.is_show" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
                                	<option value="1">是</option>
                                	<option value="0">否</option>
                                </select>
                            
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.sort" type="number" class="form-control input"  placeholder="显示排序">
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
</style>
<script>
export default{
    data () {
        return {        	
        	gdata: gdata,
        	id:null,
        	edit:{
        		name:null,
        		server:null,
        		state:null,
        		sort:null,
        		max_bandwidth_mo:null,
        		used_bandwidth_mo:null,
        		ssh_port:null,
        		ssh_user:null,
        		ssh_password:null,
        		is_show:null
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
    		if( ! this.edit.name) {
    			swal('服务器名称不能为空');
    			return
    		}
    		if( ! this.edit.server) {
    			swal('服务器地址不能为空');
    			return
    		}
    		if( ! this.edit.state) {
    			swal('没有选择服务器状态');
    			return
    		}
    	
    		if( ! this.edit.max_bandwidth_mo) {
    			swal('每月最大流量不能为空');
    			return
    		}
    		if( ! this.edit.is_show) {
    			swal('是否显示不能为空');
    			return
    		}
    		if( ! this.edit.ssh_port) {
    			swal('SSH端口不能为空');
    			return
    		}
    		if( ! this.edit.ssh_user) {
    			swal('SSH用户名不能为空');
    			return
    		}
    		if( ! this.edit.ssh_password) {
    			swal('SSH密码不能为空');
    			return
    		}
        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newserver'
        	} else {
        		api = '/admin/modifyserver/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {                    
                if (response.data.code == 0) {
                	this.$router.push({name: 'servermanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/server/'+this.id).then(function (response) {                    
                     if (response.data.code == 0) {                    
                    	 this.edit = response.data.data            	 
                     }
                 })
        	}        	
        }
    }
}
</script>
