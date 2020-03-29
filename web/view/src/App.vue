<template>
    <div>
    	<div id="top-alert" class="alert alert-danger"  style="text-align:center;display:none;z-index:99999;width:100%;margin-bottom:0;top:0;left:0;right:0;"></div>
        
        
        <welcome-component v-if="isWelcome"></welcome-component>   
        <template v-else>
        <main-component v-if="gdata.isLogin"></main-component>        
        <login-component v-if="gdata.isLogin == false && isStateChecked == true"></login-component>
        </template>        
    </div>
</template>
<style>
    body{
        //background-color:#ff0000;
    }
</style>
<script>
    import MainComponent from './components/Main.vue'
    import LoginComponent from './components/Login.vue'
    import WelcomeComponent from './components/Welcome.vue'
    import Storage from './storage.js'
    
    export default{
        data(){
            return {
            	gdata: gdata,
            	isStateChecked: false,
            }
        },computed:{
        	isWelcome(){
        		if( this.$route.name == 'welcome') {
        			return true
        		} else {
        			return false
        		}
        		
        	}
        },
        created(){
        	var vm = this        
        	gdata.bus.$on('alert', function(msg, time = 0){
        		 $('#top-alert').html(msg);
                 $('#top-alert').slideDown(300);                           
                 if(time != 0) {
                     setTimeout(function(){
                         $('#top-alert').slideUp(300);
                     }, time)
                 }
        	})
        	
        	gdata.bus.$on('close-alert', function(){        		
                 $('#top-alert').hide(300);                                                                                     
        	})
        	
            if(gdata.isLogin == false) {
                this.$http.get('/loginstate').then((response)=>{
                	this.isStateChecked = true
                	Storage.set('sessid', response.data.sessionId)
                	if (response.data.userId) {                		
                        gdata.isLogin = true
                        gdata.userId = response.data.userId                     
                        gdata.userName = response.data.userName
                        gdata.type = response.data.type
                        gdata.email = response.data.email     
                    }
                })
            }
        },
        components:{
            'main-component':MainComponent,
            'login-component':LoginComponent,
            'welcome-component':WelcomeComponent
        }
    }
</script>
