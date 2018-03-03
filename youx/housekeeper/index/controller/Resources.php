<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Base;

use think\Config;

class Resources extends Controller 
{
    protected $menu = "edit";
    public function __construct()
    {
       parent::__construct();
    }   
    static public function path(){
        return Config::get('uploads.uploads');
    }
    static public function url(){
        return Config::get('uploads.domain_name');
    }    
    //上传文件配置
    static public function imgConfig(){
        //默认上传配置
        return $_config=array("size"=>1024*1024*2,"type"=>array("jpeg","png","gif","jpg"));
    }
    //tp5 内置图片上传 接口
    public function index($Imgmenu="edit",$file="file")
    {
        $msg=array();
        $file = request()->file($file);
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png'])->move(self::path()."/".$Imgmenu);
        if($info){
            $path    =$info->getSaveName();
            $fileName=$info->getFileName();
            $url     =self::url().self::path()."/".$Imgmenu."/".$path;
            $arr['code']=0;
            $arr['msg'] =self::path()."/".$Imgmenu."/".$path;
            $arr['data']['src']  =$url;
            $arr['data']['title']=$fileName;

        }else{
            $error=$file->getError();
            $arr['code']=1;
            $arr['msg'] =$error;
        } 
        echo json_encode($arr);
    }
    /*base64      图片处理
     *$base64_img 数据流 
     *$path       保存图片的目录路径
     *$config     文件上传配置  array  ["size"=>"限制文件上传的大小单位K","type"=>['jpeg','pnd','等图片格式']]
     *返回参数 $message 状态 status=ok 成功 data 成功后图片保存位置
     *                       status=no 失败 data 失败的原因
     */
    static public function base64_img($base64_img,$path,$config=""){
          $path=self::path().$path."/".date("Ymd");
          if(!is_dir($path)) 
          {
              mkdir($path, 0777, true);
          }  
          if($config){
            $typearray=$config['type'];
            $size = $config['size'];
          }else{
            $config=self::imgConfig();
            $typearray=$config['type'];
            $size = $config['size'];
          }
          $errogTY=implode('、',$typearray);
          $types=array("jpeg"=>"jpg","png"=>"png","jpg"=>"jpg","gif"=>"gif");    
          $message['status']="no";
          $message['data']="不支持的文件格式"; 
          if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result))
          {
                  $type = $result[2];
                  //检查文件类型
                  if(in_array($type,$typearray)){

                    $type=$types["$type"];
                    $img_name=time().rand(10000,99999);
                    $base64_len=strlen($base64_img);

                    $len = ($base64_len-($base64_len/8)*2);

                    $file_size = number_format(($len/1024/1024),2);//获取图片大小

                    if($len > $size){
                              $message['status']="no";
                              $sizes = $size/1024/1024;
                              $message['data']="上传文件超过限制大小,允许(<".$sizes."M)";   
                              return   $message;die;                   
                    }
                        $base64_img=base64_decode(str_replace($result[1], '', $base64_img));

                        $new_file = $path."/".$img_name.".".$type;

                        if (file_put_contents($new_file,$base64_img))
                        {
                              $message['status']="ok";
                              $message['data']=$new_file;

                        }else{

                              $message['status']="no";
                              $message['data']="未知原因文件保存失败";                        
                        } 

                  }else{
                        $message['status']="no";
                        $message['data']="不允许的上传类型该图片支持".$errogTY."格式";                    
                  }

          } 
          return $message;         
 }  
    //$Proportion  完整参数['type'=>1,"size"=>"2"];缩小原来的2倍
    //              ['type' = >2,"width"=>"","height"=>""] 自定义宽高
    //              $Proportion  不设置或者设置为空或错误时固定宽高缩小 到150  150
   static public function thumb($openpath,$savepath,$Proportion = "3"){
          $image = \think\Image::open($openpath);
          $width = $image->width();
          // 返回图片的高度
          $height = $image->height();
          $path='./Uploads/thumb/'.$savepath."/".date("Ymd");
          if(!is_dir($path)) 
          {
              mkdir($path, 0777, true);
          } 
          $name = time().".png";
          if(isset($Proportion['type']) && $Proportion['type'] == 1){
           $widths = $width/$Proportion['size'];
           $heights= $height/$Proportion['size'];
          }else if(isset($Proportion['type']) && $Proportion['type'] == 2){
          isset($Proportion['width'])  ? $widths  = $Proportion['type']:$widths = 150;
          isset($Proportion['height']) ? $heights = $Proportion['type']:$heights= 150;  
          }else{
          $widths = 150;
          $heights= 150;            
          }
          // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
          $res=$image->thumb($widths, $heights)->save($path."/".$name);   
          return   'Uploads/thumb/'.$savepath."/".date("Ymd")."/".$name;
   }

   public function delFiel($filename){
          if(file_exists($filename)){
            echo unlink($filename);
          }else{
            echo "0";
          }
          
   }

   public function uploads($base64_img="",$path=""){
          $config=self::imgConfig();
          $res=self::base64_img($base64_img,$path,$config);
          $res['url'] = self::url();
          echo json_encode($res);
   } 
}

