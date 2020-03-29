<template>

    <div class="m">
       <nav v-if="isSearch == false" class="navclass navbar navbar-default" role="navigation">
                <div class="container-fluid container" v-if="isAlone == false">                
                    <div class="navbar-header">
                         <img class="visible-xs" src="img/logo_in.png" style="width:59px;height:37px;margin-top:5px;margin-right:0;float:left;">

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                    
                        <img class="hidden-xs" src="img/logo_in.png" alt="SSPUB" style="clear:both;font-size:20px;color:#1e88e5;width:59px;height:37px;margin-top:5px;margin-right:10px;float:left;">
                        <ul class="nav navbar-nav">
                            <li></li>
                            <router-link tag="li" :to="{name:'home'}" data-toggle="tab"><a>我的订阅</a></router-link>
                            <router-link tag="li" :to="{name:'mybill'}" data-toggle="tab"><a>账单</a></router-link>
                            <router-link tag="li" :to="{name:'support'}" data-toggle="tab"><a>支持</a></router-link>
                            
                              <!-- <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        我的账户
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <router-link tag="li" data-toggle="tab"  :to="{name:'profile'}" ><a>个人信息</a></router-link>
                                        <router-link tag="li" data-toggle="tab"  :to="{name:'authentication'}"><a>账户安全</a></router-link>
                                        <router-link tag="li" data-toggle="tab"  :to="{name:'settingsnotifications'}"><a>通知设置</a></router-link>
                                    </ul>
                                </li>-->
                             <router-link tag="li" :to="{name:'admincenter'}" v-if="gdata.type==2"><a>管理中心</a></router-link>
                        </ul>
                        
                                     
                           <ul class="nav navbar-nav   navbar-right">                        
                         	<li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span style='font-size:14px;font-weight:normal;font-family: "Raleway", Helvetica, Arial, sans-serif;'>
                                    
                                    {{userName}}</span>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                     <router-link tag="li" data-toggle="tab"  :to="{name:'profile'}" ><a>个人信息</a></router-link>
                                        <router-link tag="li" data-toggle="tab"  :to="{name:'authentication'}"><a>账户安全</a></router-link>
                                        <router-link tag="li" data-toggle="tab"  :to="{name:'settingsnotifications'}"><a>通知设置</a></router-link>
                                    <li style="cursor:pointer;border-top:1px solid #ddd;margin-top:5px;padding-top:5px;" @click="logout"><a>注销</a></li>
                                </ul>    
                                 
                         </li>
                        </ul>
                        
                           <form class="navbar-form navbar-right" role="search">
                         	<div class="input-group input-group-sm" style="margin-top:3px">
                         	  	<input style='display:none' />
				                <input style="color:#666;width:150px;background:#fff;border:1px #eee solid;border-radius:3px 0 0 3px;box-shadow:none;" placeholder="请您输入搜索内容..." v-focus @keyup.enter="contentSearch"  v-model="searchKeyword" type="text" class="form-control">				                
				                 <span class="input-group-btn" >
				                    <button style="background:#eee;border:1px solid #eee;" class="btn" :class="searchbtnclass" @click="contentSearch" type="button">{{searchbtntext}}</button>
				                </span>
				                 </div>
                       	 </form>
                    </div>
                </div>
            </nav>
        <div v-if="isSearch == true" style="width:100%;height:60px;"></div>
        <div  style="margin-top:-10px" id="myTabContent"class="container tab-content">   
         	  <transition name="slide-fade" mode="out-in"> 
          	 <div class="content-box">                        	
            	<search-result :keyword="searchKeywordConfirm" :state="searchState" :result="searchResultData"  @search="closeSerach" v-if="isSearch"></search-result>
           		   <router-view @openAlone="openAlone" @closeAlone="closeAlone" v-else ></router-view>     		
           	 </div>
              </transition>
        </div>
         <div style="text-align:center;color:#ccc;padding:50px 0 80px 0;font-size:9px"> ©2017 sspub</div>
    </div>
</template>
<style>
.m {background:#fff;}

.content-box {}

.breadcrumb {margin:-15px;margin-bottom:15px;border-radius:0px;color:#333;background:#fff;margin-top:20px;font-size:30px;}

.dropdown-menu {border-radius:0;box-shadow:1px 1px 3px #dfdfdf;border:1px solid #ccc;}
.div-line {height:10px;margin-left:-15px;margin-right:-15px;border-bottom:1px dotted #ddd;}
table {background:#fff;}
.navclass {background:#fff;z-index:100;width:100%;border-radius:0;border:none;border-bottom: 1px solid #edf0f2;}
.navbar-default .navbar-nav>.active>a, .navbar-default .navbar-nav>.active>a:focus, .navbar-default .navbar-nav>.active>a:hover {
background:#fff;border-bottom:2px solid #1e88e5;
color:#1e88e5;font-weight:bold;
}
.group-menu-btn {float:left;background:#fafafa;width:200px;color:#666;margin:5px;text-align:left;height:55px;line-height:15px;border:none;border-radius:0;border-bottom:2px solid #ccc}
.btn-default {color:#666}


.slide-fade-enter-active {
  transition: all .1s ease;
}
.slide-fade-leave-active {
  transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-active {
  transform: translateX(20px);
  opacity: 0;
}


.modal-backdrop {}
.modal-header{border:none;}
.modal-footer{border:none;}
.modal-title{font-size:16px;color:#666;font-weight:normal}
.modal-dialog .modal-content { box-shadow:none;}

</style>
<script>
	import SearchResult from "./SearchResult.vue"
    export default{
        data: function(){
            return{
                gdata: gdata,
                userType: gdata.type,
                isAdminUser: 2,
                userName: gdata.userName,
                searchKeyword: '',
                isSearch: false,
                isAlone: false,
                searchbtntext: '搜索',
                searchbtnclass: 'btn-default',
                searchKeywordConfirm: '',
                searchResultData: [],
                searchState: 0,
                manualLink: gdata.serverside + '/manual',
            }
        },
        created(){
        	var vm = this
        	gdata.bus.$on('logout', function(){
        		vm.logout()
        	})      
        },
        components:{
        	"search-result":SearchResult,
        },
        methods:{        		
        	downloadManual(){
        		window.open(this.manualLink)
        	},
        	closeSerach: function () {
        		this.isSearch = false
    			this.searchbtntext = '搜索'
            	this.searchbtnclass = 'btn-default'
       	    },
       	    openAlone(){
       	    	this.isAlone = true	
       	    },
       	    closeAlone(){
       	    	this.isAlone = false
       	    },
        	contentSearch(){        		        		 		
        		if(this.searchKeyword == '') {
        			swal("请您输入搜索内容")
        			return
        		}        		
        		this.searchKeywordConfirm = this.searchKeyword
        		this.isSearch = true
        		this.searchState = 1
        		this.$http.post('/search',{keywords:this.searchKeywordConfirm}).then((response)=>{                	
                    if(response.data.code == 0) {
                    	this.searchState = 2
                    	this.searchResultData = response.data.data                    	
                    }
                })
        	},
            logout(){
                this.$http.get('/logout').then((response) => {                    
                    if(response.data.code == 0) {
                        gdata.isLogin = false
                        gdata.userId = null
                        gdata.userName = null
                        gdata.email = null
                        gdata.type = null
                    }
                })
            }
        }
    }
</script>
