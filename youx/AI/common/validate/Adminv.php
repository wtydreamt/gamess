<?php
namespace app\common\validate;

use think\Validate;

class Adminv extends Validate
{

    protected $rule =   [
        'name'  =>'require',
        'pwd'   =>'require|alphaNum|length:8,12',
    ];
    
    protected $message  =   [
        'name.require'   => '用户名不能为空',
        'pwd.require'	 => '密码不能为空',
        'pwd.alphaNum'	 => '密码必须是数字和字母组合',
        'pwd.length'     => '密码长度 8-12位',
    ];
    
}