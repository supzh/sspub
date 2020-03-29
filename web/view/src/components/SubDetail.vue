<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
           <router-link tag="li" style="cursor:pointer" :to="{name:'home'}">我的订阅</router-link>
            <li>订阅详情</li>              
        </ol>
          
          <div class="modal fade downloadclientmodal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="editModalLabel">客户端下载</h4>
                    </div>
                    <div class="modal-body">                        
                                         
                                         <div style="padding:50px;text-align:center;line-height:80px;">                 
                        <button id="winclientdownload" type="button" class="btn-p btn btn-primary" @click="downloadClient('windows')" style="height:70px;">
                        Windows 用户客户端下载
                        <br /><span style="font-size:12px;">Shadowsocks-3.2.zip</span></button>
               
                         <button id="macclientdownload" type="button" class="btn-p btn btn-primary" @click="downloadClient('mac')"  style="height:70px;">
                         Mac OS X 用户客户端下载<br /><span style="font-size:12px;">ShadowsocksX.app</span></button>
                         <br />
                        <button type="button" class="btn-p btn btn-primary"  @click="godoc('Android 用户使用指导')" >Android 用户使用安装指导</button>
                
                          <button type="button" class="btn-p btn btn-primary"  @click="godoc('iOS 用户使用指导')"  >iOS 用户使用安装指导</button>          
                        </div>
                                             
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-d btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
         </div>
            
            
            
         <div style="float:left;clear:both;margin-bottom:30px;">                	
                   	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
					<button v-if="gift.state == 0" type="button" class="btn-p btn btn-primary btn-sm" @click="pay(gift)">支付</button>					
					
                    <button  v-if="gift.state == 0"  type="button" class="btn-pc btn btn-danger btn-sm" @click="cancel(gift)">取消</button>                                       
                    <button  v-if="gift.state == 2"  type="button" class="btn-pc btn-danger btn-sm" @click="del(gift)">删除</button>
                                     
                    <button type="button" data-toggle="modal" data-target="#editModal" class="btn-pcw btn btn-warning btn-sm">客户端下载</button>
                  
           </div> 
            
            
         
                    <div style="float:left;clear:both;">
                    <div style="width:350px;height:auto;float:left;margin-right:25px;">
	          <div class="inf_box" @click="gotrafficlog">
	        	  <p class="ltitle">本月流量已使用</p>
	         	 <p class="ltext"><span class="lweight">{{gift.d}}</span>/ {{gift.transfer_enable}}</p>
	          </div>
	          <dl class="detail-dl" style="float:left;color:#666;margin-top:20px;">				
						<dd>礼品名:</dd>
						<dt>{{gift.name}}</dt>
						<dd>订阅状态:</dd>
						<dt>{{stateText[gift.state]}}</dt>
						<dd>订阅时长:</dd>
						<dt>{{gift.mo}}个月</dt>  		
						<dd>花费:</dd>
						<dt>
						<template v-if="gift.cost_money !=0">{{gift.cost_money}}￥
							
							<template v-if="gift.instance_discount !=1">							
							{{gift.instance_discount*100}}%折扣												
							</template>
						
						</template>
						<template v-else>免费</template>
						</dt>              							
                	</dl>
                	</div>
                	
                	
	             <div style="width:350px;height:auto;float:left;margin-right:25px;">
	           
	           <div class="inf_box">
		          <p class="ltitle">订阅有效期至</p>
		          <p class="ltext" v-if="!gift.expired_at">尚未生效</p>
		           <p class="ltext" v-else>{{gift.expired_at}}</p>
	          </div>
	           <dl class="detail-dl" style="float:left;color:#666;margin-top:20px;">				
					    <dd>服务器:</dd>
						<dt>(<span style="color:#0084df;padding:2px;cursor:pointer;" @click="goservers">显示详情</span>)</dt>	         
                		<dd>端口:</dd>
						<dt>{{gift.port}}</dt>
						<dd>密码:</dd>
						<dt><template>{{showpass}}</template> <span @click="showpassclick" style="font-size:10px;line-height:32px;color:#0084df;padding:2px;cursor:pointer;">
						<template v-if="showpass == '******'">[显示]</template><template v-else>[收起]</template></span>
						<span @click="resetpass" style="float:right;font-size:10px;line-height:32px;color:#999;padding:2px;cursor:pointer;">[重置密码]</span>
						</dt>
						<dd>加密方式:</dd>
						<dt>{{gift.method}}</dt>
                	</dl>
          
                	
	          	</div>
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
      .btn-pcw {margin-right:6px;width:auto;padding-left:30px;padding-right:30px;height:50px;font-size:15px;background:#f0ad4e;border:1px solid #f0ad4e!important;}
     .btn-pcw:hover{background:#f0ad4e;border:1px solid #f0ad4e;}
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
                gift:{
                	user_sub_gift_instance_id:null,
                	name:null,
                	state:null,
                	mo:null,
                	expired_at:null,
                	port:null,
                	passwd:null,
                	method:null,
                	transfer_enable:null,
                	d:null
                },
                stateText: {
                	0: "待付款",
                	1: "已生效",
                	2: "已取消"
                },
                showpass:'******'
            }
        },
        created(){
        	this.loadGift(this.$route.params.id)
        },
        methods: {
        	godoc(title){
        		 $('.downloadclientmodal').modal('hide')
        		this.$router.push({name:'doc', params:{title:title}})
        	},    
        	showpassclick(){
        		if(this.showpass == '******') {
        			this.showpass = this.gift.passwd
        		} else {
        			this.showpass = '******'
        		}        		
        	},
        	resetpass(){
        		if(confirm('确认要重置吗？')) {
        		this.$http.get('/sub/'+this.gift.user_sub_gift_instance_id+'/resetpassword').then((response) => {
                    if(response.data.code == 0 && response.data.data != undefined) {
                    	this.showpass = this.gift.passwd = response.data.data                       
                    }
        		
      			})
        		}
        	},
        	cancel(gift){
        		
        		this.$http.get('/sub/'+this.gift.user_sub_gift_instance_id+'/cancel').then((response) => {
        			if(response.data.code == 0) {
                    	this.loadGift(this.gift.user_sub_gift_instance_id)
                    	
                    }                     	                    
        		})        		        		          		
        	},
        	del(gift){
        		
        		var vc = this
            	swal({
            		  title: "请确认删除",
            		  text: "被删除后将无法恢复。",
            		  type: "warning",
            		  showCancelButton: true,         
            		  cancelButtonText: "取消",
            		  confirmButtonText: "确认",
            		  closeOnConfirm: true
            		},
            		function(){
            			vc.$http.get('/sub/'+vc.gift.user_sub_gift_instance_id+'/delete').then((response) => {
                            if(response.data.code == 0) {
                            	vc.$router.push({name:'home'});                     
                            }
                		
              			})    
      			})
    
        	},
        	downloadClient(platform) {        		
        		if(platform == 'windows') {	        		
	        		this.$http.get('/sub/'+this.gift.user_sub_gift_instance_id+'/getclientdownload').then((response) => {
	                    if(response.data.code == 0) {
	                    	var download = response.data.data.url
	                    	swal({
	                  		  title: "下载客户端",
	                  		  text: "下载Shadowsocks Windows用户客户端（已包含订阅信息服务器配置文件）",	                  		 
	                  		  showCancelButton: true,         
	                  		  cancelButtonText: "取消",
	                  		  confirmButtonText: "开始下载",
	                  		  closeOnConfirm: true
	                  		},
	                  		function(){	                  			
	                  			   window.open(download)
	            			})
	                    }	        	
	      			})    
        		} else {
        			this.$http.get('/sub/'+this.gift.user_sub_gift_instance_id+'/getmacclientdownload').then((response) => {
	                    if(response.data.code == 0) {
	                    	var download = response.data.data.url
	                    	swal({
	                  		  title: "下载客户端",
	                  		  text: "下载Shadowsocks-NG Mac用户客户端（已包含订阅信息服务器配置文件）",	                  		 
	                  		  showCancelButton: true,         
	                  		  cancelButtonText: "取消",
	                  		  confirmButtonText: "开始下载",
	                  		  closeOnConfirm: true
	                  		},
	                  		function(){	                  			
	                  			   window.open(download)
	            			})
	                    }	        	
	      			})    
        		}
        	},        
        	gotrafficlog(){
        		this.$router.push({name:'trafficlog', params: {id: this.gift.user_sub_gift_instance_id}})
        	},
        	goservers(){
        		this.$router.push({name:'servers'})
        	},
        	goback(){
        		this.$router.push({name:'home'})
        	},
        	pay(gift){
        		this.$router.push({name:'paysub', params: {id: gift.user_sub_gift_instance_id}});
        	},
        	loadGift(id){
        		this.$http.get('/subdetail/'+id).then((response) => {
                      if(response.data.code == 0 && response.data.data != undefined) {
                         this.gift = response.data.data
                      }
        		})
        	}        	
        }
    }
</script>
