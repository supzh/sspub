<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
       <ol class="breadcrumb">
            <li>答复用户提问</li>
        </ol>
         <div style="float:left;clear:both;margin-bottom:30px;">
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                   	  </div>

           <div style="margin:0 auto;margin-top:50px;width:500px;">

             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">

                              提问信息
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

                             <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px">
                              提问用户<br />
                              {{edit.user_name}} ({{edit.email}})
                            </div>

                        </div>



                        <div class="form-group" style="margin-top:15px;">



                             <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px">
                              主题<br />
                              {{edit.subject}}
                            </div>


                        </div>
                           <div class="form-group" style="margin-top:15px;">



                             <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;font-size:12px;padding-top:10px;padding-bottom:10px">
                              问题描述<br />
                              {{edit.content}}
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
                        <div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;">

                              答复提问
                        </div>
                          <div class="form-group" >

                            <div class="col-sm-12">
                                <textarea v-model="edit.reply_content" class="form-control input" style="min-height:200px;"  placeholder="回复内容"></textarea>
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
import Storage from './../../storage.js'
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
    		if( ! this.edit.reply_content) {
    			swal('回复内容不能为空');
    			return
    		}
    		var params = this.edit
            this.$http.post('/admin/replyticket/' + this.id , params).then(function (response) {
                if (response.data.code == 0) {
                	this.$router.push({name: 'ticketsmanage'})
                }
            })
        },
        getdata(){
        	if(this.id) {
        		 this.$http.get('/admin/ticket/'+this.id).then(function (response) {
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
