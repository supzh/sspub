<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>知识库管理</li>
        </ol>
        <div style="float:left;margin-bottom:60px;">
	        <ol class="submenu">
	        	<li :class="{active:this.$route.name=='admincenter'}" @click="routerpush({name:'admincenter'})">管理中心</li>
	            <li :class="{active:this.$route.name=='giftmanage'}" @click="routerpush({name:'giftmanage'})">礼品管理</li>
	            <li :class="{active:this.$route.name=='supportmanage'}" @click="routerpush({name:'supportmanage'})">知识库管理</li>
	            <li :class="{active:this.$route.name=='ticketsmanage'}" @click="routerpush({name:'ticketsmanage'})">提问管理</li>
	            <li :class="{active:this.$route.name=='servermanage'}" @click="routerpush({name:'servermanage'})">服务器管理</li>
	            <li :class="{active:this.$route.name=='usermanage'}" @click="routerpush({name:'usermanage'})">用户管理</li>
	            <li :class="{active:this.$route.name=='friendcodemanage'}" @click="routerpush({name:'friendcodemanage'})">邀请码管理</li>
	            <li :class="{active:this.$route.name=='paymanage'}" @click="routerpush({name:'paymanage'})">支付管理</li>
	            <li :class="{active:this.$route.name=='sitemanage'}" @click="routerpush({name:'sitemanage'})">站点配置</li>
	        </ol>
	        </ol>
        </div>

         <div style="clear:both;float:left;">
            <form class="form-inline" role="form">
      			  <router-link style="width:120px;margin-right:30px;" tag="button" :to="{name:'newcategory'}" class="btn-p btn btn-primary pull-left">新增分类</router-link>
      			  <router-link style="width:120px;margin-left:160px;" tag="button" :to="{name:'newdoc'}" class="btn-p btn btn-primary pull-left">新增文章</router-link>
             </form>
        </div>

         <div style="float:left; clear:left;width:300px;margin-top:30px;margin-right:10px;border-radius:4px!important;border:1px solid #edf0f2;">


          <table class="table">
            <caption></caption>
            <thead>
            <tr>
            	<th>分类</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>

             <template v-if="category.list.data.length !=0">

            <tr v-for="v in category.list.data">
           		<td>{{v.name}}</td>
                <td>
                     <router-link tag="button" :to="{name:'modifycategory', params:{id:v.id}}"  type="button" style="font-size:13px;height:35px;" class="btn-p btn btn-primary btn-sm">修改</router-link>
                     <button type="button" style="font-size:13px;height:35px;" class="btn-pc btn btn-primary btn-sm" @click="delcategory(v)">删除</button>
                </td>
            </tr>

             </template>
               <tr v-else>
                <td style="padding-top:30px;color:#999;text-align:center;" v-html="category.finishLoad" colspan="2"></td>
            </tr>
            </tbody>
       		 </table>
        	<div  style="text-align:center;padding:10px;color:#999;">
	         <button @click="loadcategoryList(category.list.ps, category.list.pn-1)" class="btn btn-default" v-if="category.list.pn!=0">上一页</button>
	         <span style="padding:0 10px;">第{{category.list.pn+1}}页</span>
	         <button @click="loadcategoryList(category.list.ps, category.list.pn+1)" class="btn btn-default" v-if="(category.list.pn+1)!=category.list.pnum">下一页</button>
	         </div>
        </div>


        <div style="float:left;clear:right; width:820px;margin-top:30px;border-radius:4px!important;border:1px solid #edf0f2;">
          <table class="table">
            <caption></caption>
            <thead>
            <tr>
            	<th>分类</th>
            	<th>标题</th>
            	<th>作者</th>
                <th>排序</th>
                <th>添加时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>

             <template v-if="docs.list.data.length !=0">

            <tr v-for="v in docs.list.data">
                <td>{{v.name}}</td>
                <td>{{v.title}}</td>
                <td>{{v.author}}</td>
                <td>{{v.sort}}</td>
                <td>{{v.created_at}}</td>
                <td>
                	<router-link tag="button" :to="{name:'modifydoc', params:{id:v.id}}"  type="button" style="font-size:13px;height:35px;" class="btn-p btn btn-primary btn-sm">修改</router-link>
                    <button type="button" style="font-size:13px;height:35px;" class="btn-pc btn btn-primary btn-sm" @click="deldoc(v)">删除</button>
                </td>
            </tr>
             </template>
               <tr v-else>
                   <td style="padding-top:30px;color:#999;text-align:center;" v-html="docs.finishLoad" colspan="6"></td>
           	   </tr>
            </tbody>
        </table>
      		  <div style="text-align:center;padding:10px;color:#999;">
	         <button @click="loaddocList(docs.list.ps, docs.list.pn-1)" class="btn btn-default" v-if="docs.list.pn!=0">上一页</button>
	         <span style="padding:0 10px;">第{{category.list.pn+1}}页</span>
	         <button @click="loaddocList(docs.list.ps, docs.list.pn+1)" class="btn btn-default" v-if="(docs.list.pn+1)!=docs.list.pnum">下一页</button>
	         </div>
        </div>
    </div>
