<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">
            <li v-if="this.id == null">新增分类</li>
            <li v-else>修改分类</li>
        </ol>
          <div style="float:left;clear:both;margin-bottom:30px;">
              <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
           </div>
           <div style="margin:0 auto;margin-top:50px;width:500px;">
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写分类信息
                        </div>
                        <div class="form-group" style="margin-top:15px;">
                            <div class="col-sm-12">
                                <input type="text" v-model="edit.name" class="form-control input" placeholder="分类名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                 <button @click="submit"  type="button" style="margin-top:15px;width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认</button>
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
    			swal('名称不能为空');
    			return
    		}
        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newcategory'
        	} else {
        		api = '/admin/modifycategory/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {
                if (response.data.code == 0) {
                	this.$router.push({name: 'supportmanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/category/'+this.id).then(function (response) {
                     if (response.data.code == 0) {
                    	 this.edit =  {
                         	name: response.data.data.name,
                         }
                     }
                 })
        	}
        }
    }
}
</script>
