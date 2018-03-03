<?php
namespace app\index\model;
use app\index\model\Common;
class Gamelist extends Common
{
    protected $table = 'gamelist';
    protected $array = '';

    public function getCidAttr($value)
    {
        $status=array();
        $arr=model("menu")->field('id,MenuName')->select()->toArray();
        foreach($arr as $key=>$val){
            $status[$val['id']]=$val['MenuName'];
        }
        return $status[$value];
    } 

    public function getlist($strip,$page,$where){
    	 $data=$this->order('sorts', 'asc')
    	 ->order('id', 'asc')
         ->field('id,content,cid,downloads,title,sorts,grade,img_path')
    	 ->where($where)->paginate($strip,false,["page"=>$page,"where"=>$where,"path"=>"IndexList"]);
         $render=$data->render();
         $count=$this->where($where)->count();
         $render=self::pageCss($count,$render,$page,$strip,'IndexList',$where);
         return array("data"=>$data,"render"=>$render);    	
    }   
}