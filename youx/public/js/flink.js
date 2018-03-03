layui.use(['element', 'form','layedit'], function(){
   var $ = layui.jquery ,layedit = layui.layedit, element = layui.element;
   var baseUrl="http://wty.guanj.com/admin.php/";
   var IMGUrl="http://wty.guanj.com/";
   $("#flink").click(function(){
    var fname    =$("input[name=fname]").val();
    var link     =$("input[name=link]").val();
    var show=1;
    var status  =$(".layui-form-radioed").find("span").text();
    if( status ==  "不显示"){
            show=2;
    }
    var shors     =$("input[name=shors]").val();
    var send={fname:fname,link:link,show:show,shors:shors}
    $.post(baseUrl+"Flink",send,function(msg){
        msg=$.parseJSON( msg );
        if(msg.status == "200"){
          location.reload();       
        }else{
          layer.msg(msg.msg,{icon: 5});
        }
    })
});
   $('.shows').on('click', function (e){
      var _this=$(this);
      var shows=$(this).attr("data");
      var id=$(this).attr("v");
      var save={'1':2,'2':1};
      var s=save[shows]
      var send={fid:id,show:s}
      var icon={'2':"&#x1006;",'1':"&#xe618;"};
      $.post(baseUrl+"Flink/showSave",send,function(msg){
        msg=$.parseJSON( msg );
        if(msg.status == "200"){
          _this.attr("data",s);
          _this.html(icon[s])         
        }else{
          layer.msg(msg.msg,{icon: 5});
        }
      })
    })
   $(".del").click(function(){
    var  id=$(this).attr("data");
    var send={id:id}
        layer.confirm('是否删除此项', {
          btn: ['确定','取消']
        }, function(){
          $.post(baseUrl+"Flink/del",send,function(msg){
            msg=$.parseJSON( msg );
            if(msg.status=="200"){
              $("#del_"+id).remove();
              layer.msg("删除成功",{icon: 6});
              location.reload();
            }else{
              layer.msg(msg.msg,{icon: 5});
            }
          })
        })
   })
   $(".edit").click(function(){
       var id = $(this).attr("data");
       tdData = $("#del_"+id).find("td");
       shors=tdData.find("span").eq(0).text();
       fname=tdData.find("span").eq(1).text();
       link=tdData.find("span").eq(2).text();
       layer.open({
        title:'修改友情链接',
        type: 1,
        skin: 'layui-layer-rim', 
        area: ['500px', '330px'], 
        content: $('#save')
      },
       $("input[name=sfname]").val(fname),
       $("input[name=slink]").val(link),
       $("input[name=sshors]").val(shors),
       $("#flinksave").attr("data",id)
      );
   })

   $("#flinksave").click(function(){
        var id = $(this).attr("data");
        var fname =$("input[name=sfname]").val();
        var link  =$("input[name=slink]").val();
        var shors =$("input[name=sshors]").val();
        var send =  {fid:id,fname:fname,link:link,shors:shors}
      $.post(baseUrl+"Flink/showSave",send,function(msg){
        msg=$.parseJSON( msg );
        if(msg.status == "200"){
          location.reload();         
        }else{
          layer.msg(msg.msg,{icon: 5});
        }
      })        
   })
});
