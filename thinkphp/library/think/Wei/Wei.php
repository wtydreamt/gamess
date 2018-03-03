<?php
namespace think\Wei;
use think\Config;
use think\CURLFile;
use think\Db;
header('Content-type:text/html;charset=utf-8');
class Wei{
		private $appid;
		private $redirect_uri;
		private $scope;
		private $secret;
		private $url;		
		public function __construct(){
	  		$this->appid       ="wx043d7b347be7967b";
			$this->redirect_uri="";
			$this->scope       ="";
			$this->secret      ="953cd51f9c2ddbbe973357bd39c6dba5";
	    }
	  function read_type(){

	  	  
	  	    return  $data=array(
			  		"appid"       =>"wx043d7b347be7967b",
					"redirect_uri"=>"",
					"scope"       =>"",
					"secret"      =>"953cd51f9c2ddbbe973357bd39c6dba5"
						   );	  	    	

	  }
	  		  function query_token($name){
	  		  		$message=array();
		  		  	$res=DB::table("kl_token")->where(['token_name'=>$name])->find();
		  		  	if($res){
		  		  		 if($res['token_time']>time()-$res['add_time']){
			  		  		 $message['status']=true;
			  		  		 $message[$name]   =$res['access_token'];		  		  		 	
		  		  		 }else{
		  		  		     DB::table("kl_token")->where(['token_name'=>$name])->delete();
			  		  		 $message['status']=false;
			  		  		 $message[$name]   ="";
		  		  		 }
		  		  	}else{
			  		  		 $message['status']=false;
			  		  		 $message[$name]   ="";
		  		  	}
		  		  			 return $message;
	  		  }
	  		  function storage_token($name,$token){
	  		  		$arr=array("token_name"=>$name,"access_token"=>$token,"add_time"=>time(),"shijian"=>date('Y-m-d H:i:s'));
	  		  		DB::table("kl_token")->insertGetId($arr);
	  		  }
			  /*获取code*/
			  function getCode(){
			  		$arr=$this->read_type();
			  		if($arr==""){
			  			print_r(array("error"=>"MODE:参数设置有误参数值：set || file"));exit;
			  		}
			  		$redirect_uri=urlencode($arr['redirect_uri']);
					return $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$arr['appid']."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$arr['scope']."&state=STATE#wechat_redirect";
			  }
			  /*获取网页授权access_token*/
			  function get_access_token($code){
			  	   $arr=$this->read_type();
			  		if($arr==""){
			  			print_r(array("error"=>"MODE参数设置有误参数值：set || file"));exit;
			  		}	  	   
			  	    $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$arr['appid']."&secret=".$arr['secret']."&code=".$code."&grant_type=authorization_code";
			  	    $data=file_get_contents($url);
		            return $data=json_decode($data,true);
			  }
			  /*GET 拉取用户个人信息*/
			  function get_userinfo($access_token,$openid){
					$url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
			  	    $data=file_get_contents($url);
		            return $data=json_decode($data,true);			
			  }
			  /*GET 获取jsapi接口权限jsapi_ticket*/
			  function get_jsapi_ticket(){
			  	$message=$this->query_token("ticket");
			  	if($message['status']){
			  		return array("ticket"=>$message['ticket']);
			  	}else{			  	
			  		   $data=$this->get_base_token();
			  		   $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$data['access_token']."&type=jsapi";
			  		   $str=file_get_contents($url);
			  		   $res=json_decode($str,true);
					   $this->storage_token("ticket",$res['ticket']);
					   return $res;			  		
			  	}			  		   
			  }
			  /*GET 获取基础支持access_token*/
			  function get_base_token(){
			  	$message=$this->query_token("base_token");
			  	if($message['status']){
			  		return array("access_token"=>$message['base_token']);
			  	}else{
				  	$arr=$this->read_type();
					$url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$arr['appid']."&secret=".$arr['secret'];
					$json=file_get_contents($url);
					$res=json_decode($json,true);
					$this->storage_token("base_token",$res['access_token']);
					return $res;			  		
			  	}
	  	
			  }
			  function get_temp_img($token,$img_id){
			  	$url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$token."&media_id=".$img_id;
			  	$data=file_get_contents($url);
			  	return $data;
			  }
	  //业务模板消息提醒模块
	  function TemplateMessage(){
	  	 $res=$this->get_base_token();
	  	 $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$res['access_token'];
		 $arr=array("touser"=>"ocl3MwVnkxlNZzioq1AvQ3v4X780",
		            "template_id"=>"isiQWgTECclVUV0ud2WXUq6QMFAxmXxJNNQo7GTMIFk",
		            "url"=>"www.baidu.com",
		            "topcolor"=>"#FF0000",
		            "data"=>array("first"=>array("value"=>"恭喜你购买成功！vip玩家","color"=>"#173177"),
		                          "keyword1"=>array("value"=>"昆仑财富vip消息会员","color"=>"#173177"),
		                          "keyword2"=>array("value"=>"微信支付","color"=>"#173177"),
		                          "keyword3"=>array("value"=>date('Y-m-d H:i:s'),"color"=>"#173177"),
		                          "keyword4"=>array("value"=>date('Y-m-d H:i:s'),"color"=>"#173177"),
		                          "remark"  =>array("value"=>"昆仑财富vip消息","color"=>"#173177"),
		                         )
		            );	  	
	  	$this->curlPost($arr,$url);
	  }

