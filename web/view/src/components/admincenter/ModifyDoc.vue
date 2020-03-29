<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">
            <li v-if="this.id == null">新增分类</li>
            <li v-else>修改分类</li>
        </ol>
         <div style="float:left;clear:both;margin-bottom:30px;">
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
          </div>

           <div style="margin:0 auto;margin-top:50px;width:1000px;">
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              填写文章信息
                        </div>

                         <div class="form-group" style="margin-top:15px;float:left;">
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:200px;margin-left:15px;color:#999;padding-top:10px;">
                                分类
                                <select v-model="edit.support_category_id" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >
                                	<option :value="v.id" v-for="v in categorylist.data">{{v.name}}</option>
                                </select>

                            </div>
                        </div>

						  <div class="form-group" style="float:left;margin-top:15px;clear:right;margin-left:10px;width:820px;">

                            <div class="col-sm-12">
                                <input v-model="edit.title"  type="text" style="height:72px;" class="form-control input"  placeholder="标题">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <div class="col-sm-12">
                                <input v-model="edit.author" type="text" class="form-control input"  placeholder="作者">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">
                                <input v-model="edit.sort" type="number" class="form-control input"  placeholder="排序">
                            </div>
                        </div>

                        <div class="form-group" >
                            <div class="col-sm-12">
                            	<textarea id="editor" style="height:500px;border:none;" placeholder="文章内容"></textarea>
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
    body{
        //background-color:#ff0000;
    }
</style>
<script>
export default{
    data () {
        return {
        	gdata: gdata,
        	id: null,
        	edit:{
        		support_category_id: null,
        		title: null,
        		content: null,
        		author: null,
        		sort: null
        	},
        	//test:'testcontent',
        	categorylist:[],
        	ueditor:null,
        }
    },
    mounted(){
   		if(this.$route.params.id){
    		this.id = this.$route.params.id
    	}
   		this.getdata()
    	this.getCategorylist()
    },
    created(){

    },
    methods: {
    	goback(){
    		this.$router.go(-1)
    	},
    	getCategorylist() {
    		this.$http.get('/admin/category?ps=99').then(function (response) {
                if (response.data.code == 0) {
                	 this.categorylist = response.data.data
                }
            })
    	},
        submit () {
    		if( ! this.edit.support_category_id) {
    			swal('分类不能为空');
    			return
    		}
            if( ! this.edit.title) {
    			swal('标题不能为空');
    			return
    		}
            if( ! this.edit.author) {
    			swal('作者不能为空');
    			return
    		}
        	this.edit.content = this.ueditor.getContent();
            if( ! this.edit.content) {
    			swal('内容不能为空');
    			return
    		}

        	var params = this.edit
        	var api
        	if(this.id == null) {
        		api = '/admin/newdoc'
        	} else {
        		api = '/admin/modifydoc/' + this.id
        	}
            this.$http.post(api, params).then(function (response) {
                if (response.data.code == 0) {
                	this.$router.push({name: 'supportmanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/doc/'+this.id).then(function (response) {
                     if (response.data.code == 0){
                    	 this.edit = response.data.data
                     }
                     var vc = this
                     $(function(){
                    	 document.getElementById('editor').innerHTML = vc.edit.content
                     })
                     this.ueditor = UE.getEditor('editor')
                 })
        	} else {
        		this.ueditor = UE.getEditor('editor')
        	}
        }
    }
}
</script>
