<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">       	       		
            <li v-if="this.id == null">新增礼品</li>
            <li v-else>修改礼品</li>
        </ol>
         <div style="float:left;clear:both;margin-bottom:30px;">                	
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                   	  </div>
        
           <div style="margin:0 auto;margin-top:50px;width:500px;">
             	
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写礼品信息
                        </div>
                        
                        <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.name" type="text" class="form-control input"  placeholder="礼品名称">
                            </div>
                        </div>
                                                                                             
                         <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.mo_bandwidth" type="number" class="form-control input"  placeholder="每月流量(GB)">
                            </div>
                        </div>
                                                
                         <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.max_instance" type="number" class="form-control input"  placeholder="最多实例">
                            </div>
                        </div>
                        
                          <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.mo_price" type="number" class="form-control input"  placeholder="每月价格(￥)">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.discount"  type="number" class="form-control input"  placeholder="折扣比例">
                            </div>
                        </div>
                        
                          <div class="form-group" style="margin-top:15px;">                       
                            <div class="col-sm-12">
                                <input v-model="edit.user_sub_max_mo" type="number" class="form-control input"  placeholder="每用户最大订阅时长(月)">
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
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                是否允许使用邀请码
                                <select v-model="edit.allow_use_friendcode" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
                                	<option value="1">是</option>        
                                	<option value="0">否</option>                                	                        	
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

</style>
<script>
export default{
    data () {
        return {        	
        	gdata: gdata,
        	id: null,
            edit: {
            	name: null,
            	mo_bandwidth: null,
            	max_instance: null,
            	mo_price: null,
            	discount: null,
            	user_sub_max_mo: null,
            	is_show: null,
            	allow_use_friendcode: null
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
    			swal('礼品名称不能为空');
    			return
    		}
    		if(this.edit.name.length >12 ) {
    			swal('礼品名称不能超过12个字')
    			return
    		}
    		if( ! this.edit.mo_bandwidth) {
    			swal('每月流量不能为空');
    			return
    		}
    		if( ! this.edit.max_instance) {
    			swal('最多实例不能为空');
    			return
    		}
    		if( ! this.edit.mo_price) {
    			swal('每月价格不能为空');
    			return
    		}
    		if( ! this.edit.user_sub_max_mo) {
    			swal('每用户最大订阅时长不能为空');
    			return
    		}
    		if( ! this.edit.is_show) {
    			swal('是否显示不能为空');
    			return
    		}
    		if( ! this.edit.allow_use_friendcode) {
    			swal('是否允许使用邀请码不能为空');
    			return
    		}
        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newgift'
        	} else {
        		api = '/admin/modifygift/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {                    
                if (response.data.code == 0) {
                	this.$router.push({name: 'giftmanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/gift/'+this.id).then(function (response) {                    
                     if (response.data.code == 0) {
                    	 this.edit =  {
                         	name: response.data.data.name,
                         	mo_bandwidth: response.data.data.mo_bandwidth,
                         	max_instance: response.data.data.max_instance,
                         	mo_price: response.data.data.mo_price,
                         	discount: response.data.data.discount,
                         	user_sub_max_mo:  response.data.data.user_sub_max_mo,
                         	is_show: response.data.data.is_show,
                         	allow_use_friendcode: response.data.data.allow_use_friendcode
                         }                    	 
                     }
                 })
        	}        	
        }
    }
}
</script>
