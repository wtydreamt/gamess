<?php

   function GetWhere($where){
        if(!$where){
            return "";
        }else{
            $str="&";
            foreach($where as $key=>$val){
                $ws=explode(".", $key);
                $len=count($ws);
                $count=$len-1;
                $str=$str.$ws[$count]."=".$val."&";
            }
        }
        return rtrim($str,"&");
    }

    function label(){
        $list=model("label")->select();
        $temp=array();
        foreach($list as $key=>$val){
            $temp[$val['sorts']-1]=$val['label_img'];
        }
        return $temp;
    }

 function GameList($cid){
    $arr=model("gamelist")->where(['cid'=>$cid])->order("downloads desc")->order("id desc")->select();
    $str= "";
    $label=label();
    $labels="";
    foreach($arr as $key=>$val){
                if(isset($label[$key])){
                    $labels='<img src="'.$label[$key].'" alt="">';

                }else{
                    $num=$key+1;
                    $labels='<span>'.$num.'</span>';
                }
                $grade=grade($val['grade']);
                $str.='<li><a href="index.php/info/'.$val['id'].'">'.
                '<div class="left-list-img">
                '.$labels.'</div>
                <div class="media-content"><div class="left_img_con">
                <img class="hui-lazy"  src="/static/index/images/lazy.png" lazySrc="'.$val['img_path'].'" alt="" />'.
                '</div><h1 class="add_ho">'.$val['title'].'</h1>'.
                '<p class="add_stcon">
               '.$grade.'
                </p><p class="add_stext">'.mb_substr(preg_replace('/&nbsp;/is','',strip_tags($val['content'])),0,10,'utf-8').'</p></div><div class="righ-list-img">
                <img src="/static/index/images/xz.png">'.
                '<p>已下载'.$val['downloads'].'次</p></div></a></li>'; 
    }
    echo $str;
 }
   
 function grade($num){
    $str="";
    $str2="";
    $str=str_repeat('<img src="/static/index/images/x_h.png"/>', $num);
    $len=5-$num;
    $str2=str_repeat('<img src="/static/index/images/x_o.png"/>', $len);
    return $str.$str2;
    
 }                             
                                            
                                        
                             
                                  
                                  
                                 
                                      
                                      
                                                           
                        

        


                                
