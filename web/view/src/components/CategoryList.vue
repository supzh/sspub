<template>
    <div class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>支持</li>
        </ol>

          <div style="float:left;margin-bottom:60px;">
	        <ol class="submenu">
	            <li :class="{active:this.$route.name=='guides'}" @click="gosupport">知识库</li>
	            <li :class="{active:this.$route.name=='tickets'}" @click="gosupporttickets">我的提问</li>
	        </ol>
        </div>

       <div style="margin:0 auto;margin-top:50px;width:500px;">
             	<form class="form-horizontal" role="form">
						<div class="col-sm-12" style="padding:10px;font-size:20px;margin-left:0;padding-left:0;font-size:24px;">
                              全部分类
                        </div>
                         <div class="col-sm-12" style="padding:0;padding-bottom:10px;">
              		     	    <ul style="list-style:none;margin:0;padding:0;color:#666;line-height:40px;width:500px;font-size:16px;cursor:pointer;">
								<li v-for="v in list" @click="gocategory(v)"><div style="float:left;color:#1e88e5;font-size:23px;padding-right:15px;">+</div> {{v.name}}</li>
			               </ul>
                		 </div>

                    </form>

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
                list:[],
            }
        },
        created(){
        	this.loadList()
        },
        methods: {
        	gocategory(v){
        		this.$router.push({name:'category', params:{name:v.name}})
        	},
        	loadList(){
        		this.$http.get('/category').then((response)=>{
                      if(response.data.code == 0 && response.data.data != undefined) {
                         this.list = response.data.data
                      }
        		})
        	},
        	gosupport(){
        		this.$router.push({name:"support"})
        	},
			gosupporttickets(){
        		this.$router.push({name:"tickets"})
        	},
        	goback(){
        		this.$router.push({name:'home'})
        	}
        }
    }
</script>
