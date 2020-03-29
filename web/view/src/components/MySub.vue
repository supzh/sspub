<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>我的订阅</li>
        </ol>

        <div style="float:left;margin-right:20px;">
            <form class="form-inline" role="form">
      			  <router-link style="width:120px;height:120px;border-radius:200px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" tag="button" :to="{name:'newsub'}" class="btn btn-primary pull-left">新订阅</router-link>
             </form>
             </div>
        <div style="float:left; width:1000px;border-radius:4px!important;border:1px solid #edf0f2;">
          <table class="table" >
            <caption></caption>
            <thead >
            <tr>
                <th>订阅礼品</th>
                <th>状态</th>
                <th>有效期至</th>
                <th style="text-align:left;">订阅内容</th>
                <th style="text-align:left;">操作</th>
            </tr>
            </thead>
            <tbody>

              <template v-if="list.data.length !=0">
            <tr v-for="v in list.data">
                <td>{{v.name}}</td>
                <td>{{stateText[v.state]}}</td>
                <td v-if="v.state == 1">{{v.expired_at}}</td>
                <td v-else>尚未生效</td>
                <td>
                	 <div class="inf_box" style="width:120px;height:67px;" @click="godetail(v)">
	        	  <p class="ltitle" style="margin-top:10px;text-align:left;">每月流量</p>
	         	 <p class="ltext" style="text-align:left;">{{v.transfer_enable}}</p>
	          </div>

                </td>
                <td style="text-align:left;">
                    <button type="button" style="font-size:13px;height:35px;" class="btn-p btn btn-primary btn-sm" @click="godetail(v)">详情</button>
                </td>
            </tr>
             </template>
            <tr v-else>
               <td style="padding-top:30px;color:#999;text-align:center;" v-html="finishLoad" colspan="5"></td>
            </tr>
            </tbody>
        </table>
         <div style="text-align:center;padding:10px;color:#999;">
	         <button @click="loadList(list.ps, list.pn-1)"  class="btn btn-default" v-if="list.pn!=0">上一页</button>
	         <span style="padding:0 10px;">第{{list.pn+1}}页</span>
	         <button @click="loadList(list.ps, list.pn+1)" class="btn btn-default" v-if="((list.pn+1)!=list.pnum) && (list.pnum!=0)">下一页</button>
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
    color:#666;
    }

     .table>tbody>tr>td {
     line-height:70px;
     text-align:center;
     }

    .table>thead>tr>th {
    border-bottom:1px solid #eee;
    text-align:center;
    color:#999;
    /*background:linear-gradient(0deg, #fafafa 1%, #fff 100%);*/
    }
</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
                list:{data:[], ps:null, pn:0, datanum:null, pnum:null},
                stateText: {
                	0: "待付款",
                	1: "已生效",
                	2: "已取消"
                },
                finishLoad:'加载中'
            }
        },
        created(){
        	this.loadList()
        },
        methods: {
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
        		this.$http.get('/mysub?ps='+ps+'&pn='+pn).then((response)=>{
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
