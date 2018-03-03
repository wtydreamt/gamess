<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Base;
use think\Request;
use think\Cookie;
class Login extends Controller 
{
    protected $getData;
    public function __construct(Request $Request)
    {
       parent::__construct();
       $this->getData = Request::instance();
    }		
	public function index(){

		$arr=$this->getData->param(true);
		if(empty($arr)){
			return $this->fetch("login");
		}else{
	       $message['status'] = "400";
	       $message['msg'] = "用户名或密码错误";				
		   $rule =   [
		        'user'   => 'require',
		        'pwd'    => 'require',
		        'code'    =>'require|captcha',
		    ];
		    
		    $meg =   [
		        'user.require'	    => '用户名不能为空',
		        'pwd.require'       => '密码不能为空',
		        'code.require'      => '验证码不能为空',
		        'code.captcha'      => '验证码不正确'
		    ];
		    $validate = new \think\Validate($rule,$meg);
		    if (!$validate->check($arr)) {
                 $message['status'] = "400";
                 $message['msg'] = $validate->getError();
                 echo json_encode($message);die;
              }else{
              	 $arr=model("admin")->where(['name'=>$arr['user'],"pwd"=>md5($arr['pwd'])])->find();
              	 if($arr){
              	 	Cookie::set("admin",$arr['id']);
              	 	$data['logintime'] = date("Y-m-d H:i:s");
              	 	$data['ip']        = getIp();
              	 	model("admin")->save($data,['id'=>$arr['id']]);
              	 	$message['status'] = "200";
              	 }else{
              	 	$message['status'] = "400";
              	 	$message['msg'] = "用户名或密码错误";
              	 }
              	 echo json_encode($message);die;
              }		
              echo json_encode($message);die;	
		}
		
	}

	public function LoginOut(){
		   Cookie::set("admin","");
		   header("Location: login");exit;
	}
}

