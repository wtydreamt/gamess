<?php
namespace app\index\controller;
use think\Controller;
use app\admin\controller\Auth;
use think\Config;
use think\Cookie;
use think\Session;
header("Content-type:text/html;charset=utf-8");
class Base extends Controller
{
    
    public function __construct()
    {
       parent::__construct();
       $this->file_path=Config::get('uploads.uploads');
       
       $controller = request()->controller();
       $action     = request()->action();       
    }
    public function isLoging(){
           $user=Session::get("user");
           if($user){
             return $user;
           }else{
             return false;
           }
    }
    public function Verification($data,$msg,$arr){
        $validate = new \think\Validate($data,$msg);
        if (!$validate->check($arr)) {
            return $validate->getError();
        }else{
            return "";
        }
    }
}