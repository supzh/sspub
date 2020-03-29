import Storage from './storage.js'
export default {
    install(Vue, options){
        // 注册一个全局自定义指令 v-focus
        Vue.directive('focus', {
            // 当绑定元素插入到 DOM 中。
            inserted: function (el) {
                // 聚焦元素
                el.focus()
            }
        })
        Vue.http.options.emulateJSON = true
        Vue.http.interceptors.push((request, next)  => {
        	
        	if(request.url.substr(0,9) != '/direct') {        	
        		request.url =  gdata.serverhost + '/api' + request.url
        	} else {
        		request.url =  gdata.serverhost + request.url.replace('/direct','')
        	}
        	var sessid
        	if(sessid = Storage.get('sessid')) {
        		request.params.sessid = sessid        		
        	}        	        	        	
            $("#loading").show();
            next((response) => {
                $("#loading").hide();
                if(response.ok != true) {
                	gdata.bus.$emit('alert', '网络连接已断开')
                	return
                } else {
                	gdata.bus.$emit('close-alert')
                }
                
                if(response.data.code == 1) {
                    return response
                }
                if(response.data.msg=='') {
                    return response;
                }

                switch(response.data.code) {
                    case 0:
                    	 return response                             
                    default:                	                            
                        swal({title:'消息',confirmButtonText:'关闭',text:response.data.msg})
                        break;
                }
                return response
            });
        });

        Vue.prototype.$getPhoneModel = function(){
            var agent = navigator.userAgent.toLowerCase() ;
            var regStr_ie = /msie [\d.]+;/gi ;
            var regStr_ff = /firefox\/[\d.]+/gi
            var regStr_chrome = /chrome\/[\d.]+/gi ;
            var regStr_saf = /safari\/[\d.]+/gi ;
            //IE
            if(agent.indexOf("msie") > 0)
            {
                return agent.match(regStr_ie) ;
            }
            //firefox
            if(agent.indexOf("firefox") > 0)
            {
                return agent.match(regStr_ff) ;
            }
            //Chrome
            if(agent.indexOf("chrome") > 0)
            {
                return agent.match(regStr_chrome) ;
            }
            //Safari
            if(agent.indexOf("safari") > 0 && agent.indexOf("chrome") < 0)
            {
                return agent.match(regStr_saf) ;
            }
        }
        Vue.prototype.$alert = {
            show(msg, type='warning', time=0){
                $('#alert').removeClass('alert-warning')
                $('#alert').removeClass('alert-danger')
                $('#alert').removeClass('alert-info')
                $('#alert').removeClass('alert-success')
                $('#alert').addClass('alert-'+type)
                $('#alert').html(msg);
                $('#alert').slideDown(300);
                if(time != 0) {
                    setTimeout(function(){
                        $('#alert').slideUp(300);
                    }, time)
                }
            },
            hide(time=0) {
                if(time !=0) {
                    setTimeout(function(){
                        $('#alert').slideUp(300);
                    }, time)
                } else {
                    $('#alert').slideUp(300);
                }
            }
        }
    },

}