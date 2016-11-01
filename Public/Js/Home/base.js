$(function(){
 	//登出  
	$('#logout').click(function(){
		$.ajax({
			type:'GET',
			url:baseLogout,
			data:{
				action:'logout',
			},
			success:function(data){
				if(data==1){
					location.href = loginIndex;
				}
			}
		});
	});
//单量数据
function showblnum(){
	$.ajax({
		url:baseGetblnum,
		success:function (data) {
			 $blnum=new Array(); 
			 $blnum=data.split('-');
			 $('#allbl').text($blnum[0]);
			 $('#resuidbl').text($blnum[1]);
		}
	});
}
showblnum();
setInterval(showblnum, 100000);//100s执行一次
//全区搜索
$('#allsearch').click(function(){
	var buildname=$.trim($('#buildname').val());
	var allsearch=$('#allsearch').text();
	if(buildname!=''&&allsearch!='搜索中...'){
		$('#allsearch').text('搜索中...');
		$.ajax({
			type:'GET',
			url:baseAllsearch+'?action=allsearch',
			dataType: "json", 
			data:{
				buildname:buildname
			},
			success:function (data) {
				$('#allsearch').text('全区搜索');
				if(data!=1){
					var ancontent = '';
					for (var i = data.length - 1; i >= 0; i--) {
						ancontent +='<p class="alert-danger">'+data[i]+'</p>';
					}
					$('#modalinfo').html(ancontent);
					$('#msgerror').modal('show');
				}else{
					$('#modalinfo').html('<span class="alert alert-danger">无法查找到该小区！</span>');
					$('#msgerror').modal('show');					
				}	
			}
		})
	}else{
		$('#modalinfo').html('<span class="alert alert-danger">告诉智需要搜索什么呀！</span>');
		$('#msgerror').modal('show');	
	}
});
//加载今日配置的模态框
$('#toconfig').click(function(){
	$.ajax({
		url:modalToconfig,
		success:function(content){
			if(content!=''){
				$('#modal').html(content);
				ctconfig();
				$('#myModal').modal('show');
			}
		}
	});
});
//加载永久配置的模态框
$('#yconfig').click(function(){
	$.ajax({
		url:modalYconfig,
		success:function(content){
			if(content!=''){
				$('#modal').html(content);
				yconfig();
				$('#configure').modal('show');
			}
		}
	});
});
//加载新增数据的模态框
$('#importexcel').click(function(){
	$.ajax({
		url:modalImportexcel,
		success:function(content){
			if(content!=''){
				$('#modal').html(content);
				importexcel();
				$('#newbl').modal('show');
			}
		}
	});
});
//加载维护表格数据的模态框
$('#importwh').click(function(){
	$.ajax({
		url:modalImportwh,
		success:function(content){
			if(content!=''){
				$('#modal').html(content);
				importwh();
				$('#import').modal('show');
			}
		}
	});
});
//加载维护表格数据的模态框
$('#developer').click(function(){
	$.ajax({
		url:modalSuggest,
		success:function(content){
			if(content!=''){
				$('#modal').html(content);
				importwh();
				$('#suggestinfo').modal('show');
				subsug();
			}
		}
	});
});
//临时修改当天的配置
function ctconfig(){
	$('#one').click(function(){
		name=$.trim($(this).text());
		if(name=='刘胡平'){
			$(this).text('陈金荣');
		}else{
			$(this).text('刘胡平');
		}
	});
	$('#two').click(function(){
		name=$.trim($(this).text());
		if(name=='吴密情'){
			$(this).text('吕配云');
		}else{
			$(this).text('吴密情');
		}
	});
	var i=4;
	$('#add').click(function(){
		$('#sif').append("<div class='form-group'>"+
      				"<input type='text' class='form-control' id='name"+i+"' placeholder='原人员'>=>"+
      				"<input type='text' class='form-control' id='bname"+i+"' placeholder='替换人员'>"+
   				"</div>");
		i++;
	});
	$('#agsub').click(function(){
		var one=$('#one').text();
		var two=$('#two').text();
		var sif=new Array();
		for(var j=0;j<i;j++){
			var k=j+1;
			var	name=$.trim($('#name'+k).val());
			var	bname=$.trim($('#bname'+k).val());
			if(name,bname!=''){
				eval("sif["+j+"]='"+name+":"+bname+"'");				
			}
		}
		sif[i-1]='one:'+one;
		sif[i]='two:'+two;
		var conf=JSON.stringify(sif);
		$.ajax({
			type:'POST',
			url:loginConfig+'?info=config',
			data:{
				conf:conf,
			},
			dataType:'json',
			success:function(data){
				if(data==1){
					$('#agtext').html('<span class="alert alert-danger">修改配置成功</span>');
					setTimeout("$('#myModal').modal('hide')", 1000);
				}
			}
		});
	});
}
//永久修改配置
function yconfig(){
	var i=4;
   $('#yadd').click(function(){
		$('#ysif').append("<div class='form-group'>"+
      				"<input type='text' class='form-control' id='yname"+i+"' placeholder='原人员'>=>"+
      				"<input type='text' class='form-control' id='ybname"+i+"' placeholder='替换人员'>"+
   				"</div>");
		i++;
	});
	$('#yagsub').click(function(){
		var sif=new Array();
		for(var j=0;j<i;j++){
			var k=j+1;
			var	name=$.trim($('#yname'+k).val());
			var	bname=$.trim($('#ybname'+k).val());
			if(name,bname!=''){
				eval("sif["+j+"]='"+name+":"+bname+"'");				
			}
		}
		var conf=JSON.stringify(sif);
		$.ajax({
			type:'POST',
			url:baseYconfig+'?info=yconfig',
			data:{
				conf:conf,
			},
			dataType:'json',
			success:function(data){
				if(data==1){
					$('#yconfigtext').html('<span class="alert alert-danger">修改配置成功</span>');
					setTimeout("$('#configure').modal('hide')", 1000);
				}
			}
		});
	});
}
//维护表格上传
function importwh(){
	$('#whsub').click(function(){
		var formData = new FormData();
		formData.append('file', $('#upfile')[0].files[0]);
		/*var file_data = $('#upfile').prop('files')[0];   
   		var form_data = new FormData();                  
    	form_data.append('file', file_data);*/
    	$('#modalinfo').html("<span class='alert alert-info'>正在读取表格数据,请耐心等待...</span>");
		$.ajax({
			type:'POST',
			url:baseUpload,
			cache:false,							//cache设置为false，上传文件不需要缓存
			data:formData,
			processData: false,						//processData设置为false。因为data值是FormData对象，不需要对数据做处理。
    		contentType: false,						//contentType设置为false。因为是由<form>表单构造的FormData对象，且已经声明了属性enctype="multipart/form-data"，所以这里设置为false。
    		success:function(data){
    			if(data==1){
    				$.ajax({
    					url:baseShowsheet,
    					success:function(content) {
    						$('#import').modal('hide');
    						$.ajax({
								url:modal+'change',
								success:function(cont){
									if(content!=''){
										$('#modal').html(cont);
										$('#showsheet').append(content);
    									$('#change').modal('show');
    									choosesheet();
    									changeshhet ();
									}
								}
							});
    					}
    				});
    			}else{
				$('#modalinfo').html('<span class="alert alert-danger">'+data+'</span>');
    			}
    		}
		});
	});
}
//选择表格
function choosesheet(){
	$('.sheet').click(function(){ 
		var con=$(this).text();
		var content=$('#needchange').text();
		$('#needchange').text(con);
		$(this).text(content);
	});
}
//修改表格
function changeshhet () {
	$('#changesheet').click(function(){
		var need=$('#needchange').text().split('-')[0];
		$('#modalinfo').html("<span class='alert alert-info'>正在导入表格数据,请耐心等待...</span>");
		$.ajax({
			type:'GET',
			url:baseChangesheet,
			data:{
				need:need
			},
			success:function(data){
				if(data==1){
					$('#modalinfo').html("<span class='alert alert-info'>导入表格数据成功！</span>");
					setTimeout(window.location.reload(),1000);
				}
			}
		})
	});
}
//新增数据的上传
function importexcel(){
	$('#newblsub').click(function(){
		var formData = new FormData();
		formData.append('file', $('#newinfofile')[0].files[0]);
		/*var file_data = $('#upfile').prop('files')[0];   
   		var form_data = new FormData();                  
    	form_data.append('file', file_data);*/
    	$('#modalinfo').html("<span class='alert alert-info'>正在导入表格数据，请耐心等待....</span>");
		$.ajax({
			type:'POST',
			url:baseUploadfile,
			cache:false,							//cache设置为false，上传文件不需要缓存
			data:formData,
			processData: false,						//processData设置为false。因为data值是FormData对象，不需要对数据做处理。
    		contentType: false,						//contentType设置为false。因为是由<form>表单构造的FormData对象，且已经声明了属性enctype="multipart/form-data"，所以这里设置为false。
    		success:function(data){
    			if(data==1){
    				$.ajax({
    					type:'GET',
    					url:baseInsertdata,
    					data:{
    						action:'import'
    					},
    					success:function(res){
    						if(res==1){
    							$('#modalinfo').html("<span class='alert alert-info'>导入表格数据成功！</span>");
    							setTimeout(window.location.reload(),1000);
    						}
    					}
    				})
    			}else{
					$('#modalinfo').html('<span class="alert alert-danger">'+data+'</span>');
    			}
    		}
		});
	});
}
//发送email
function subsug() {
	$('#sugsub').click(function(){
		var suggest=$.trim($('#suggest').val());
		if(suggest!=''){
			$('#suginfo').html('<span class="alert alert-warning">您的意见建议正在通知开发者！请耐心等待不要关闭...</span>');		
			$.ajax({
				type:'POST',
				url:baseSubsug+'?action=subsug',
				data:{
					suggest:suggest
				},
				success:function (data) {
					 if(data==1){
					 	$('#suginfo').html('<span class="alert alert-info">您的意见建议开发者已知晓会尽快回复您的！</span>');
					 	setTimeout("$('#suggestinfo').modal('hide')",1000);
					 } 
				}
			});
		}else{
			$('#suginfo').html('<span class="alert alert-warning">发送内容不能为空...</span>');
			setTimeout("$('#suggestinfo').modal('hide')",1000);
		}
	});
}
});