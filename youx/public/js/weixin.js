var quanju="";
function weixinconfig(appid,timestamp,noncestr,sign){
        wx.config({
        debug: true, // 调试模式,会打印所有调用返回信息
        appId: appid, 
        timestamp: timestamp,
        nonceStr:noncestr, 
        signature: sign,
        jsApiList: ['onMenuShareTimeline','onMenuShareQQ','scanQRCode','onMenuShareAppMessage','onMenuShareWeibo','chooseImage','uploadImage','getLocalImgData','onMenuShareQZone'] // 请求权限
    });
}
function parms(str){
    return quanju=str;
}
// var appid='{$wxconfig.appid}';
// var timestamp='{$wxconfig.timestamp}';
// var noncestr='{$wxconfig.noncestr}';
// var sign='{$wxconfig.sign}';
// weixinconfig(appid,timestamp,noncestr,sign);
// var str={'title':'游戏排行榜','like':'{$wxconfig.url}','imgUrl':"__BASEURL__/static/img/kunlun.jpg"};
// parms(str);

wx.ready(function () {  
            wx.onMenuShareTimeline({  
                        title: quanju.title, // 分享标题  
                        link:  quanju.like, // 分享链接,将当前登录用户转为puid,以便于发展下线  
                        imgUrl: quanju.imgUrl, // 分享图标  
                        success: function () {   

                        },  
                        cancel: function () {   
                                    // 用户取消分享后执行的回调函数  
                                }  
                }); 
			wx.onMenuShareAppMessage({
			    title: quanju.title, // 分享标题
			    desc: quanju.desc, // 分享描述
			    link: quanju.like, //分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
			    imgUrl: quanju.imgUrl, // 分享图标
			    type: '', // 分享类型,music、video或link，不填默认为link
			    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			});
			//涉及到分享到qq desc 参数不能为空
			wx.onMenuShareQZone({
			    title: quanju.title, // 分享标题
			    desc: '游戏排行榜', // 分享描述
			    link: quanju.like, // 分享链接
			    imgUrl: quanju.imgUrl, // 分享图标
			    success: function () { 
			    },

			});	
			wx.onMenuShareQQ({
			    title: quanju.title, // 分享标题
			    desc: '游戏排行榜', // 分享描述
			    link: quanju.like, // 分享链接
			    imgUrl: quanju.imgUrl, // 分享图标
			    success: function () { 
			    },
			});		
			wx.onMenuShareWeibo({
			    title: quanju.title, // 分享标题
			    desc: '游戏排行榜', // 分享描述
			    link: quanju.like, // 分享链接
			    imgUrl: quanju.imgUrl, // 分享图标
			    success: function () { 
			    },
			});						                 
                // wx.error(function(res){  
                //     // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。  
                //     alert("errorMSG:"+res);  
                // });                
    }); 

function chooseImage(obj){
    // 选择张片
    wx.chooseImage({
        count: 1, // 默认9
        sizeType: ['original'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success: function(res) {
            var localIds = res.localIds[0]; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
            // 上传照片
            var arr=test();
            obj.setAttribute("src",localIds)
            if(arr['0']==true){
                obj.setAttribute("src",localIds)
                uploadImage(localIds)

            }
            if(arr['1']==true){
               if(window.__wxjs_is_wkwebview){
                 getLocalImgData(localIds,obj)
                 uploadImage(localIds)
               }else{
                 obj.setAttribute("src",localIds)
                 uploadImage(localIds)
               } 
                
            }
            // uploadImage(localIds)

        }
    });
}
function uploadImage(localIds){
            wx.uploadImage({
                localId:localIds.toString(),
                isShowProgressTips: 1,
                success: function(res) {
                    serverId = res.serverId;
                    var url="http://kl.ffcka.com/personal/img/web/"+serverId+"/";
                    push_get_data(url,'');
                   
                },
                fail: function (error) {  
                    // alert(JSON.stringify(error));  
  
                }  
            });    
}
function getLocalImgData(localIds,obj){
    wx.getLocalImgData({

        localId: localIds, // 图片的localID

        success: function (res) {
            var localData = res.localData; // localData是图片的base64数据，可以用img标签显示
            localData = localData.replace('jgp', 'jpeg');
            obj.setAttribute("src",localData)
            // alert(JSON.stringify(res));
        },
        fail: function (error) {  
            alert("网络繁忙……")
             // alert(JSON.stringify(error));  
  
        } 
    });    
}

function test(){
    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    return arr=[isAndroid,isiOS];
}
function push_get_data(url,data){
            if(data){
                url+=+data+"/";
            }

            var xmlhttp;
            if (window.XMLHttpRequest)
            {
                xmlhttp=new XMLHttpRequest();
            }
            else
            {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            } 
                xmlhttp.onreadystatechange=function()
                {
                    if (xmlhttp.readyState==4 && xmlhttp.status==200)
                    {
                        // alert(xmlhttp.responseText);
                    }
                }
                xmlhttp.open("GET",url,true);

                xmlhttp.send(); 
}
