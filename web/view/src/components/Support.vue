<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">        
            <li>支持</li>              
        </ol>
        <div style="float:left;margin-bottom:60px;">
	        <ol class="submenu">        
	            <li :class="{active:this.$route.name=='support'}" @click="gosupport">知识库</li>
	            <li :class="{active:this.$route.name=='tickets'}" @click="gosupporttickets">我的提问</li>
	        </ol>
        </div>                   
            
            <div style="float:left;clear:both;">
                   
	          <div class="big_inf_box" @click="gofaq">
	        	  <p class="ltitle">FAQ</p>
	         	  <p class="ltext">查看问题解答是否包含您所需要的问题</p>
	          </div>	          	                    
	           
	           <div class="big_inf_box" @click="goguides">
		          <p class="ltitle">安装使用指导</p>
		          <p class="ltext">查看安装使用指导教程以及客户端下载</p>
	          </div>
	          
	           <div class="big_inf_box" @click="gonewticket">
		          <p class="ltitle">发起提问工单</p>
		          <p class="ltext">在线填写描述您的问题，由客服帮助您解答</p>
	          </div>
	           
          </div>
          
          <div style="float:left;clear:both;margin-left:10px;">
          	<div style="float:left;">
          	   <h5 style="margin-top:60px;font-size:18px;color:#333;font-weight:normal">常见问题</h5>
               <ul style="list-style:none;margin:0;padding:0;color:#666;line-height:36px;width:500px;cursor:pointer">				
					<template v-if="popularquestion.length!=0" >
					<li v-for="v in popularquestion" @click="godoc(v.id)"><div style="float:left;color:#1e88e5;font-size:22px;padding-right:15px;">+</div> {{v.title}}</li>
					</template>
					<li v-else>目前没有数据。</li>
				 </ul>
               <button v-if="popularquestion.length!=0" @click="gofaq" type="button" style="font-weight:bold;margin-top:30px;width:100%;color:#666;height:45px;font-size:13px;" class="btn-d btn btn-default btn-sm">查看所有问题</button>              	
            </div>
            
            <div style="float:left; margin-left:30px;">
          	   <h5 style="margin-top:60px;font-size:18px;color:#333;font-weight:normal">常见分类指导</h5>
               <ul style="list-style:none;margin:0;padding:0;color:#666;line-height:36px;width:500px;cursor:pointer">	
               		<li v-for="v in category"  @click="gocategory(v)"><div style="float:left;color:#1e88e5;font-size:22px;padding-right:15px;"></div> {{v.name}}</li>					
               </ul>
               <button @click="gocategorylist" type="button" style="font-weight:bold;margin-top:30px;width:100%;color:#666;height:45px;font-size:13px;" class="btn-d btn btn-default btn-sm">查看所有分类</button>              	
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
                category:[],
            	popularquestion:[]
            }
        },
        created(){
        	this.getcategory()
        	this.getpopularquestion()
        },
        methods: {
        	getcategory(){
        		this.$http.get('/category?limit=5').then((response) => {
                    if(response.data.code == 0 && response.data.data != undefined) {
                       this.category = response.data.data
                    }
      			})
        	},
        	getpopularquestion(){
        		this.$http.get('/popularquestion?limit=5').then((response) => {
                    if(response.data.code == 0 && response.data.data != undefined) {
                       this.popularquestion = response.data.data
                    }
      			})
        	},
        	gocategory(v){
        		this.$router.push({name:'category',params:{name:v.name}})
        	},        	       
        	godoc(id){
        		this.$router.push({name:'doc', params:{id:id}})
        	},
        	gofaq(){
        		this.$router.push({name:'category',params:{name:'FAQ'}})
        	}
        	,
        	gocategorylist(){
        		this.$router.push({name:'categorylist'})
        	}
        	,
        	goguides(){
        		this.$router.push({name:"guides"})
        	},
        	gonewticket(){
        		this.$router.push({name:"newticket"})
        	},
        	gosupport(){
        		this.$router.push({name:"support"})
        	},
			gosupporttickets(){
        		this.$router.push({name:"tickets"})
        	},
        	cancel(){
        		this.$router.push({name:'home'});
        	},
        	downloadClient(platform) {
        		
        	},
        	guide(device){
        		
        	},
        	goback(){
        		this.$router.push({name:'home'})
        	} 	
        }
    }
</script>
