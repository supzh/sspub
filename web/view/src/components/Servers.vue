<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>服务器</li>            
        </ol>
        
          <div style="float:left;clear:both;margin-bottom:30px;">                	
              <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
          </div>
        
        <div style="float:left; width:100%;border-radius:4px!important;border:1px solid #edf0f2;">
          <table class="table" >
            <caption></caption>
            <thead>
            <tr>
            	<th>服务器名称</th>
                <th>服务器地址</th>
                <th>状态</th>  
                <th>在线人数</th>
                    
                <th>操作</th>
            </tr>
            </thead>
            <tbody>            
             <template v-if="list.data.length !=0">            
            <tr v-for="v in list.data">
            	<td>{{v.name}}</td>
               <td>{{v.server}}</td>
               <td>{{stateText[v.state]}}</td>
               <td>{{v.online_user}}</td>                       
                <td>
                    <button type="button" style="font-size:13px;height:35px;" class="btn-p btn btn-primary btn-sm" @click="testspeed(v)">测速</button>                                
                </td>
            </tr>
            
             </template>
             <tr v-else>
                <td style="padding-top:30px;color:#999;text-align:center;" v-html="finishLoad" colspan="9"></td>
            </tr>     
            </tbody>
        </table>
         <div v-if="list.data.length !=0" style="text-align:center;padding:10px;color:#999;">        	
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
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th  {
    border-top:1px dotted #eee;
    }
    
    .table>thead>tr>th {
    border-bottom:1px solid #eee;
    /*background:linear-gradient(0deg, #fafafa 1%, #fff 100%);*/
    }
</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
                list:{data:[], ps:null, pn:0, datanum:null, pnum:null},
                finishLoad:'加载中',
                stateText: {
                	1: "工作中",
                	0: "已关闭"
                }
            }
        },
        created(){
        	this.loadList()
        },
        methods: {
        	testspeed(v){
        		window.open('http://'+v.server+':12345/testspeed.zip')
        	},
        	goback(){  
        		this.$router.go(-1);
        	},
	        godetail(gift){
	        	this.$router.push({name:'subdetail', params: {id:gift.user_sub_gift_instance_id}})
	        },
        	loadList(ps, pn){
        		if(!ps) {
        			ps = 10
        		}
        		if(!pn) {
        			pn = 0    			
        		}
        		this.$http.get('/servers?ps='+ps+'&pn='+pn).then((response)=>{        			  
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
