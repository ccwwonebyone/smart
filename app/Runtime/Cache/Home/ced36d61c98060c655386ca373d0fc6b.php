<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>智-登录</title>
	<link rel="stylesheet" href="/Public/bootstrap/css/bootstrap.min.css">
	<script src="/Public/jquery-ui/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>
	<link rel="stylesheet" href="/Public/Css/Home/login.css">
	
<script> 
  var loginLogin = "<?php echo U('Login/login');?>";
  var loginChoose = "<?php echo U('Login/choose');?>";
</script>
<script src="/Public/Js/Home/login.js" type="text/javascript" charset="utf-8" async defer></script>

</head>
<body>

<!-- 模态框（Modal） -->
<div class="modal show" >
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title text-center">登录</h4>
         </div>
         <div class="modal-body">
           <form class="form center-block">
            <div class="form-group">
              <input type="text" class="form-control" id="username" name="username" placeholder="登录帐号">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="pwd" name="pwd" placeholder="登录密码">
            </div>
           </form>
        </div>
        <div class="modal-footer">
            <div class="text-right">
              <span class="btn btn-sm btn-info" id="error">现在开启你的智能工作!</span>       
              <span class="btn btn-primary" id="sub">立刻登录</span>
            </div>
        </div>
      </div>
  </div>
</div>

</body>
</html>