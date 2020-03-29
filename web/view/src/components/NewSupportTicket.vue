<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">
            <li>支持</li>
        </ol>
        <div style="float:left;margin-bottom:60px;">
	        <ol class="submenu">
	            <li :class="{active:this.$route.name=='support'}" @click="gosupport">知识库</li>
	            <li :class="{active:this.$route.name=='modifyticket'||this.$route.name=='newticket'}" @click="gosupporttickets">我的提问</li>
	        </ol>
        </div>

           <div style="margin:0 auto;margin-top:50px;width:500px;">

             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">
                              <template v-if="this.id == null">发起提问</template>
                              <template v-else>查看提问</template>
                        </div>
                          <div class="col-sm-12" style="padding:0;padding-bottom:10px;">
                		  <label style="float:left;">
                		  <div style="border:1px solid #eee;border-radius:3px;padding:20px;width:240px;color:#666;font-weight:normal">
                  	 		<template  v-if="this.id == null" ><input type="radio" value="1" v-model="edit.type"> 普通问题</template>
                  	 		<template v-else><input disabled type="radio" value="1" v-model="edit.type"> 普通问题</template>
                  	 	  </div>
                  	 	  </label>

                  		 <label style="float:right;">
                  	     <div style="border:1px solid #eee;border-radius:3px;padding:20px;width:240px;color:#666;font-weight:normal">
                  	 		<template  v-if="this.id == null" ><input type="radio" value="2" v-model="edit.type"> 账单问题</template>
                  	 		<template v-else><input disabled type="radio" value="2" v-model="edit.type"> 账单问题</template>
                  	 	</div>
                  	 		 </label>
                 		 </div>

                        <div class="form-group" style="margin-top:15px;">

                            <div class="col-sm-12">

                                 <template  v-if="this.id == null" >
                                   <input type="text" v-model="edit.subject" class="form-control input"  placeholder="主题">
                                </template>
                                <template v-else>
                                   <input readonly type="text" v-model="edit.subject" class="form-control input"  placeholder="主题">
                               </template>
                            </div>
                        </div>

                        <div class="form-group" >

                            <div class="col-sm-12">
                              <template  v-if="this.id == null" >
                                <textarea  v-model="edit.content" class="form-control input" style="min-height:200px;"  placeholder="问题描述"></textarea>
                                </template>
                                <template v-else>
                                 <textarea readonly v-model="edit.content" class="form-control input" style="min-height:200px;"  placeholder="问题描述"></textarea>
                                 </template>
                            </div>
                        </div>

                         <div class="form-group" style="margin-top:15px;">
                       <template  v-if="this.id == null" >
                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;">
                            上传附件 ( 文件大小不能超过 5MB )
                                <input type="file" @change="fileUpload"  id="ticket-file-input" style="border:none;height:40px;font-size:12px;color:#666;line-height:20px;" class="form-control input"  placeholder="附件">
                            </div>
                            </template>
                            <template v-else>
                             <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px">
                            附件文件<br />
                                <a :href="edit.file"  style="border:none;height:40px;font-size:12px;color:#666;line-height:20px;" target="_BLANK">{{edit.filename}}</a>
                            </div>
                            </template>
                        </div>
                           <div v-if="this.id!=null" class="form-group" style="margin-top:15px;">

                             <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;min-height:200px;margin-left:15px;color:#666;padding-top:10px;padding-bottom:10px">
                              客服答复内容<br /><br />

                              {{edit.reply_content}}
                            </div>


                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                  <button  v-if="this.id == null" @click="submit" type="button" style="margin-top:15px;width:100%;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn-p btn btn-primary">确认</button>
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
import Storage from './../storage.js'
export default{
    data () {
        return {
        	gdata: gdata,
        	id: null,
        	edit:{
        		type:null,
        		subject:null,
        		content:null,
        		file:null,
        		filename:null,
        		reply_content:null
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
    	gosupport(){
    		this.$router.push({name:"support"})
    	},
		gosupporttickets(){
    		this.$router.push({name:"tickets"})
    	},
    	goback(){
    		this.$router.go(-1)
    	},
        fileUpload() {
            var vm = this
            var file = document.querySelector('#ticket-file-input')
            file = file.files[0]
            var url =  gdata.serverhost + '/api/uploadfile?sessid='+Storage.get('sessid');
            var xhr = new XMLHttpRequest();
            var fd = new FormData();
            xhr.open("POST", url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Every thing ok, file uploaded
                    var res = JSON.parse(xhr.responseText); // handle response.
                    if(res.code != 0) {
                         swal({title:'消息',confirmButtonText:'关闭',text:res.msg})
                    } else {
                        vm.edit.file = res.data.url
                    }
                }
            };
            fd.append("file", file);
            fd.append("sessid", Storage.get('sessid'));
            xhr.send(fd);
        },
        submit () {
    		if( ! this.edit.type) {
    			swal('您还没有选择问题类型');
    			return
    		}
    		if( ! this.edit.subject) {
    			swal('主题不能为空');
    			return
    		}
    		if( ! this.edit.content) {
    			swal('内容不能为空');
    			return
    		}

    		var params = this.edit
            this.$http.post('/newticket' , params).then(function (response) {
                if (response.data.code == 0) {
                	this.$router.push({name: 'tickets'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/ticket/'+this.id).then(function (response) {
                     if (response.data.code == 0) {
                    	 this.edit = response.data.data
                    	 if(this.edit.file) {
                    		var fn = this.edit.file.split('_')
                    		this.edit.filename = fn[0] =''
							for(var n in fn) {
								this.edit.filename+=fn[n]
							}

                    	 }
                     }
                 })
        	}
        }
    }
}
</script>
