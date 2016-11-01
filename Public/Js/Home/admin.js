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
		changeuserinfo(fobj);
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
function changeuserinfo(fobj){	
	$('#tdinfo').unbind('blur').blur(function(){				//关于事件绑定问题
		var newinfo=$.trim($('#tdinfo').val());
		if(newinfo!=fobj.text()){							
			var need=fobj.attr('class').toString();
			var qclass=need.split(' ')[0];						//防止有多个class 取第一个
			var qxu=fobj.parents().children('.qxu').text();
			$.ajax({
				type:'GET',
				url:staffChangeuserinfo,
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
//删除数据
function clickdel(){
	$('.del').click(function(){		
		if(confirm('确定删除么')){
			var obj=$(this);
			var qxu=obj.parents().children('.qxu').text();
			$.ajax({
				type:'GET',
				url:staffUserdel,
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
//新增功能
$('#usersub').click(function(){	
		var username  =$.trim($('#username').val());
		var pwd =$.trim($('#pwd').val());
		var userbck =$.trim($('#userbck').val());
		if(username!=''&&pwd!=''&&userbck!=''){
			$.ajax({
				type:'GET',
				url:staffAdduser+'?action=usersub',
				data:{
					'username':username,
					'pwd':pwd,
					'userbck':userbck,
				},
				success:function(data){			//输出后的参数带q方便更改
					if(data!=0){
						$('#username').val('');
						$('#pwd').val('');
						$('#userbck').val('');
						$('#getall').after("<tr class='alert alert-success'>"+
							"<td class='qxu btn btn-warning btn-xs'>"+data+"</td>"+
							"<td class='qname'>"+username+"</td>"+
							"<td class='qbck'>"+userbck+"</td>"+
							"<th><button type='button' class='btn  btn-primary btn-xs del'>删除</button></th>"+
							'</tr>');
						clicktd();
						clickdel();  				
					}else{
						$('#modalinfo').html('<span class="alert alert-danger">用户名或备注重复</span>');
						$('#msgerror').modal('show');
					}
				}
			});
		}else {
			$('#modalinfo').html('<span class="alert alert-danger">注册信息不完整</span>');
			$('#msgerror').modal('show');
		}
	});
});