<template>
    <div>        
        
      <div class="container" style="width:900px;">
        
                    <div class="row">

                        <div class="col-sm-12"  style="margin-bottom:50px;">
                           
                            <h5 style="text-align:center;color:#1e88e5;font-family:'avenir';font-weight:normal;font-size:20px;margin-top:80px;">
                            
                             <img src="img/logo.png" style="width:196px;height:49px;margin:10px;" >
                                   </h5>
                              <h5 style="text-align:center;color:#1e88e5;font-family:'avenir';font-weight:normal;font-size:20px;margin-top:20px;line-height:32px;">
                             订阅VULTR的在线Shadowsocks服务器帮助您高速访问海外网站...
                            </h5>
                        </div>
                    </div>
                    <form class="form-horizontal" role="form">						                       
                        
                        <div class="form-group">
                            <div class="col-sm-12" style="text-align:center;">
                                <button type="button" style="width:120px;height:50px;font-size:16px;background:#1e88e5;border:1px solid #1e88e5;" class="btn btn-primary" @click="gologin">注册 / 登录</button>
							</div>
                        </div>                                                                         
                        
                    </form>             
               </div></div>

  
</template>
<style>
    body{
        background-color:#fff;;        
    }
    .input {border-radius:3px;line-height:50px;height:50px;border:1px solid #ebebeb;color:#333;box-shadow:none;padding-left:15px;}
    .control-label {color:#666;font-weight:normal}
    .login-user-type-list {list-style:none;margin-left:0;padding:0;}
    .login-user-type-list li {cursor:pointer;float:left;margin-right:10px;font-size:16px;color:#aaa;padding:2px 5px;margin:0;}
    .login-user-type-list li.active {color:#666;background:#fff;border-bottom:2px solid #ddd;}
    .login-box {margin-top:15px;}
</style>
<script>
	import Storage from './../storage.js'
    export default{
        data(){
            return {
                gdata:gdata,            
            }
        },
        created(){
            if(gdata.isLogin) {
                this.$router.push({name:'home'})
            }
        },
        methods:{	       
        	gologin(){
        		this.$router.push({name:'home'})
        	}                	
        }
    }
</script>
