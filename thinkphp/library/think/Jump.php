<?php
namespace think;

class Jump{
	
	//参数  $arr 数组  type 提示类型 404  页面未找到  2弹出提示信息 3直接跳转无提示信息
	//content 提示信息 url 跳转转路径

    public function __construct($arr)
    {
	    if($arr['type'] == "404"){
    	echo '<link rel="stylesheet" href="/static/admin/layui/layui/css/layui.css" media="all" /><div style="text-align: center; padding:11% 0;">
		<i class="layui-icon" style="line-height:20rem; font-size:20rem; color: #393D50;">&#xe61c;</i>
		<p style="font-size: 20px; font-weight: 300; color: #999;">我勒个去，页面被外星人挟持了!</p>
	    </div>';die;	    	
	    }

	    if($arr['type'] == "2"){
        echo '<script type="text/javascript" src="/static/admin/layui/layui/layui.js"></script>'."<script>
		layui.use(['layer'], function(){
		var $ = layui.jquery ,layer = layui.layer;
		              layer.open({  
		                      content: '".$arr['content']."',  
		                      btn: ['确认'],  
		                      yes: function(index, layero) {  
		                          window.location.href='".$arr['url']."';  
		                      }
		                      ,  
		                      cancel: function() {  
		                          window.location.href='".$_SERVER["HTTP_REFERER"]."'; 
		
		                      }  
		                  })
		                  });              
        </script>";die;	    	
	    }
	    if($arr['type'] == "3"){
	    	if(isset($arr['returns'])){
	    		header("Location: ".$_SERVER["HTTP_REFERER"]);exit;
	    	}else{
	    		header("Location: ".$arr['url']);exit;
	    	}
	    }
    }

}