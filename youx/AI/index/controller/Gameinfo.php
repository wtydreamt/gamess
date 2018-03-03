<?php
namespace app\index\controller;

use app\index\controller\Base;


class Gameinfo extends Base 
{
    public function index()
    {
    	$menu=model("menu")->where(['Pid'=>0])->field("id,MenuName")->select()->toArray();

		return $this->fetch("index",['menu'=>$menu]);
    }


}