</template>
<style>
    body{
        //background-color:#ff0000;
    }
    .submenu {list-style:none;margin:0;padding:0;margin-top:-20px;}
    .submenu li.active {color:#1e88e5;}
    .submenu li {float:left;height:20px;line-height:20px;font-size:18px;padding-right:25px;color:#999;padding-top:20px;cursor:pointer;}
    .btn-p {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#1e88e5;border:1px solid #1e88e5;}
    .btn-p:hover {background:#1e88e5;border:1px solid #1e88e5;}
    .btn-d {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#fff;border:1px solid #ddd;color:#333;}
    .btn-d:hover {background:#fff;border:1px solid #ccc;}
     .btn-pc {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#d9534f;border:1px solid #d9534f;}
     .btn-pc:hover{background:#d9534f;border:1px solid #d9534f;}
    .big_inf_box {float:left;border-radius:3px;border:1px solid #e6e9eb;height:200px;width:310px;margin-right:50px;cursor:pointer;}
    .big_inf_box:hover {border:1px solid #1e88e5}
   .big_inf_box .ltitle {font-size:26px;height:20px;line-height:20px;margin-top:70px;color:#1e88e5;text-align:center;}
   .big_inf_box .ltext {line-height:30px;height:30px;margin-top:10px;font-size:15px;color:#666;text-align:center;}
   .big_inf_box .ltext .lweight {font-size:25px;color:#333;}
   .detail-dl {width:280px;}
    .detail-dl dd {float:left;font-weight:bold;clear:left; width:80px;line-height:32px;height:32px!important;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
    .detail-dl dt {float:left;font-weight:normal;width:200px;line-height:32px;height:32px;font-size:15px;border-bottom:1px dotted #eee;margin:0;padding:0;}
</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
                category:{
                	list:{data:[], ps:null, pn:0, datanum:null, pnum:null},
                	finishLoad:'加载中'
                },
                docs:{
                	list:{data:[], ps:null, pn:0, datanum:null, pnum:null},
                	finishLoad:'加载中'
                }
            }
        },
        created(){
        	this.loadcategoryList()
        	this.loaddocList()
        },
        methods: {
        	routerpush(pushroutename){
        		this.$router.push(pushroutename)
        	},
        	delcategory(v){
        		var vc = this
            	swal({
            		  title: "请确认删除",
            		  text: "被删除数据无法恢复。",
            		  type: "warning",
            		  showCancelButton: true,
            		  cancelButtonText: "取消",
            		  confirmButtonText: "确认删除",
            		  closeOnConfirm: true
            		},
            		function(){
            			vc.$http.get('/admin/deletecategory/'+v.id).then((response)=>{
                        if(response.data.code == 0) {
                        	vc.loadcategoryList(vc.category.list.ps, vc.category.list.pn)
                        }
            		})
      			})
        	},
        	loadcategoryList(ps, pn){
        		if(!ps) {
        			ps = 10
        		}
        		if(!pn) {
        			pn = 0
        		}
        		this.$http.get('/admin/category?ps='+ps+'&pn='+pn).then((response)=>{
                      if(response.data.code == 0 && response.data.data != undefined) {
                    	 this.category.list = response.data.data
                         if(this.category.list.data.length == 0 ) {
                             this.category.finishLoad = '目前没有内容';
                         }
                      } else {
                      	 this.category.list.data = []
                      	 this.category.finishLoad = '目前没有内容';
                      }
        		})
        	},
        	deldoc(v){
        		var vc = this
            	swal({
            		  title: "请确认删除",
            		  text: "被删除数据无法恢复。",
            		  type: "warning",
            		  showCancelButton: true,
            		  cancelButtonText: "取消",
            		  confirmButtonText: "确认删除",
            		  closeOnConfirm: true
            		},
            		function(){
            			vc.$http.get('/admin/deletedoc/'+v.id).then((response)=>{
                        if(response.data.code == 0) {
                        	vc.loaddocList(vc.docs.list.ps, vc.docs.list.pn)
                        }
            		})
      			})
        	},
        	loaddocList(ps, pn){
        		if(!ps) {
        			ps = 10
        		}
        		if(!pn) {
        			pn = 0
        		}
        		this.$http.get('/admin/docs?ps='+ps+'&pn='+pn).then((response) => {
                      if(response.data.code == 0 && response.data.data != undefined) {
                    	  this.docs.list = response.data.data
	                      if(this.docs.list.data.length == 0 ) {
	                          this.docs.finishLoad = '目前没有内容';
	                      }
                      } else {
                      	 this.docs.list.data = []
                      	 this.docs.finishLoad = '目前没有内容';
                      }
        		})
        	}
        }
    }
</script>
