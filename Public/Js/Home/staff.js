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
				url:staffChangeinfo,
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
//搜索选项
function search(){
    var search=new Array();
    search[0]="name:"+$.trim($('#sstaffname').val());
    search[1]="tel:"+$.trim($('#stel').val());
    search[2]="mark:"+$.trim($('#smark').val());
    $.ajax({
    	type:'POST',
    	url:staffSearch+'?action=search',
    	data:{
    		search:search
    	},
    	success:function(content){
    		$('#sstaffname').val('');
    		$('#stel').val('');
    		$('#smark').val('');
    		$('#getall').html(content);
			clicktd();
			clickdel();
    	}
    });
}
//删除数据
function clickdel(){
	$('.del').click(function(){		
		if(confirm('确定删除么')){
			var obj=$(this);
			var qxu=obj.parents().children('.qxu').text();
			$.ajax({
				type:'GET',
				url:staffDel ,
				data:{
					qxu:qxu
				},
				success:function (data) {
					if(data==1){
						obj.parent().parent().remove();
					}
				}
			});
		}

	});
} 
/***********************************
基础事件默认执行
************************************/
clicktd();
clickdel();
/*************************************
静态页面方法
*************************************/
//录入功能
$('#stasub').click(function(){	
		var staffname  =$.trim($('#staffname').val());
		var tel =$.trim($('#tel').val());
		var mark =$.trim($('#mark').val());
		if(staffname!=''&&tel!=''&&mark!=''){
			$.ajax({
				type:'GET',
				url:staffInsert+'?action=stasub',
				data:{
					'staffname':staffname,
					'tel':tel,
					'mark':mark,
				},
				success:function(data){			//输出后的参数带q方便更改
					if(data!=''){
						$('#staffname').val('');
						$('#tel').val('');
						$('#mark').val('');
						$('#getall').prepend("<tr class='alert alert-success'>"+
							"<td class='qxu' style='display:none;'>"+data+"</td>"+
							"<td class='qname'>"+staffname+"</td>"+
							"<td class='qtel'>"+tel+"</td>"+
							"<td class='qmark'>"+mark+"</td>"+
							"<th><button type='button' class='btn  btn-primary btn-xs del'>删除</button></th>"+
							'</tr>');
						clicktd();
						clickdel();  				
					}
				}
			});
		}
	});
//点击实现搜索功能		
    $('#staffsearch').click(function(){
    	search();
	});
});