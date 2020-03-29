<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">        
            <li>流量统计</li>              
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
        
        <div style="float:left;clear:both; width:100%;margin-top:30px;border-radius:4px!important;border:1px solid #edf0f2;">
          <table class="table" >
            <caption></caption>
            <thead>
            <tr>
             	<th>用户邮箱</th>
            	<th>注册时间</th>	
            	<th>最后在线时间</th> 
                <th>注册IP</th>
                <th>消费金额</th>
                <th>使用中的订阅数量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            
             <template v-if="list.length !=0">
            
            <tr v-for="v in list">
           		<td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>                   
                <td>
                     <button type="button" style="font-size:13px;height:35px;" class="btn-d btn btn-default btn-sm" @click="godetail(v)">详情</button>            
                </td>           
            </tr>
            
             </template>         
               <tr v-else>
                <td style="padding-top:30px;color:#999;text-align:center;" v-html="finishLoad" colspan="7"></td>
            </tr>     
            </tbody>
        </table></div>

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
                list:[],
                finishLoad:'加载中'
            }
        },
        created(){
        	this.loadList()
        },
        methods: {        	
        	routerpush(pushroutename){
        		this.$router.push(pushroutename)
        	},
        	loadList(){
        		this.$http.get('/tickets').then((response)=>{        			  
                      if(response.data.code == 0 && response.data.data != undefined) {
                         this.list = response.data.data   
                         if(this.list.length == 0 ) {
                             this.finishLoad = '目前没有内容';
                         }
                      } else {
                      	 this.list = []
                      	 this.finishLoad = '目前没有内容';
                      }
        		})
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
