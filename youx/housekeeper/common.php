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


function jiequ($str,$end = "10"){

    echo mb_substr(preg_replace('/&nbsp;/is','',strip_tags($str)),0,$end,'utf-8');
}
function getIp(){
if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
$ip = getenv("HTTP_CLIENT_IP");
else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
$ip = getenv("REMOTE_ADDR");
else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
$ip = $_SERVER['REMOTE_ADDR'];
else
$ip = "unknown";
return($ip);}


        
        
                
                  
                  
                
        