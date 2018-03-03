<?php
namespace app\common\validate;

use think\Validate;

class Forumv extends Validate
{

    protected $rule =   [
        'title'   => 'require|max:75',
        'Keyword' => 'require',
        'content' => 'require',
        'code'    =>'require|captcha',
    ];
    
    protected $message  =   [
        'title.require'	    => '标题不能为空',
        'title.max'         => '标题不能超过25个字',
        'Keyword.require'   => '关键字不能为空不能为空',
        'content.require'   => '帖子内容不能为空',
        'code.require'      => '验证码不能为空',
        'code.captcha'      => '验证码不正确'
    ];
    
}