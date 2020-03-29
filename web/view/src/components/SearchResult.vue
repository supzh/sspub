<template>
    <div class="tab-pane in active" id="search-result">      
        <ol class="breadcrumb"><li> <button @click="closeSearch" class="btn btn-warning">返回</button> 
        “{{keyword}}”的搜索结果 ({{result.allNum}}个)</li></ol>
                  	
      	<ul class="searchbox">      		
      		<template v-if="state == 2">
      			<template v-if="result.allNum !=0">      				      			
      				<li v-if="result.user_gift_instance.length!=0">
      				
      				<table class="table table-bordered table-striped table-hover table-responsive">
			      		<caption>搜索到的订阅信息：</caption>
			            <thead>
			            <tr>
			           		<th>礼品</th>			           
			                <th>有效期至</th>
			                 <th>每月流量信息</th>
			                <th>订阅时间</th>
			            </tr>
			            </thead>
			            <tbody>			             
			            <tr v-for="v in result.user_gift_instance">
			             	  <td>{{v.name}}</td>				             
				                <td>{{v.expired_at}}</td>				               
				                <td>
				                {{v.transfer_enable}} bytes					          
				                </td>
                            <td>{{v.created_at}}</td>		               
			            </tr>			            
			            </tbody>
			        </table>      					
      				</li>      				      				      				
      			</template>
      			<span class="noresult" v-else>没有搜索到相关内容。</span>
      		</template>
      		<li v-else-if="state == 1">加载中</li>
      	</ul>
      	
    </div>
</template>
<style>
    body{
        //background-color:#ff0000;
    }
    ul.searchbox {
    	list-style:none;
    	margin-left: 0;
    	padding-left: 15px;   
    	
    }
    ul.searchbox .noresult {
    	color:#666;
    }
</style>
<script>
    export default{
      
        props: ['keyword', 'result', 'state'],
       
        methods: {        	        	        	
        	closeSearch: function () {
        	      this.$emit('search')
        	}        	            	             
        }
    }
</script>
