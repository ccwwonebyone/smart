$(function(){
	$('#sub').click(function(){
		var name=$.trim($('#username').val());
		var pwd=$.trim($('#pwd').val());
		if(name,pwd!=''){
			$('#error').removeClass('btn-info').removeClass('btn-danger').addClass('btn-success').text('正在登陆中...');
			$.ajax({
				type:'GET',
				url:loginLogin+'?action=login',
				data:{
					name:name,
					pwd:pwd
				},
				success:function(data){
					if(data==1){
						location.href=loginChoose;
					}else{
						$('#error').removeClass('btn-info').addClass('btn-danger').text('帐号或密码错误');
					}
				}
			})
		}else{
			$('#error').removeClass('btn-info').addClass('btn-danger').text('帐号或密码不能为空');
		}
	});
	$('#username').focus();
	$("body").keyup(function (event) {  
		if (event.which == 13){  
			$("#sub").trigger("click");  
        }  
	}); 
});