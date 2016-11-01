$(function(){
//数据一定要根据id获取否则问题很严重
//防止浏览器记录历史
$('#tdinfo').val('');
/********************************************
基础事件  对于动态生成的元素很重要
*********************************************/
/*//回车事件绑定
$("body").keyup(function (event) {  
if (event.which == 13){  
    $("#search").trigger("click");
  }  
});*/
//表格点击事件
function clicktd(){
  $("td").off("click").on("click",function(){ //冒泡事件解决动态添加元素无效情况  
    var fobj=$(this);
    $('#tdinfo').val(tdinfo);
    var tdinfo=fobj.text(); 
    $('#tdinfo').val(tdinfo);
    $('.select').removeClass('select');
    fobj.addClass('select');
    changeinfo(fobj);
    $("#tdinfo").focus().select();
  });
}
//移入插件zclip实现点击复制功能
/*function tocopy(){
   $(".tocopy").zclip({
      path: 'Public/jquery-ui/zclip/ZeroClipboard.swf',
      copy: function(){     
        return $(this).text();
      }
    }); 
}*/
//更改信息事件
function changeinfo(fobj){  
  $('#tdinfo').unbind('blur').blur(function(){        //关于事件绑定问题
    var newinfo=$.trim($('#tdinfo').val());
    if(newinfo!=fobj.text()){             
      var need=fobj.attr('class').toString();
      var qclass=need.split(' ')[0];            //防止有多个class 取第一个
      var qxu=fobj.parents().children('.qxu').text();
      if(qxu==''){ 
        qxu=fobj.parents().prev().children('.qxu').text();
      }
      $.ajax({
        type:'GET',
        url:baseChangeinfo,
        data:{
        qclass:qclass,
        qxu:qxu,
        newinfo:newinfo
        },
        success:function (data) {
          if(data==1){
            fobj.text(newinfo); 
          }
        }
      });
    }
  });
}
//已录入之后,获取该小区的负责人
//地址,处理类型,需要人员,备注,序号
function getstaffval(address,actype,qstaff,qmark,qxu){
  var staff= actype=='维修'?1:2;
  if(address!=''){
    $.ajax({
      type:'GET',
      url:baseAutoadd,
      data:{
        'address':address,
        'staff':staff
      },
      success:function(data){
        if(data=='海之欣'){
          qstaff.text(data);
          qmark.text('铁通置换');
        }else if(data==3){
          $('#modalinfo').html('<span class="alert alert-danger">该小区为代维京信</span>');
          $('#msgerror').modal('show');
        }else{
          var ninfo=new Array();
         var  ninfo=data.split('-');
          qstaff.text(ninfo[0]);
          if(ninfo[1]=='置换小区'){
            qmark.text('铁通置换');
          }else{
            qmark.text('');
          }
        }
        $.ajax({            //数据库更改数据
          type:'GET',
          url:baseChangeinfo,
          data:{
          qclass:'qstaff',
          qxu:qxu,
          newinfo:ninfo[0]
          }
        });
        if(ninfo[1]=='置换小区'){   //数据库更改数据
          $.ajax({
            type:'GET',
            url:baseChangeinfo,
            data:{
            qclass:'qmark',
            qxu:qxu,
            newinfo:ninfo[1]
            }
          });
        }               
      }   
    });
  }   
}
//搜索选项
function search(){
    var search=new Array();
    search[0]="wo:"+$.trim($('#swo').val());
    search[1]="tel:"+$.trim($('#stel').val());
    search[2]="address:"+$.trim($('#saddress').val());
    search[3]="tda:"+$.trim($('#stda').val());
    search[4]="mark:"+$.trim($('#smark').val());
    search[5]="sta:"+$.trim($('#ssta').val());
    search[6]="staff:"+$.trim($('#sres').val());
    if($.trim($('#sres').val())==''){     
      if($('#sstaff').text()!='所有'){
        search[7]="staff:"+$.trim($('#sstaff').text());
      }
  }
    if($('#stype').text()!='所有'){
      search[8]="type:"+$.trim($('#stype').text());
    }     
    $.ajax({
      type:'POST',
      url:baseSearch+'?action=tdsearch',
      data:{
        search:search
      },
      success:function(content){
        //alert(content);
        $('#swo').val('');
        $('#stel').val('');
        $('#saddress').val('');
        $('#stda').val('');
        $('#smark').val('');
        $('#ssta').val('');
        $('#sres').val('');
        $('#getall').html(content);
        clickcall();
        clicktd();
        clickre();
        clickother();
        clickqtype();
        /*clickdel();*/
      }
    });
}
//已录入时刻使用 变更各项数据
function clickre(){    
    $('.repair').click(function(){
    var ctype=$(this).text();
    var address=$(this).parents().children('.qaddress').text();
    var qstaff=$(this).parents().children('.qstaff');
    var qmark=$(this).parents().children('.qmark');
    var qxu=$(this).parents().children('.qxu').text();
    if(ctype=='维修'){
      $(this).text('催装');
      var actype='催装';
      getstaffval(address,actype,qstaff,qmark,qxu);
    }else{
      $(this).text('维修');
      var actype='维修';
      getstaffval(address,actype,qstaff,qmark,qxu);
    }
  });
}
//点击其他
function clickother() {
    $('.other').click(function(){
    $('.bla').remove();
    $('#call').remove();
    $('#dowork').remove();
    var obj=$(this);
    var tel=obj.parents().children('.qtel').text();
    var tels=new Array();
    tels=tel.split(' ');
    var needtel=tels[tels.length-1];
    var address=obj.parents().children('.qaddress').text();
    var res=obj.parents().children('.qres').text();
    var xu=obj.parents().children('.qxu').text();
    var fdate=new Date();
    var day=fdate.getDate();
    $.ajax({
      type:'GET',
      url:blGetother+'?action=getother',
      data:{
        xu:xu
      },
      success:function(data){
        if(data!=1){
          var allinfo=new Array();
          allinfo=data.split('_');
          obj.parent().parent().parent().after("<tr class='bla'>"+
          "<td colspan='9' class='qtdin'>"+day+'号'+res+' '+needtel+' '+address+"</td></tr>"+
          "<tr class='bla'>"+
          "<td colspan='9' class='qtdin'>"+allinfo[0]+' '+needtel+' '+address+' '+allinfo[1]+"</td></tr>");
          clicktd();
        }else{
          obj.parent().parent().parent().after("<tr class='bla'>"+
          "<td colspan='9' class='qtdin'>"+day+'号'+res+' '+needtel+' '+address+"</td>"+
          "</tr>");
          clicktd();           
        }
      }
    });
   });
}
//点击单状态
function clickqtype(){
  $('.qtype').click(function(){
    var obj=$(this);
    var qtype=obj.text();
    var type=null;
    switch (qtype) {
      case '已派发':
        newinfo='已提单';
        obj.text(newinfo);
        break;
      case '已提单':
        newinfo='先提单';
        obj.text(newinfo);
        break;
      case '先提单':
        newinfo='已派发';
        obj.text(newinfo);
        break;
    }
    var qxu=obj.parent().children('.qxu').text();
    var need=obj.attr('class').toString();
    var qclass=need.split(' ')[0];            //防止有多个class 取第一个
    $.ajax({
      type:'GET',
      url:baseChangeinfo,
      data:{
        qclass:qclass,
        qxu:qxu,
        newinfo:newinfo
      }
    });
  });
}
//点击拨号
function clickcall(){
  $('.call').click(function(){
    var obj=$(this);
    var tda=obj.text();
    if(tda=='完成'){
      $('#modalinfo').html('<span class="alert alert-danger">你已无需拨号</span>');
      $('#msgerror').modal('show');
      return false;
    }
    var xu=obj.parents().children('.qxu').text();
    var tel=obj.parents().children('.qtel').text();
    var tels=new Array();
    tels=tel.split(' ');
    var needtel=tels[tels.length-1];
    //var tda=obj.text();
    $('.bla').remove(); 
    $('#call').remove(); 
    $('#dowork').remove(); 
    $.ajax({
      type:'GET',
      url:blCall+'?action=call',
      data:{
        xu:xu,
      },
      success:function(data){
        var fdate=new Date();
        var day=fdate.getDate();
        var hour = fdate.getHours();      //获取当前小时数(0-23)
        var minute = fdate.getMinutes();   // 获取当前分钟数(0-59)       
        if(data!=1){
          obj.parent().parent().parent().after("<tr id='call'>"+
          "<td colspan='9' class='qtdb'>"+data+" "+hour+':'+minute+" 录音电话回访用户"+needtel+"</td>"+
          /*"<th>"+
          "<div class='btn-group btn-group-xs czm'>"+
          "<button type='button' class='btn  btn-primary fine'>认可</button>"+
          "<button type='button' class='btn  btn-primary refuse'>无人</button>"+
          "<button type='button' class='btn  btn-primary contact'>联系</button>"+
          "</div></th>"+*/
          "</tr>"+
          "<tr id='dowork'><th colspan='9'>"+
          "<div class='btn-group btn-group-sm czm'>"+
          "<button type='button' class='btn  btn-primary fine'>认可</button>"+
          "<button type='button' class='btn  btn-warning refuse'>无人</button>"+
          "<button type='button' class='btn  btn-warning refused'>拒接</button>"+
          "<button type='button' class='btn  btn-primary newmeet'>改约</button>"+
          "<button type='button' class='btn  btn-primary contact'>联系</button>"+
          "<button type='button' class='btn  btn-primary makesure'>确定</button>"+
          "</div></th></tr>");
          clickcontact();
          clickfine();
          clickrefuse();
          clickrefused();
          clicknewmeet();
          clickmakesure()
          clicktd();
        }else{
          obj.parent().parent().parent().after("<tr id='call'>"+
          "<td colspan='9' class='qtdb'>"+day+'/ '+hour+':'+minute+" 录音电话回访用户"+needtel+"</td>"+
          /*"<th>"+
          "<div class='btn-group btn-group-xs czm'>"+
          "<button type='button' class='btn  btn-primary fine'>认可</button>"+
          "<button type='button' class='btn  btn-primary refuse'>无人</button>"+
          "<button type='button' class='btn  btn-primary contact'>联系</button>"+
          "</div></th>"+
          "</tr>"+*/
          "<tr id='dowork'><th colspan='9'>"+
          "<div class='btn-group btn-group-sm czm'>"+
          "<button type='button' class='btn  btn-primary fine'>认可</button>"+
          "<button type='button' class='btn  btn-warning refuse'>无人</button>"+
          "<button type='button' class='btn  btn-warning refused'>拒接</button>"+
          "<button type='button' class='btn  btn-primary newmeet'>改约</button>"+
          "<button type='button' class='btn  btn-primary contact'>联系</button>"+
          "<button type='button' class='btn  btn-primary makesure'>确定</button>"+
          "</div></th></tr>");
          clickcontact();
          clickfine();
          clickrefuse();
          clickrefused();
          clicknewmeet();
          clickmakesure()
          clicktd();
        }
      }
    });   
  });
}
//点击联系
function clickcontact(){
  $('.contact').click(function(){
    var obj=$(this);
    var content=obj.parent().parent().parent().prev().children().text();
     content=content.replace(/回访/, '联系');
    obj.parent().parent().parent().prev().children().text(content);
  });
}
//拨号状态转换1
function switchinfo(qcall){
  switch (qcall) {
      case '拨号':
        return '完成';
        break;
      case '两次':
        return '完成';
        break;
      case '三次':
        return '完成';
        break;
    }
}
//拨号状态转换2
function unswitchinfo(qcall){
  switch (qcall) {
      case '拨号':
        return '两次';
        break;
      case '两次':
        return '三次';
        break;
      case '三次':
        return '完成';
        break;
    }
}
/***************************************************
修改拨号信息
obj 点击按钮事件
info 修改内容
sel 变更拨号次数
infochangge 是否处理内容 1为处理 其余为不处理
****************************************************/
function getnewinfo(obj,info,sel,infochange) {
  var need=obj.parent().parent().parent().prev().children().attr('class').toString(); 
  var qclass=need.split(' ')[0];            //防止有多个class 取第一个
  var qxu=obj.parent().parent().parent().prev().prev().children('.qxu').text();
  if(infochange==1){      
    var content=obj.parent().parent().parent().prev().children().text();
        content=content+' '+info;
    $.ajax({
      type:'GET',
      url:baseChangeinfo,
      data:{
        qclass:qclass,
        newinfo:content,
        qxu:qxu
      },
      success:function(data){
        if(data==1){
            obj.parent().parent().parent().prev().children().text(content);
          }
      }
    });
  }
  var qcall=obj.parent().parent().parent().prev().prev().children('th').children().children('.call').text();
  if(sel==1){
    var qtda=switchinfo(qcall);
  }else{
    var qtda=unswitchinfo(qcall);
  }    
  $.ajax({
    type:'GET',
    url:baseChangeinfo,
    data:{
      qclass:'qtda',
      newinfo:qtda,
      qxu:qxu
    },
    success:function(data){
      if(data==1){
        obj.parent().parent().parent().prev().prev().children('th').children().children('.call').text(qtda);
        }
    }
  });
}
//点击认可
function clickfine(){
  $('.fine').click(function(){
    var obj=$(this);
    var info='用户表示认可。';
    getnewinfo(obj,info,1,1);
  });
}
//点击无人接听
function clickrefuse(){
  $('.refuse').click(function(){
    var obj=$(this);
    var info='用户无人接听。';
    getnewinfo(obj,info,0,1);
  });
}
//点击拒接
function clickrefused(){
  $('.refused').click(function(){
    var obj=$(this);
    var info='用户拒接电话。';
    getnewinfo(obj,info,0,1);
  });
}
//点击改约
function clicknewmeet(){
  $('.newmeet').click(function(){
    var obj=$(this);
    var fdate=new Date();
    var ddate=fdate.getDate();
    ddate++;
    var info='改约'+ddate+'号上门维修。用户表示认可。';
    getnewinfo(obj,info,1,1);
  });
}
//点击确定
function clickmakesure(){
  $('.makesure').click(function(){
    var obj=$(this);
    var info='';
    getnewinfo(obj,info,1,0);
  });
}
/***********************************
基础事件默认执行
************************************/
clicktd();
clickre();
clickcall();
clickother();
clickqtype();
/*************************************
静态页面方法
*************************************/
    //点击实现搜索功能
    $('#search').click(function(){
      search();
  });

    //搜索单子的状态
    $('.wotype').click(function(){
      var wotype=$(this).text();
      $('#stype').text(wotype);
      search();
    })
    //搜索人员
     $('.wostaff').click(function(){
      var wostaff=$(this).text();
      $('#sstaff').text(wostaff);
      search();
    });
});