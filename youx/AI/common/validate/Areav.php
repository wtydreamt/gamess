<?php
namespace app\common\validate;

use think\Validate;

class Areav extends Validate
{

    protected $rule =   [
        'aname'  =>'require',
        'adesc'  =>'require',
    ];
    
    protected $message  =   [
        'aname.require'   => '小区名称不能为空',
        'adesc.require'	  => '小区简介不能为空',
    ];
    
}