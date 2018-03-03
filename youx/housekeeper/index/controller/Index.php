<?php
namespace app\index\controller;
use app\index\model\Gamelist;
use app\index\controller\Base;
use think\Db;
use think\Request;
use app\common\validate\Gamev;
use think\Loader;
class Index extends Base 
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
		return $this->fetch("index",['title'=>"游戏排名管理"]);
    }
    
    public function IndexList(){
        $where="";
        (input('?get.p')) ? $page=input('p') : $page=1;  
        $gamelist=model("gamelist");
        $data=$gamelist->getlist("5",$page,$where);

    	return $this->fetch("indexlist",['gamelist'=>$data['data'],"render"=>$data['render'],"page"=>$page]);
    }

    public function AddGame(){
        $arr=$this->getData->post();

        if(empty($arr)){
            $meun=model("menu")->where(['Pid'=>0])->field("id,MenuName")->select();
            return $this->fetch("addgame",['menu'=>$meun]);
        }else{
            $message=array("status"=>400,"msg"=>"添加失败");

            $validate = Loader::validate('Gamev');
            $arr['content'] = $_POST['content'];
            if($validate->check($arr)){

            $imgRes=Resources::base64_img($arr['file'],"/images",$this->typearray);
                if($imgRes['status'] == "ok"){

                    $arr['img_path'] = $imgRes['data'];

                    $Gamelist=new Gamelist($arr);
                    if($Gamelist->allowField(true)->save()){
                        $message['status']=200;
                        $message['msg']   ="添加成功";
                    }                 
                }else{
                        $message['msg']   =$imgRes['data'];
                }
            }else{

            $message['msg']=$validate->getError();

            }            
            echo json_encode($message);die;
        }
    	
    }

    public function setIncDel($type="",$id=""){
        if($type==1){
           echo model("gamelist")->where(['id'=>$id])->setInc('sorts',1);
         }elseif($type==2){
           echo model("gamelist")->where(['id'=>$id])->setDec('sorts',1);
         }
    }

    public function Main(){

    }

    public function Edit($id="",$page=""){
        $info=model("gamelist")->get($id);
        $meun=model("menu")->where(['Pid'=>0])->field("id,MenuName")->select();
        if($info['album']){
          $img=explode(",", $info['album']);
        }else{
          $img="";
        }
        

        return $this->fetch("edit",['menu'=>$meun,"info"=>$info,"page"=>$page,"img"=>$img]);
    }

    public function GameSave(){

           $message=array("status"=>"400","msg"=>"修改失败");


           $arr=$this->getData->post();

           if(!empty($arr)){
              $arr['content'] = $_POST['content'];


              $validate = Loader::validate('Gamev');

              if($validate->check($arr)){
                
              if(isset($arr['file'])){
                $imgRes=Resources::base64_img($arr['file'],"/images",$this->typearray);
                if($imgRes['status']=="ok"){
                  $arr['img_path']=$imgRes['data'];
                  unset($arr['file']);
                }else{
                  $message['msg']=$imgRes['data'];
                  echo json_encode($message);die;
                }         
              }    
               if(model("gamelist")->allowField(true)->save($arr,$arr['id'])){
                $message['status'] = "200";
               }
            }else{

            $message['msg']=$validate->getError();

            }   

           }
           echo json_encode($message);  
    }
    public function del($id=""){
           if(!$id) {echo json_encode(array("status"=>"400","msg"=>"必要参数不能为空"));die;};
           $gamelist=model("gamelist");
           if($gamelist::destroy($id)){
              $message['status'] = "200";
           }else{
              $message['status']    = "400";
              $message['msg'] = "删除失败";
           };
           echo json_encode($message);
    }    
}
