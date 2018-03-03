<?php
namespace app\common\validate;

use think\Validate;

class Gamev extends Validate
{

    protected $rule =   [
        'title'       =>'require',
        'ios'         =>'url|require',
        'android'     =>'url|require',        
        'sorts'       =>'require|number',
        'downloads'   =>'require|number',
        'content'     =>'require',
        'type'       =>'require',
    ];
    
    protected $message  =   [
        'title.require'      => '标题不能为空',
        'ios.url'         =>'ios下载链接格式不正确',
        'ios.require'         =>'ios下载链接不能为空',
        'android.url'     =>'android下载链接格式不正确',
        'android.require'         =>'android下载链接不能为空',         
        'sorts.require'      => '排序不能为空',
        'sorts.number'       => '排序必须是数字',
        'downloads.require'	 => '下载量不能为空',
        'downloads.number'   => '下载量必须是数字',
        'type.require'       => '游戏类型不能为空',
        'content.require'    => '内容不能为空',
    ];
    
}