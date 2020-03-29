export default {
	   
		getMode(){			
			if(typeof(localStorage) != "undefined") {
				return 1 //localstorage
			}			
			return 2 //cookie
			
		},		
		set(name,value){			
			switch(this.getMode()) {
				case 1:
					localStorage.setItem(name, value)
					break
				case 2:
				    var Days = 30;
			        var exp = new Date();
			        exp.setTime(exp.getTime() + Days*24*60*60*1000);
			        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
					break
			}			
	    },
	    get(name){	    	
	    	switch(this.getMode()) {
				case 1:
					return localStorage.getItem(name)
					break
				case 2:
					 var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
				     if(arr=document.cookie.match(reg))
				        return unescape(arr[2]);
				     else
				        return null;
					break
	    	}
	    },
	    del(name) {
	    	switch(this.getMode()) {
				case 1:
					localStorage.removeItem(name)
					break
				case 2:
				    var exp = new Date();
			        exp.setTime(exp.getTime() - 1);
			        var cval=getCookie(name);
			        if(cval!=null)
			        	document.cookie= name + "="+cval+";expires="+exp.toGMTString();
					break
	    	}
	      
	    }	    
}