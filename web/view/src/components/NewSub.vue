<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
        	<router-link tag="li" style="cursor:pointer" :to="{name:'home'}">我的订阅</router-link>
            <li>新订阅</li>
        </ol>
 
            <div style="margin:0 auto;width:500px;">
                
         <form class="form-horizontal" role="form">
        	 	<div style="font-size:20px;color:#666;">订阅礼品</div>
            	 
         	   		<div class="gift-list" style="z-index:1;margin-top:15px;width:1130px;padding:10px;margin:0 auto;margin-left:-283px;padding-left:20px">
            
		          	  <div v-for="gift in gifts" class="gift" style="z-index:10!important;background:#fff;border:1px solid #e6e9eb;border-radius:3px;padding:10px;margin-right:20px;width:200px;height:205px;margin-bottom:15px;">
			               <p style="color:#333;font-weight:normal;text-align:center;margin-top:10px;">{{gift.name}}</p>
			            <ul style="list-style:none;margin:0;padding:0;line-height:32px;font-size:17px;text-align:center;color:#666;">		         	 
			              <li>{{gift.mo_bandwidth}}GB 流量</li>
			          	  <li style="color:#1e88e5">{{gift.mo_price}}￥/月</li>		          	  
			            </ul>
			          <p style="text-align:center;margin-top:20px">
			            <button type="button" class="btn-p btn btn-primary" @click="selectGift(gift)">选择</button>
			            </p>            
		            	</div>	   		            	          	        	          
	            	        <div style="clear:both"></div>
            	</div>
           
            
              <div style="font-size:20px;color:#666;margin-top:20px;">订阅时长</div>
            <div style="margin:5px 0;margin-top:10px;">
        
        
         <div class="form-group" style="margin-top:15px;">                                                                              
	                            <div class="col-sm-12" style="border:1px solid #ebebeb;border-radius:3px;width:500px;margin-left:15px;color:#999;padding-top:10px;">
	                                请您选择订阅时长
	                                <select v-model="mo" style="border:none;color:#666;margin-left:0px;border-bottom:1px dotted #ccc;height:30px;margin-bottom:10px;" class="form-control input" >                                	
	                                	<option value="1">1个月</option>        
	                                	<option value="6">6个月</option>   
	                                	<option value="12">12个月</option>                              	                        	
	                                </select>                            
	                            </div>
	                        </div>

	                 
            </div>
            
                  <div class="confirm" >
            
           			 <div style="margin:0 auto;">
            			            
			          	    
			          	  <p style="width:100%;border:1px solid #1e88e5;padding:20px;height:auto;border-radius:4px;line-height:24px;">
			          	  	<span style="font-size:24px;color:#1e88e5;">请您确认订阅信息</span><br /><br />
			          	  <span style="padding:10px;padding-right:15px;color:#999;">选择礼品</span><span style="font-weight:bold;"><template v-if="gift.id !=null">{{gift.name}}</template></span><br />
			          	  <span style="padding:10px;padding-right:15px;color:#999;">订阅时长</span><span style="font-weight:bold;"><template v-if="mo !=null">{{mo}} 个月</template></span><br />
			          	  <span style="padding:10px;padding-right:15px;color:#999;">订阅花费</span><span style="font-weight:bold;"><template v-if="price !=0"> {{price}}￥</template></span>
			          	   
			          	  </p>
			         
			       	  <div style="margin-top:25px;clear:both;">
			            	<button type="button" class="btn-p btn btn-primary" @click="submit">确认</button>
			            	  <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
			            	</div>  
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
    .gift-list {}
    .gift {float:left;}
    
</style>
<script>
export default{
    data () {
        return {        	
        	gdata: gdata,
        	gift: {id:null, mo_price:null},
            mo: null,
            gifts:[],
        }
    },
    created(){
    	this.getGiftlist()
    },
    computed: {
    	price () {
    		return this.gift.mo_price * this.mo
    	}	
    },
    methods: {    
    	goback(){
    		this.$router.push({name:'home'})
    	},    
    	getGiftlist() {
    		this.$http.get('/giftlist').then(function (response) {                    
                if (response.data.code == 0) {
                	 this.gifts = response.data.data
                }
            })
    	},
    	selectGift(gift) {
    		this.gift = gift    		
    	},
        submit () {    		
    		if(!this.gift.id) {
    			swal('请您选择订阅内容');
    			return
    		}
    		if(!this.mo) {
    			swal('请您选择订阅时长');
    			return
    		}
        	var params = {
        			giftId:this.gift.id,
        			mo:this.mo
        	}
        	
            this.$http.post('/newsub', params).then(function (response) {                    
                if (response.data.code == 0) {
                	this.$router.push({name: 'subdetail', params: {id: response.data.subId}})
                }
            })
        }
    }
}
</script>
