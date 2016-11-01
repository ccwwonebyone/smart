<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>智-登录</title>
	<link rel="stylesheet" href="/Public/bootstrap/css/bootstrap.min.css">
	<script src="/Public/jquery-ui/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>
	<link rel="stylesheet" href="/Public/Css/Home/choose.css">
	
<script>
   var loginConfig = "<?php echo U('Login/config');?>";
   var loginChtype = "<?php echo U('Login/chtype');?>";
   var bl = "<?php echo U('Bl/index');?>";
   var home = "<?php echo U('Index/index');?>";
</script>
<script src="/Public/Js/Home/choose.js" type="text/javascript" charset="utf-8" async defer></script>

</head>
<body>

<div id="choose">
	<div class="btn-group">
		<span class="btn btn-lg btn-danger" data-toggle="modal" data-target="#myModal" id='xyy'>预约</span>
		<span class="btn btn-lg btn-primary" id="blw">提单</span>
	</div>
</div>
<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content" id="bgc">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  &times;
            </button>
            <h4 class="modal-title text-center" >
               预约设置【直接提交会默认执行】<span class="label label-warning">设置很重要</span>
            </h4>
         </div>
         <div class="modal-body text-center">
        	<form class="form-inline"  role="form" id='sif'>
        		<div class="form-group">
      				<input type="text" class="form-control" id="name1" placeholder="原人员">=>
      				<input type="text" class="form-control" id="bname1" placeholder="替换人员">
   				</div>
   				<div class="form-group">
      				<input type="text" class="form-control" id="name2" placeholder="原人员">=>
      				<input type="text" class="form-control" id="bname2" placeholder="替换人员">
   				</div>
   				<div class="form-group">
      				<input type="text" class="form-control" id="name3" placeholder="原人员">=>
      				<input type="text" class="form-control" id="bname3" placeholder="替换人员">
   				</div>
			</form>
			<div class="btn-group " data-toggle="buttons">
	            <label class="btn btn-primary" id='one'>刘胡平</label>
	   			<label class="btn btn-primary" id='two'>吴密情</label>
			</div>
			<span class="btn btn-danger" id="add">增加</span>
         </div>
         <div class="modal-footer">
            <span  class="btn btn-default" 
               data-dismiss="modal">关闭
            </span>
            <span class="btn btn-primary" id='sub'>提交</span>
         </div>
      </div>
</div>
</div>

</body>
</html>