<?php
namespace app\index\model;
use app\index\model\Common;
class Label extends Common
{
    protected $table = 'label';

    public function getSortsAttr($value)
    {
        $status=array("1"=>"第1名","2"=>"第2名","3"=>"第3名");
 
        return $status[$value];
    }    
}