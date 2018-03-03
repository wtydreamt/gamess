<?php
namespace app\index\controller;

use app\index\controller\Base;


class Index extends Base 
{
    public function index()
    {
        $wei=new \think\Wei\Wei();
        // $url="http://".$_SERVER['HTTP_HOST'];
        // $array=$wei->fenxiang($url);
      
    	$menu=model("menu")->where(['Pid'=>0])->field("id,MenuName")->select()->toArray();
		return $this->fetch("index",['menu'=>$menu]);
    }

    public function info($id = "")
    {
    	$info=model("gamelist")->where(['id'=>$id])->find();
        if($info['album']){
          $info['album']=explode(",", $info['album']);
        }else{
          $info['album']="";
        }
		return $this->fetch("info",['info'=>$info]);
    }
}
