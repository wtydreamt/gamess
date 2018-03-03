<?php
namespace app\index\model;

use think\Model;
use think\Db;

/**
 * description:公共Model,保存一些公共属性
 * @author wuyanwen
 */
class Common extends Model
{
    const STATUS = 1;
    static public function Message($code="200",$Message="成功",$data="",$display="yes"){
    	($code == "200")?$status = "true":$status = "flase";
    	$Msg=array("code"=>$code,"status"=>$status,"Message"=>$Message,"data"=>$data,"display"=>$display);
    	return json_encode($Msg);
    }

    //基于layui 的分页样式
    //$count 总条数 $render分页page，$page 当前页数 $strip 每页显示的条数
    static public function pageCss($count,$render,$page,$strip,$path,$where=""){
        $where=GetWhere($where);
        if($count<=$strip){
            return "";
        }
    	$start="<div class='layui-box layui-laypage layui-laypage-default'>";
    	$start=$start."<span class='layui-laypage-count'>共".$count."条</span>".$render;
    	$str="";
        $pagecount=ceil($count/$strip);
    	for($i=1;$i<$pagecount+1;$i++){
    		if($page == $i){
    			$str=$str."<option value=".$i." selected>$i 页</option>";
    		}else{
    			$str=$str."<option value=".$i.">$i 页</option>";
    		}
    	}
    	$start=$start."<span class='layui-laypage-limits'>到第<select onchange='ChagePage(this)' lay-ignore=''>".$str."</select><a id='a-href' data='$where' href='http://wty.guanj.com/admin.php/".$path."?p=$page$where'>确定</a></div>";
    	$script="<script>";
    	$script=$script."function ChagePage(obj){
				document.getElementById('a-href').setAttribute('href', '".$path."?p='+obj.value+document.getElementById('a-href').getAttribute('data'));
    		};";
    	$script=$script."</script>";
    	return $start.$script;
    }

    


}