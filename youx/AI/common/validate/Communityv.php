<?php
namespace app\common\validate;

use think\Validate;

class Communityv extends Validate
{

    protected $rule =   [
        'r_name'   => 'require|chs',
    ];
    
    protected $message  =   [
        'r_name.require'	    => '地区名不能为空',
        'r_name.chs'            => '地区名为汉字',
    ];
    
}