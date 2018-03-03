<?php
namespace app\common\validate;

use think\Validate;

class Register extends Validate
{

    protected $rule =   [
    	'nickname'  => 'require|alphaNum|length:4,24',
        'pwd'       => 'require|alphaNum|length:6,12',
        'phone'     => 'require|max:11|number',
        'code'      => 'require',

    ];
    
    protected $message  =   [
        'nickname.require'	  => '用户名不能为空',
        'nickname.alphaNum'   => '用户名数字或字母',
        'nickname.length'	  => '用户名长度4-24',
        'pwd.require'         => '密码不能为空',
        'pwd.alphaNum'        => '数字或字母',
        'phone.require'       => '手机号不能为空',
        'phone.max'           => '11位的手机号',
        'phone.number'        => '手机号必须是数字',
        'pwd.length'          => '密码长度6-12',
        'code.require'        => '验证码不能为空',
    ];
    
}