  <?php
namespace app\common\validate;

use think\Validate;

class Imgv extends Validate
{

    protected $rule =   [
        'shorts'  => 'number',
        'desc'    =>'require|chsAlpha|max:25',
        'link'    =>'url',
    ];
    
    protected $message  =   [
        'shorts.number'   => '序号必须是数字',
        'desc.require'	  => '图片简介不能为空',
        'desc.chsAlpha'   => '图片简介只能是汉字和字母',
        'desc.max'        => '图片简介不能超过25个字',
        'link.url'        => '请填写有效的url 比如（http://www.baidu.com）'
    ];
    
}