											  function sendTextMessageAll($url,$content){
											  		$arr=$this->get_base_token();
											  		$content="<a href=\"".$url."\">".$content."</a>";
												    $url ="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$arr['access_token'];
												    $curlPost = [
												                    'filter' => ['is_to_all'=>true, 'tag_id'=>0],
												                    'msgtype' => 'text',
												                    'text' => ['content'=>$content]
												                ];
												    $this->curlPost($curlPost,$url);
											  }
											  /*文本消息发送给指定的一群人*/
											  function sendOpneidListTextmessage($openids,$url,$content){
												  	$arr=$this->get_base_token();
												  	$content="<a href=\"".$url."\">".$content."</a>";
												  	$url ="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$arr['access_token'];
											        $curlPost = [
											                    'touser' => $openids,
											                    'msgtype' => 'text',
											                    'text' => ['content'=>$content]
											                ];
											        $this->curlPost($curlPost,$url);
											  }
											  /*文本消息发送给指定的一个人*/
											  function sendOneTextmessage($openid,$url,$content){
													$arr=$this->get_base_token();
													$content="<a href=\"".$url."\">".$content."</a>";
												    $url ="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$arr['access_token'];
												    $curlPost = [
												                    'touser' => $openid,
												                    'msgtype' => 'text',
												                    'text' => ['content'=>$content]
												                ];
												    $this->curlPost($curlPost,$url);
												  }
											function fenxiang($url){
							                    $jsapi_ticket=$this->get_jsapi_ticket();
							                    $array=array('noncestr'=>$this->generateNonceStr(),
							                                 'jsapi_ticket'=>$jsapi_ticket['ticket'],
							                                 'timestamp'=>time(),
							                                 'url'=>$url
							                        );   
							                    $str=$this->jsapiSign($array);  
							                    $array['sign']=$str;  
							                    $wxparams=$this->read_type();
							                    $array['appid']=$wxparams['appid'];	
							                    return $array;											
											}
		 function curlPost($curlPost,$url){
			    $curlPost = json_encode($curlPost, JSON_UNESCAPED_UNICODE);
				$ch = curl_init();                                     //初始化curl
				curl_setopt($ch,CURLOPT_URL,$url);                 //抓取指定网页
				curl_setopt($ch,CURLOPT_HEADER, 0);                    //设置header
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
				curl_setopt($ch,CURLOPT_POST, 1);                      //post提交方式
				curl_setopt($ch,CURLOPT_POSTFIELDS, $curlPost);
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
				curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
				$data = curl_exec($ch);                                 //运行curl
				curl_close($ch);
				// print_r($data);die;	 
		 }
			 /*微信客服系统*/
			 function CustomerService(){
			 	$arr=$this->get_base_token();
			 	$url="https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=".$arr['access_token'];
			 	$arr=file_get_contents($url);
			 }
	 
		    /*** 生成随机字串* @param number $length 长度，默认为16，最长为32字节* @return string*/
		    public function generateNonceStr($length=16){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$str = "";
			for($i = 0; $i < $length; $i++)
			{
				$str .= $chars[mt_rand(0, strlen($chars) - 1)];
			}
			return $str;
		    }
		    public function Arrsort($params){
				ksort($params);
				foreach ($params as $key => $item) {
				if (!empty($item)) {
							$newArr[] = $key.'='.$item;
				}
				}	
				return 	 $params;   	
		    }
			/** 获取签名；* @return String 通过计算得到的签名；**/
			public function getSign($params) {
			ksort($params);
			foreach ($params as $key => $item) {
			if (!empty($item)) {
						$newArr[] = $key.'='.$item;
			}
			}
			$data=$this->payPublicParams();
			$stringA = implode("&", $newArr);
			$stringSignTemp = $stringA."&key=".$data['key'];
			$stringSignTemp = MD5($stringSignTemp);
			$sign = strtoupper($stringSignTemp);
			return $sign;
			}	

			public function jsapiSign($params){
				ksort($params);
				foreach ($params as $key => $item) {
				if (!empty($item)) {
							$newArr[] = $key.'='.$item;
				}
				}
				return $stringA = sha1(implode("&", $newArr));			
			}

