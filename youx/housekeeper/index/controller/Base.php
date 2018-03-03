<?php
namespace app\index\controller;
use think\Controller;
use app\admin\controller\Auth;
use think\Config;
use think\Cookie;
header("Content-type:text/html;charset=utf-8");
class Base extends Controller
{
    
    public function __construct()
    {
       parent::__construct();
       $this->file_path=Config::get('uploads.uploads');
       $this->isLoging();       
    }
    public function isLoging(){
        if(!Cookie::get('admin')){
            echo "<script>window.parent.location.href='/admin.php/login';</script>";
        }else{
            return true;
        }
    }
}