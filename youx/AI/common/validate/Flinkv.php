<?php
namespace app\common\validate;

use think\Validate;

class Flinkv extends Validate
{

    protected $rule =   [
        'fname'  => 'require',
        'link'    =>'require|url',
        'shors'    =>'require|number',
    ];
    
    protected $message  =   [
        'fname.require'	  => '友情链接名称不能为空',
        'link.require'    => '友情链接不能为空',
        'link.url'        => '请填写有效的url 比如（http://www.baidu.com）',
        'shors.require'   => '排序不能为空',
        'shors.number'    => '序号必须是数字',
    ];
    
}