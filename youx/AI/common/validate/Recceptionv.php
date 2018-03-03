<?php
namespace app\common\validate;

use think\Validate;

class Recceptionv extends Validate
{

    protected $rule =   [
    	'm_name'  => 'require',
        'shorts'  => 'require|number'
    ];
    
    protected $message  =   [
        'm_name.require'	  => '栏目名称不能为空',
        'shorts.number'       => '序号必须是数字'
    ];
    
}