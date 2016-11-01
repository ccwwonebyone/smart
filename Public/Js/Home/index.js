$(function(){
//数据一定要根据id获取否则问题很严重
//防止浏览器记录历史
$('#tdinfo').val('');
/********************************************
基础事件  最前面写  对于动态生成的元素很重要
*********************************************/
//表格点击事件
function clicktd(){
	$("td").off("click").on("click",function(){	//冒泡事件解决动态添加元素无效情况	
		var fobj=$(this);
		$('#tdinfo').val(tdinfo);
		var tdinfo=fobj.text();	
		$('#tdinfo').val(tdinfo);
		$('.select').removeClass('select');
		fobj.addClass('select');
		$("#tdinfo").focus().select();
		changeinfo(fobj);
	});
}
//移入插件zclip实现点击复制功能		无法使用相对定位  该网页无法实现
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
	$('#tdinfo').unbind('blur').blur(function(){				//关于事件绑定问题
		var newinfo=$.trim($('#tdinfo').val());
		if(newinfo!=fobj.text()){							
			var need=fobj.attr('class').toString();
			var qclass=need.split(' ')[0];						//防止有多个class 取第一个
			var qxu=fobj.parents().children('.qxu').text();
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
//其他按钮点击事件
function clickother(){
	$(".other").off("click").on("click",function(){	  
	    var obj=$(this);						//获取其他信息
	    var qxu=obj.parents().children('.qxu').text();
	    $.ajax({
	    	type:'GET',
	    	url:indexGetmore+'?action=more',
	    	data:{
	    		qxu:qxu
	    	},
	    	success:function(data){
	    		$('#sendsta').remove();
	    		$('#sendsys').remove();
	    		var sendinfo= new Array(); //定义一数组
				sendinfo=data.split("_"); //字符分割
	    		obj.parent().parent().parent().after("<tr id='sendsta'>"+
	    			"<td colspan='9' class='qyya'>"+sendinfo[0]+"</td></tr>"+
	    			"<tr id='sendsys'><td colspan='9' class='qyyb tocopy'>"+sendinfo[1]+'</td></tr>');
	    		clicktd();
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
//确认按钮点击事件
function clicksure(){
	$('.sure').off('click').on('click',function(){
		var obj=$(this);						//获取其他信息
	    var xu=obj.parents().children('.qxu').text();
	    var wo=obj.parents().children('.qwo').text();
	    var tel=obj.parents().children('.qtel').text();
	    var address=obj.parents().children('.qaddress').text();
	    var ytime=obj.parents().children('.qytime').text();
	    var mark=obj.parents().children('.qmark').text();
	    var status=obj.parents().children('.qstatus').text();
	    var res=obj.parents().children('.qres').text();
	    var staff=obj.parents().children('.qstaff').text();
	    var type=obj.parents().children('.qtype').text();
	    $.ajax({
	    	type:'GET',
	    	url:indexSuredata+'?action=suredata',
	    	data:{
	    		xu:xu,
	    		wo:wo,
	    		tel:tel,
	    		address:address,
	    		mark:mark,
	    		status:status,
	    		res:res,
	    		staff:staff,
	    		type:type
	    	},
	    	success:function(data){
				$('#sendsta').remove();
				$('#sendsys').remove();
				var info=new Array();
				info=data.split('_');	    		
				$('.showdata:first').after("<tr class='alert alert-success'>"+
							"<td class='qxu' style='display:none;'>"+info[0]+"</td>"+
							"<td class='qwo'>"+wo+"</td>"+
							"<td class='qtel'>"+tel+"</td>"+
							"<td class='qaddress'>"+address+"</td>"+
							"<td class='qtime'>"+ytime+"</td>"+
							"<td class='qmark'>"+mark+"</td>"+
							"<td class='qsta'>"+status+"</td>"+
							"<td class='qres'>"+res+"</td>"+
							"<td class='qstaff'>"+staff+"</td>"+
							"<th class='qtype btn btn-primary'>"+type+"</th>"+
							"<th>"+
								"<div class='btn-group btn-group-xs'>"+
									"<button type='button' class='btn  btn-primary other'>其他</button>"+
	  								"<button type='button' class='btn  btn-primary repair'>维修</button>"+
	  								"<button type='button' class='btn  btn-primary delete'>删除</button>"+
			  					"</div>"+
		  					"</th></tr>"+
		  				"<tr id='sendsta'>"+
	    				"<td colspan='9' class='qyya'>"+info[1]+"</td></tr>"+
	    				"<tr id='sendsys'><td colspan='9' class='qyyb tocopy'>"+info[2]+'</td></tr>');
		  				clickother();
						clicktd();
						clickre();
						clickdel();
						clickqtype();
				obj.parent().parent().parent().remove();
	    	}
	    });
	});
}
//录入时获取该小区的负责人
function getstaff(address,actype){
	var staff =actype=='维修'?1:2;
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
					$('#staff').val(data);
					$('#mark').val('铁通置换');
				}else if(data==3){
					$('#modalinfo').html('<span class="alert alert-danger">该小区为代维京信</span>');
					$('#msgerror').modal('show');
				}else if(data==4){
					$('#modalinfo').html('<span class="alert alert-danger">无法查询出结果，可能为其他代维</span>');
					$('#msgerror').modal('show');					
				}else{
					var ninfo=new Array();
					ninfo=data.split('-');
					$('#staff').val(ninfo[0]);
					if(ninfo[1]=='置换小区'){
						$('#mark').val('铁通置换');
					}else{
						$('#mark').val('');
					}
				}
			}
		});
	}		
}
//已录入之后,获取该小区的负责人
function getstaffval(address,actype,qstaff,qmark,qxu){
	var staff =actype=='维修'?1:2;
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
					ninfo=data.split('-');
					qstaff.text(ninfo[0]);
					if(ninfo[1]=='置换小区'){
						qmark.text('铁通置换');
					}else{
						qmark.text('');
					}
				}
				$.ajax({						//数据库更改数据
					type:'GET',
					url:baseChangeinfo,
					data:{
					qclass:'qstaff',
					qxu:qxu,
					newinfo:ninfo[0]
					}
				});
				if(ninfo[1]=='置换小区'){		//数据库更改数据
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
    search[3]="time:"+$.trim($('#stime').val());
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
    	url:baseSearch+'?action=yysearch',
    	data:{
    		search:search
    	},
    	success:function(content){
    		//alert(content);
    		$('#swo').val('');
    		$('#stel').val('');
    		$('#saddress').val('');
    		$('#stime').val('');
    		$('#smark').val('');
    		$('#ssta').val('');
    		$('#sres').val('');
    		$('#getall').html(content);
    		clickother();
			clicktd();
			clickre();
			clickdel();
			clickqtype();
			clicksure();
    	}
    });
}
//已录入时刻使用 变更各项数据
function clickre(){    
    $('.repair').off('click').on('click',function(){
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
//删除数据
function clickdel(){
	$('.delete').off('click').on('click',function(){		
		if(confirm('确定删除么')){
			$('#sendsta').remove();
			$('#sendsys').remove();
			var obj=$(this);
			var qxu=obj.parents().children('.qxu').text();
			$.ajax({
				type:'GET',
				url:baseDel,
				data:{
					qxu:qxu
				},
				success:function (data) {
					if(data==1){
						obj.parent().parent().parent().remove();
					}
				}
			});
		}

	});
} 
/***********************************
基础事件默认执行
************************************/
clickother();
clicktd();
clickre();
clickdel();
clicksure();
clickqtype();
/*************************************
静态页面方法
*************************************/
//变更类型处理类型	录入时刻使用
$('.sel').click(function(){
	var ctype=$(this).text();
	if(ctype=='维修'){
		$(this).text('催装');
		var actype='催装';
		if($('#address').val()!=''){
			var address=$.trim($('#address').val());
			getstaff(address,actype);
		}
	}else{
		$(this).text('维修');
		var actype='维修';
		if($('#address').val()!=''){
			var address=$.trim($('#address').val());
			getstaff(address,actype);
		}
		
	}
});
//输入地址时自动输入对应负责人
$('#address').blur(function(){
	var address=$.trim($('#address').val());
	var actype=$('.sel').text();
	getstaff(address,actype);
});
/*//jquery弹窗
$( "#dialog" ).dialog({
    autoOpen: false,
    show: {
    effect: "blind",
    duration: 500
	},
    hide: {
    effect: "explode",
    duration: 500
     }
});*/
 	/*//给序号值
	var fxu=parseInt($('#fxu').text());
	var cxu=fxu+1;
	$('#xu').val(cxu);
	
	//给日期值
	var fdate=new Date();
	mdate=fdate.getMonth();
	ddate=fdate.getDate();
	if(ddate<10){
		ddate='0'+ddate;
	}*/
	//$('#date').val(mdate+1+'.'+ddate);
	//确认信息无误后提交数据  写入参数不带q
	$('#sub').click(function(){	
		var wo  =$.trim($('#wo').val());
		var tel =$.trim($('#tel').val());
		var address =$.trim($('#address').val());
		var ytime=$.trim($('#ytime').val());
		var mark=$.trim($('#mark').val());
		var sta=$.trim($('#sta').val());
		var res=$.trim($('#res').val());
		var staff=$.trim($('#staff').val());
		var ylr=$('#ylr').val();
		if(wo!=''&&tel!=''&&address!=''&&ytime!=''&&staff){
			$.ajax({
				type:'GET',
				url:indexInsert+'?action=sub',
				data:{
					'wo':wo,
					'tel':tel,
					'address':address,
					'ytime':ytime,
					'mark':mark,
					'sta':sta,
					'res':res,
					'staff':staff,
					'ylr':ylr,
				},
				success:function(data){			//输出后的参数带q方便更改
					if(data!=''){
						$('#sendsta').remove();
						$('#sendsys').remove();
						var info=new Array();
						info=data.split('_');
						$('#wo').val('');
						$('#tel').val('');
						$('#address').val('');
						$('#ytime').val('');
						$('#mark').val('');
						$('#res').val('');
						$('#staff').val('');
						$('#linfo').after("<tr class='alert alert-success'>"+
							"<td class='qxu' style='display:none;'>"+info[0]+"</td>"+
							"<td class='qwo'>"+wo+"</td>"+
							"<td class='qtel'>"+tel+"</td>"+
							"<td class='qaddress'>"+address+"</td>"+
							"<td class='qtime'>"+ytime+"</td>"+
							"<td class='qmark'>"+mark+"</td>"+
							"<td class='qsta'>"+sta+"</td>"+
							"<td class='qres'>"+res+"</td>"+
							"<td class='qstaff'>"+staff+"</td>"+
							"<th class='qtype btn btn-primary'>"+ylr+"</th>"+
							"<th>"+
								"<div class='btn-group btn-group-xs'>"+
									"<button type='button' class='btn  btn-primary other'>其他</button>"+
	  								"<button type='button' class='btn  btn-primary repair'>维修</button>"+
	  								"<button type='button' class='btn  btn-primary delete'>删除</button>"+
			  					"</div>"+
		  					"</th></tr>"+
		  				"<tr id='sendsta'>"+
	    				"<td colspan='9' class='qyya'>"+info[1]+"</td></tr>"+
	    				"<tr id='sendsys'><td colspan='9' class='qyyb tocopy'>"+info[2]+'</td></tr>');
		  				clickother();
						clicktd();
						clickre();
						clickdel();
						clickqtype();				
					}
				}
			});
		}else{
			$('#modalinfo').html('<span class="alert alert-danger">录入信息不完整</span>');
			$('#msgerror').modal('show');
		}
	});	
/*	$("#fixedbo").off("click").on("click",'td',function(){	//冒泡事件解决动态添加元素无效情况	
		var fobj=$(this);
		$('#tdinfo').val(tdinfo);
		tdinfo=fobj.text();	
		$('#tdinfo').val(tdinfo);
		changeinfo(fobj);		
	});*/
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