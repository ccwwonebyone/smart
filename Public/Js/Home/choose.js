jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
    return this;
}
//Use the above function as:
$('#choose').center();
$(window).resize(function(){
   $('#choose').center();
});
$(function(){
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
	$('#sub').click(function(){
		var chtype=$('#xyy').text();
		$.ajax({
			type:'GET',
			url:loginChtype,
			data:{
				chtype:chtype,
			}
		});
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
					location.href = home;
				}
			}
		});
	});
	$('#blw').click(function(){
		var chtype=$(this).text();
		$.ajax({
			type:'GET',
			url:loginChtype,
			data:{
				chtype:chtype,
			},
			success:function(data){
				if(data==1){
					location.href = bl;
				}
			}
		});
	});
})