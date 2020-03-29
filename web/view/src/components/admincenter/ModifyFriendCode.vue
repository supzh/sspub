<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">       	       		
            <li>生成邀请码</li>              
        </ol> 
         <div style="float:left;clear:both;margin-bottom:30px;">                	
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                   	  </div>
        
           <div style="margin:0 auto;margin-top:50px;width:500px;">
             	
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写邀请码信息
                        </div>
                        
                          <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.code" type="text" class="form-control input" readonly  placeholder="邀请码密码(系统生成)">
                            </div>
                        </div>
                        
                          <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.discount" type="number" class="form-control input"  placeholder="使用邀请码订阅礼品的折扣比例(0到1之间小数)">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.limit_times" type="number" class="form-control input"  placeholder="限制使用次数">
                            </div>
                        </div>
                        
                         <div class="form-group" style="margin-top:15px;">
                       
                            <div class="col-sm-12">
                                <input v-model="edit.used_times" type="number" class="form-control input" readonly  placeholder="已使用次数">
                            </div>
                        </div>                                             
                        
                         <div class="form-group" style="margin-top:15px;">                                                                              
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
                                允许使用
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

</style>
<script>
export default{
    data () {
        return {        	
        	gdata: gdata,
        	id:null,
        	edit:{
        		code:null,
        		used_times:null,
        		limit_times:null,
        		discount:null,
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
    		if( ! this.edit.limit_times) {
    			swal('限制次数不能为空');
    			return
    		}
    		if( ! this.edit.discount) {
    			swal('折扣比例不能为空');
    			return
    		}

    		if( ! this.edit.disabled) {
    			swal('请您选择是否允许使用');
    			return
    		}
        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newfriendcode'
        	} else {
        		api = '/admin/modifyfriendcode/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {                    
                if (response.data.code == 0) {
                	this.$router.push({name: 'friendcodemanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/friendcode/'+this.id).then(function (response) {                    
                     if (response.data.code == 0) {
                    	 this.edit = response.data.data       	 
                     }
                 })
        	}        	
        }
    }
}
</script>
