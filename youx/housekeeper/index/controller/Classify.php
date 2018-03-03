<?php
namespace app\index\controller;
use think\Request;
use app\index\controller\Base;
use think\Db;

class Classify extends Base 
{
    protected $getData;
    protected $typearray;

    public function __construct(Request $Request)
    {
       parent::__construct();
       $this->typearray=array("size"=>1024*1024,"type"=>array("jpeg","png"));
       $this->getData = Request::instance();
    }	
    public function index()
    {
		
    }
    public function menu()
    {
        $meun=model("menu")->where(['Pid'=>0])->field("id,MenuName")->select();
		return $this->fetch("menu",['meun'=>$meun]);
    } 
    public function MenuSave(){
        $arr=$this->getData->param(true);
        $id = $arr['id'];
        unset($arr['id']);
        if(model("Menu")->allowField(true)->save($arr,["id"=>$id])){
        $message=array("status"=>"200","msg"=>"修改成功");
        }else{
        $message=array("status"=>"400","msg"=>"修改失败");
        }

        echo json_encode($message);
         
    }

    public function Label(){
        $label=model("label")->select();
        return $this->fetch("label",['label'=>$label]);
    }

    public function LabelInfo($id=""){
        $info=model("label")->get($id);
        return $this->fetch("labelinfo",['info'=>$info]);
    }
    public function LabelSave(){

           $message=array("status"=>"400","msg"=>"修改失败");


           $arr=$this->getData->post();

           if(!empty($arr)){

              if(isset($arr['file'])){
                $imgRes=Resources::base64_img($arr['file'],"/label",$this->typearray);
                if($imgRes['status']=="ok"){
                  $arr['label_img']=$imgRes['data'];
                  unset($arr['file']);
                }else{
                  $message['msg']=$imgRes['data'];
                  echo json_encode($message);die;
                }         
              }    
             if(model("label")->allowField(true)->save($arr,$arr['id'])){
              $message['status'] = "200";
             }
           }
           echo json_encode($message);  
    }

    public function del($id=""){
           if(!$id) {echo json_encode(array("status"=>"400","msg"=>"必要参数不能为空"));die;};
           $label=model("label");
           if($label::destroy($id)){
              $message['status'] = "200";
           }else{
              $message['status']    = "400";
              $message['msg'] = "删除失败";
           };
           echo json_encode($message);
    } 

    public function LabelAdd(){
        $arr=$this->getData->post();
        if(empty($arr)){
            return $this->fetch("labeladd");
        }else{
            $message=array("status"=>400,"msg"=>"添加失败");
            $res=model("label")->where(array("sorts"=>$arr['sorts']))->find();
            if($res){
                $message['msg'] = "该标签已经存在";
                echo json_encode($message);die;
            }            
            $imgRes=Resources::base64_img($arr['file'],"/label",$this->typearray);
                if($imgRes['status'] == "ok"){
                    $arr['label_img'] = $imgRes['data'];

                    if(model("label")->allowField(true)->save($arr)){
                        $message['status']=200;
                        $message['msg']   ="添加成功";
                    }                 
                }else{
                        $message['msg']   =$imgRes['data'];
                }
           
            echo json_encode($message);die;            
        }
        
    }        
}
