<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">        
            <li>邀请码管理</li>              
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
      			  <router-link style="width:auto;" tag="button" :to="{name:'newfriendcode'}" class="btn-p btn btn-primary pull-left">生成邀请码</router-link>
             </form>
        </div>
        
        <div style="float:left;clear:both; width:100%;margin-top:30px;border-radius:4px!important;border:1px solid #edf0f2;">
          <table class="table" >
            <caption></caption>
            <thead>
            <tr>
             	<th>邀请码密码</th>
            	<th>生成时间</th>	
            	<th>已使用次数</th> 
                <th>限制使用次数</th>
                <th>暂停</th>
                <th>折扣比例</th>                
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            
           
             <template v-if="list.data.length !=0">
            
            <tr v-for="v in list.data">
           		<td>{{v.code}}</td>
                <td>{{v.created_at}}</td>
                <td>{{v.used_times}}</td>
                <td>{{v.limit_times}}</td>
                <td>{{yesorno[v.disabled]}}</td>
                <td>{{v.discount}}</td>                
                <td>
                   <router-link tag="button" :to="{name:'modifyfriendcode', params:{id:v.id}}"  type="button" style="font-size:13px;height:35px;" class="btn-p btn btn-primary btn-sm">修改</router-link>
                 </td>           
            </tr>
            
             </template>         
               <tr v-else>
                <td style="padding-top:30px;color:#999;text-align:center;" v-html="finishLoad" colspan="7"></td>
            </tr>     
            </tbody>
        </table>
        	<div  style="text-align:center;padding:10px;color:#999;">        	
	         <button @click="loadList(list.ps, list.pn-1)"  class="btn btn-default" v-if="list.pn!=0">上一页</button>
	         <span style="padding:0 10px;">第{{list.pn+1}}页</span>
	         <button @click="loadList(list.ps, list.pn+1)" class="btn btn-default" v-if="(list.pn+1)!=list.pnum">下一页</button>
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
                list:{data:[], ps:null, pn:0, datanum:null, pnum:null},
                finishLoad:'加载中',
                yesorno:{
                	0:'否',
                	1:'是'
                }
            }
        },
        created(){
        	this.loadList()
        },
        methods: {        	
        	routerpush(pushroutename){
        		this.$router.push(pushroutename)
        	},
        	loadList(ps, pn){
        		if(!ps) {
        			ps = 10
        		}
        		if(!pn) {
        			pn = 0    			
        		}
        		this.$http.get('/admin/friendcodes?ps='+ps+'&pn='+pn).then((response)=>{        			  
                      if(response.data.code == 0 && response.data.data != undefined) {                    	
                    	  this.list = response.data.data
                         if(this.list.data.length == 0 ) {
                             this.finishLoad = '目前没有内容';
                         }
                      } else {
                      	 this.list.data = []
                      	 this.finishLoad = '目前没有内容';
                      }
        		})
        	}
        }
    }
</script>