			/*创建公共参数*/
			public function payPublicParams(){
				return array('appid'      =>"wx457dec0a066e3bb6",
							 "mch_id"     =>"1485564862",
							 "key"        =>"KLuGJ3hytgHak2VUtoQQhRuHCdMkz36H",
							 "notify_url" =>"http://kl.ffcka.com/notify/",
							 "trade_type" =>"JSAPI"
							 );
			}			
			/*统一下单操作*/
			public function sendRequest($arr){
						   $arr_pay_data =$this->getsendPayData($arr);
						   $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
						   $content = $this->execute($isHttps = false, $requestType = 'POST', $arr_pay_data, $useCert = false,$url);
						   return $content;
			}
			public function GetJsApiParameters(){

			}
			/* 解析xml文档，转化为数组*/							
			function xmlToArray($xml){ 
			 //禁止引用外部xml实体 
			libxml_disable_entity_loader(true); 
			$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
			$val = json_decode(json_encode($xmlstring),true); 
			return $val; 
							}
			//调起支付
			function jsapistr($arr){
				unset($arr['sign']);
				// print_r($arr);die;
				$array=array(
							 "appId"=>"wx457dec0a066e3bb6",
							 "timeStamp"=>strval(time()),
							 "nonceStr"=>$this->generateNonceStr(),
							 "package"=>"prepay_id=".$arr['prepay_id'],
							 "signType"=>"MD5",
							);
				 
				$array=$this->Arrsort($array);
				$array['paySign']=$this->getSign($array);
				 return $array;

			} 			
			/*创建微信xml格式数据*/
			public function getsendPayDataXml(){
				return"<xml>
						<appid><![CDATA[%s]]></appid>
						<body><![CDATA[%s]]></body>
						<mch_id><![CDATA[%s]]></mch_id>
						<nonce_str><![CDATA[%s]]></nonce_str>
						<notify_url><![CDATA[%s]]></notify_url>
						<openid><![CDATA[%s]]></openid>
						<out_trade_no><![CDATA[%s]]></out_trade_no>
						<spbill_create_ip><![CDATA[%s]]></spbill_create_ip>
						<total_fee><![CDATA[%d]]></total_fee>
						<trade_type><![CDATA[%s]]></trade_type>
						<sign><![CDATA[%s]]></sign>
						</xml>";								
			}
			/*获取微信支付的数据*/
			public function getsendPayData($arr){
					   $nonce_str        =$this->generateNonceStr();
					   $data             =$this->payPublicParams();
					   $arr['appid']     =$data['appid'];
					   $arr['nonce_str'] =$nonce_str;
					   $arr['mch_id']    =$data['mch_id'];
					   $arr['notify_url']=$data['notify_url'];
					   $arr['trade_type']=$data['trade_type'];
					   $arr['openid']    =$arr['openid'];
					   $sign=$this->getSign($arr);
					   $sTpl=$this->getsendPayDataXml();
					   return $arr_pay_data = sprintf($sTpl, $data['appid'], $arr['body'], $data['mch_id'], $nonce_str, $data['notify_url'], $arr['openid'], $arr['out_trade_no'], $arr['spbill_create_ip'], $arr['total_fee'], $data['trade_type'], $sign);

			}	
		    /* 执行一个请求* @return resource 返回执行结果*/
		    public function execute($isHttps = false, $requestType = 'GET', $data = null, $useCert = false,$url){
				$ch      =curl_init();
				$this->setopt($isHttps, $requestType, $data, $useCert,$ch,$url);
				$content = curl_exec($ch);
				$this->close($ch);
				if (!empty($content)) 
				{
				    return $content;
				} else {
				    return curl_error($ch);
			    	   }
			}
			/** 构造curl请求     设置请求属性* @param  boolean $isHttps     是否采用https方式请求* @param  string  $requestType 请求方式*/				
			private function setopt($isHttps, $requestType, $data, $useCert = false,$ch,$url){
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				if ($isHttps) {
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
					}
				   if($useCert == true){
				    //设置证书
				    //使用证书：cert 与 key 分别属于两个.pem文件
			        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
			        curl_setopt($ch,CURLOPT_SSLCERT, SSLCERT_PATH);
			        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
			        curl_setopt($ch,CURLOPT_SSLKEY, SSLKEY_PATH);
			    }
				if (!empty($data)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				}
				if ($requestType == 'POST') {
					curl_setopt($ch, CURLOPT_POST, true);
				}
			}
			/**关闭一个请求资源句柄*/
			private function close($ch){
				    curl_close($ch);
			}	
							/************************************************
							 * 接收支付结果通知参数
							 * @return Object 返回结果对象；
							 *************************************************/
							public function getNotifyData() {
								$postXml = $GLOBALS["HTTP_RAW_POST_DATA"];	//接受通知参数；
								if (empty($postXml)) {
									return false;
								}
								$postObj = $this->xmlToObject($postXml);
								if ($postObj === false) {
									return false;
								}
								if (!empty($postObj->return_code)) {
									if ($postObj->return_code == 'FAIL') {
										return false;
									}
								}
								return $postObj;
							}
							//接受通知结果 返回数组参数
							public function getNotifyArr(){
								$postXml = $GLOBALS["HTTP_RAW_POST_DATA"];	//接受通知参数；
								if (empty($postXml)) {
									return false;
								}
								$postarr=$this->xmlToArray($postXml);
								if($postarr['return_code']== 'FAIL'){
									return false;
								}
								}			
			 //  /*素材添加模块*/
			 //  function get_img_media_id(){
			 //  	$res=$this->get_base_token();
			 //  	// $url="http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$res['access_token']."&type=image";       //新增临时素材 PJUa53CVKlqGLQlDj1_DVo-8ZzhoABWA5zoJBkvDY5APZflPMyU827iO3_GnFQJN

