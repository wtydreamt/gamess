<?php
namespace app\index\controller;

use app\index\controller\Base;
use think\Request;
use think\Loader;
use app\common\validate\Adminv;
use app\index\model\Admin;
use app\index\model\Role;
class User extends Base 
{
    protected $getData;
    public function __construct(Request $Request)
    {
          parent::__construct();
          $this->getData = Request::instance();
    }			
    public function index()
    {
    	$arr=$this->getData->param(true);
    	if(empty($arr)){
    		$admin=model("admin")->select();
    		return $this->fetch("adminUser",['admin'=>$admin]);
    	}else{
    		$message=array("status"=>"400","msg"=>"添加失败");
			$validate = Loader::validate('Adminv');
			if($validate->check($arr)){
				if(model("admin")->get(['name'=>$arr['name']])){$message['msg']="用户名存在"; echo json_encode($message);die;}
				$arr['pwd'] = md5($arr['pwd']);
				$arr['addtime'] = date("Y-m-d H:i:s");
				$Admin=new Admin($arr);
    			if($Admin->allowField(true)->save()) $message=array("status"=>"200","msg"=>"添加成功");
			}else{
			    $message['msg']=$validate->getError();
			}
			echo json_encode($message);
    	}
    }
    public function addArticle(){
    	return $this->fetch("addArticle");
    }
    public function group(){
    	$arr=$this->getData->param(true);
    	if(empty($arr)){
    		$role=model("role")->select();
    		return $this->fetch("group",['role'=>$role]);
    	}else{
    		$message=array("status"=>"400","msg"=>"添加失败");
			$validate = new \think\Validate(['rolename'=>'require','shors'=>'number'],["rolename.require"=>"组别名称不能为空","shors.number"=>"序号必须是数字"]);
			if (!$validate->check($arr)) {
			   $message['msg'] = $validate->getError();
			}else{
				$Role=new Role($arr);
    			if($Role->allowField(true)->save()) $message=array("status"=>"200","msg"=>"添加成功");
			}  
			echo json_encode($message);  		
    	}
    }

    public function adminDel($id = ""){
           $res=model("admin")->destroy($id);
           if($res){
            $message['status']="200";
           }else{
            $message['status']="400";
            $message['msg']   ="删除失败";
           }
           echo json_encode($message);
    }

    public function adminSave(){
            $arr=$this->getData->param(true);
            $message=array("status"=>"400","msg"=>"添加失败");
            $validate = Loader::validate('Adminv');
            if($validate->check($arr)){
                if(model("admin")->get(['name'=>$arr['name']])){$message['msg']="用户名不可用"; echo json_encode($message);die;}
                $arr['pwd'] = md5($arr['pwd']);
                $arr['addtime'] = date("Y-m-d H:i:s");
                $id = $arr['id'];
                unset($arr['id']);
                if(model("admin")->allowField(true)->save($arr,['id'=>$id])) $message=array("status"=>"200","msg"=>"修改成功");
            }else{
                $message['msg']=$validate->getError();
            }
            echo json_encode($message);
    }

    public function userNode(){
           $arr=$this->getData->param(true);
           $id = $arr['r_id'];
           unset($arr['r_id']);
           $res=model("role")->allowField(true)->save($arr,['r_id'=>$id]);
           if($res){
            $message['status'] = "200";
            $message['msg']    = "保存成功";
           }else{
            $message['status'] = "400";
            $message['msg']    = "保存失败";
           }
           echo json_encode($message);die;
    }

    public function adminRole(){
            $arr=$this->getData->param(true);
            $message=array("status"=>"400","msg"=>"修改失败");
            $id = $arr['id'];
            unset($arr['id']);
            if(model("admin")->allowField(true)->save($arr,['id'=>$id])) {$message=array("status"=>"200","msg"=>"修改成功");}
            echo json_encode($message);die;
    }
}