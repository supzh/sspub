<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">        
            <li>管理中心</li>              
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
        </div>
          
            <div style="float:left;clear:both;">
                   
	          <div class="normal_inf_box" @click="routerpush({name:'usermanage'})">
	        	  <p class="ltitle">总用户数</p>
	         	  <p class="ltext">{{info.all_user_num}}</p>
	          </div>	          	                    
	           
	           <div class="normal_inf_box">
		          <p class="ltitle">最近一小时在线用户数</p>
		          <p class="ltext">{{info.recent_online_user_num}}</p>
	          </div>
	          
	           <div class="normal_inf_box">
		          <p class="ltitle">最近一小时产生流量</p>
		          <p class="ltext">{{info.recent_online_generate_traffic_num}}</p>
	          </div>
	           
          </div>
          
          <div style="float:left;clear:both;margin-left:10px;">
          	<div style="float:left;">
          	   <h5 style="margin-top:30px;font-size:18px;color:#333;font-weight:normal">服务器信息</h5>
               <dl class="detail-dl-f" style="float:left;color:#666;margin-top:20px;">									
						<dd>PHP版本:</dd>
						<dt>{{info.php_version}}</dt>	
						<dd style="width:90px;">SSPUB版本:</dd>
						<dt style="border:none;">{{info.sspub_version}}</dt>			
                	</dl>
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
   
    .normal_inf_box {float:left;border-radius:3px;border:1px solid #e6e9eb;height:120px;width:260px;margin-right:30px;cursor:pointer;background:#e6e9eb;}
    .normal_inf_box:hover {border:1px solid #1e88e5}
   .normal_inf_box .ltitle {font-size:16px;height:20px;line-height:20px;margin-top:30px;color:#1e88e5;text-align:center;}
   .normal_inf_box .ltext {line-height:30px;height:30px;margin-top:10px;font-size:15px;color:#666;text-align:center;}
   .normal_inf_box .ltext .lweight {font-size:25px;color:#333;}
   
   
    .detail-dl-f {width:100%;}
    .detail-dl-f dd {float:left;font-weight:normal; width:auto;line-height:32px;height:32px!important;font-size:15px;margin:0;padding:0;}
    .detail-dl-f dt {float:left;font-weight:normal;width:auto;line-height:32px;height:32px;font-size:15px;margin:0;padding:0 20px 0 10px; border-right:1px solid #ddd;margin-right:10px;}
</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
               	info: {
               		all_user_num:'-',
               		recent_online_user_num:'-',
               		recent_online_generate_traffic_num:'-',
               		sspub_version:'-',
               		php_version:'-'
               	}
            }
        },
        created(){
        	this.loadinfo()
        },
        methods: {        	
        	routerpush(pushroutename){
        		this.$router.push(pushroutename)
        	},
        	loadinfo(){        	
        		this.$http.get('/admin/admincenterinfo').then((response)=>{        			  
                      if(response.data.code == 0 && response.data.data != undefined) {                    	
                    	this.info = response.data.data
                      }
        		})
        	},        	
        	goback(){
        		this.$router.push({name:'home'})
        	} 	
        }
    }
</script>