			 //  	// $url="https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$res['access_token'];                      //图文消息中的图片上传后获取图片url

			 //  	$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$res['access_token']."&type=thumb";  //新增其他永久素材  可用在图文消息封面图片media_id获取
			 //  	//4F_FWWnF4xIHgGUCjmJRWqfhsx9stGpoHJsva0hftJA  //永久图片media_id
			 //  	//x8q2VjaYglKNWes0Ks5JfKfSYvCFbsOGVp5B6WuYqy0
				// if (class_exists('\CURLFile')) {
				//     $curlPost = array('media' => new \CURLFile('/var/www/kunlun/indexs/public/static/img/lazy.png'));
				// } else {
				//     $curlPost = array('media' => '@' . '/var/www/kunlun/indexs/public/static/img/666.png');
				// }
				
				// 		$ch = curl_init();                                     //初始化curl
				// 		curl_setopt($ch,CURLOPT_URL,$url);                 //抓取指定网页
				// 		curl_setopt($ch,CURLOPT_HEADER, 0);                    //设置header
				// 		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
				// 		curl_setopt($ch,CURLOPT_POST, 1);                      //post提交方式
				// 		curl_setopt($ch,CURLOPT_POSTFIELDS, $curlPost);
				// 		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
				// 		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
				// 		$data = curl_exec($ch);                                 //运行curl
				// 		curl_close($ch);
				// 		print_r($data);die;	

			 //  }
			  // //新增永久的图文素材
			  // function xing_add_news(){
			  // 	$res=$this->get_base_token();
			  //   $url="https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$res['access_token'];
			  //   $curlPost=array("articles"=>[["title"             =>"你一定爱看的极简魏晋南北朝史（前传)",
					// 						       "author"            =>"不知道",
					// 						       "digest"            =>"魏晋南北朝历史",
					// 						       "content"           =>"内容太长这儿不写了点击看原文吧",
					// 						       "show_cover_pic"    =>1,
					// 						       "thumb_media_id"    =>"x8q2VjaYglKNWes0Ks5JfKfSYvCFbsOGVp5B6WuYqy0",
					// 						       "content_source_url"=>"http://www.jianshu.com/p/94e0be36ce01"
			  //   								 ]]
			  //   				);
			  //   //4F_FWWnF4xIHgGUCjmJRWsAtsx8g5BSjNpZ6ANlA4hs 永久图文素材
			  //   //x8q2VjaYglKNWes0Ks5JfIPUZZampq4EVgnZdHH2ZKI
			  //   $this->curlPost($curlPost,$url);
			  // }
			  //业务模板消息提醒模块	
	  										//function sendImage(){
											//$arr=$this->get_base_token();
												 //$url ="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$arr['access_token'];
												 //$curlPost = [
												 //                    'filter'  => ['is_to_all'=>true, 'tag_id'=>0],
												 //                    'mpnews'  => ['media_id'=>"x8q2VjaYglKNWes0Ks5JfKfSYvCFbsOGVp5B6WuYqy0"],
												 //                    'msgtype' => 'image',
												 //                ];
												 //    $this->curlPost($curlPost,$url);	  										  	
	  										//   }
											  // /*图文消息发送所有人*/
											  // function sendTextImgMessageAll(){
											  // 		$arr=$this->get_base_token();
												 //    $url ="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$arr['access_token'];
												 //    $curlPost = [
												 //                    'filter' => ['is_to_all'=>true, 'tag_id'=>0],
												 //                    'mpnews' => ['media_id'=>"x8q2VjaYglKNWes0Ks5JfIPUZZampq4EVgnZdHH2ZKI"],
												 //                    'msgtype' => 'mpnews',
												 //                    'send_ignore_reprint'=>0
												 //                ];
												 //    $this->curlPost($curlPost,$url);	  	
											  // }
											  /*文本消息群发所有人*/ 		  
}