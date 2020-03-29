<template>
    <div  v-if="gdata.isLogin"  class="tab-pane in active" id="app-about">
        <ol class="breadcrumb">
            <li>我的订阅</li>
            <li>订阅支付</li>
        </ol>

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="editModalLabel">支付结果</h4>
                    </div>
                    <div class="modal-body">
                    	<div style="padding:50px;text-align:center;">
                        <button type="button" class="btn-p btn btn-primary" @click="ok" data-dismiss="modal">支付已完成</button>
                        <button type="button" class="btn-d btn btn-default"  data-dismiss="modal">支付遇到问题</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>

         <div style="float:left;border:1px solid #ddd;width:500px;padding:20px;border-radius:3px;">
	           <dl class="detail-dl" style="float:left;">
	             		<dd>订阅ID</dd>
						<dt>{{gift.user_sub_gift_instance_id}}</dt>
						<dd>礼品</dd>
						<dt>{{gift.name}}</dt>
						<dd>时长</dd>
						<dt>{{gift.mo}}个月</dt>
						<dd>每月流量</dd>
						<dt>{{gift.transfer_enable}}</dt>
						<dd>费用</dd>
						<dt>{{gift.cost_money}}￥ <template v-if="gift.code_discount!=1">已使用邀请码（{{gift.code_discount*100}}%折扣）</template></dt>
	             	</dl>
         		 <div>
                  <div class="form-group" style="margin-top:-10px;">

                <input v-model="usefriendcode" type="text" style="border:none;width:200px;height:30px;font-size:13px;border-bottom:1px dotted #dedede;" class="form-control input"  placeholder="使用邀请码(可选)">
                      <button @click="confirmusefriendcode" v-if="usefriendcode!=''" class="btn btn-xs btn-default" style="margin-top:10px;margin-left:15px;">确认使用邀请码</button>

                 </div>
                  <div style="clear:both;">
                		  <div>
                		  <label>
                		  <div style="float:left;margin-right:10px;border:1px solid #eee;padding:20px;width:150px;">
                  	 		<input type="radio" value="1" v-model="paytype"> 支付宝支付

                  	 		</div>
                  	 		</label>

                  	 <label>
                  	     <div style="float:left;margin-right:10px;border:1px solid #eee;padding:20px;width:150px;">
                  	 		<input type="radio"  value="2" v-model="paytype"> 微信支付
                  	 		</div>
                  	 	</label>
                 		 </div>

                 		<div style="clear:left;margin-top:20px;float:left;">

					  <button type="button" class="btn-p btn btn-primary btn-sm" @click="confirmpay">确认支付</button>
                      <button type="button" class="btn-d btn btn-default btn-sm" @click="goback">返回</button>
                      </div>

                      </div>

        		  </div>


           </div>
    </div>
</template>
<style>
    body{
        //background-color:#ff0000;
    }

</style>
<script>
    export default{
        data () {
            return {
                gdata:gdata,
                id:null,
                gift:{
                	id:null,
                	name:null,
                	state:null,
                	mo:null,
                	expired_at:null,
                	port:null,
                	passwd:null,
                	method:null,
                	transfer_enable:null,
                	d:null,
                	code_discount:1
                },
                stateText: {
                	0: "待付款",
                	1: "已生效"
                },
                paytype:null,
                usefriendcode:''
            }
        },
        created(){
        	this.id = this.$route.params.id
        	this.loadGift()
        },
        methods: {
        	confirmusefriendcode(){
        		if(!this.usefriendcode) {
        			alert('请您输入邀请码');
        		}
        		var params = {
        				friendcode:this.usefriendcode
        		}
        		this.$http.post('/sub/usefriendcode/' + this.id, params).then((response)=>{
                    if(response.data.code == 0 && response.data.data != undefined) {
                       this.gift.cost_money = response.data.data.cost_money
                       this.gift.code_discount = response.data.data.discount
                    }
      		})

        	},
        	confirmpay(){
        		if(!this.paytype) {
        			swal('请您选择支付类型')
        			return
        		}

        		var vc = this
            	swal({
            		  title: "支付结果",
            		  text: "",
            		  type: "warning",
            		  showCancelButton: true,
            		  confirmButtonText: "支付成功",
            		  cancelButtonText: "支付遇到问题",
            		  closeOnConfirm: true
            		},
            		function(){
            			vc.$router.push({name:'subdetail', params:{'id': vc.id}})
            		}      			)


        		window.open(gdata.serverhost + '/userpay?type='+this.paytype+'&subid='+this.id);

        	},
        	ok(){
        		this.$router.push({name:'subdetail', params: {id: this.id}})
        	},
        	goback(){
        		this.$router.push({name:'subdetail', params: {id: this.id}})
        	},
        	loadGift(){
        		this.$http.get('/subdetail/'+this.id).then((response)=>{
                      if(response.data.code == 0 && response.data.data != undefined) {
                         this.gift = response.data.data
                      }
        		})
        	}
        }
    }
</script>
