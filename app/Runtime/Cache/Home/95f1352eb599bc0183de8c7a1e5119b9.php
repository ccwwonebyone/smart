<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>智-用户管理</title>
    <!-- <link rel="stylesheet" href="/Public/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/Public/jquery-ui/jquery-ui.theme.min.css"> -->
    <link rel="stylesheet" href="/Public/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/Css/Home/base.css">
    <script src="/Public/jquery-ui/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <!-- <script src="/Public/jquery-ui/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script> -->
    <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>
    <script src="/Public/bootstrap/js/bootstrap3-typeahead.min.js" type="text/javascript" charset="utf-8" async defer></script>
    <!-- 放到最后否则不认识自动补全插件 -->
    <script src="/Public/Js/Home/base.js" type="text/javascript" charset="utf-8"></script>
    <!-- <script src="/Public/jquery-ui/zclip/jquery.zclip.js" type="text/javascript" charset="utf-8"></script> -->
    <script>
        var baseLogout = "<?php echo U('Base/logout');?>";
        var baseGetblnum = "<?php echo U('Base/getblnum');?>";
        var baseAllsearch = "<?php echo U('Base/allsearch');?>";
        var baseYconfig = "<?php echo U('Base/yconfig');?>";
        var baseUpload = "<?php echo U('Base/upload');?>";
        var baseShowsheet = "<?php echo U('Base/showsheet');?>";
        var baseChangesheet = "<?php echo U('Base/changesheet');?>";
        var baseUploadfile = "<?php echo U('Base/uploadfile');?>";
        var baseInsertdata = "<?php echo U('Base/insertdata');?>";
        var baseSubsug = "<?php echo U('Base/subsug');?>";
        var baseChangeinfo = "<?php echo U('Base/changeinfo');?>";
        var baseAutoadd= "<?php echo U('Base/autoadd');?>";
        var baseSearch = "<?php echo U('Base/search');?>";
        var baseDel = "<?php echo U('Base/del');?>";               
        var loginConfig = "<?php echo U('Login/config');?>";
        var loginIndex = "<?php echo U('Login/index');?>";
        var modalToconfig = "<?php echo U('Modal/toconfig');?>";
        var modalYconfig = "<?php echo U('Modal/yconfig');?>";
        var modalImportexcel = "<?php echo U('Modal/importexcel');?>";
        var modalImportwh = "<?php echo U('Modal/importwh');?>";
        var modalSuggest = "<?php echo U('Modal/suggest');?>";
    </script>
    <link rel="stylesheet" href="/Public/Css/Home/admin.css">
    
<script>
  var staffChangeuserinfo = "<?php echo U('Staff/changeuserinfo');?>";
  var staffUserdel = "<?php echo U('Staff/userdel');?>";
  var staffAdduser = "<?php echo U('Staff/adduser');?>";
</script>

<script src="/Public/Js/Home/admin.js" type="text/javascript" charset="utf-8" async defer></script>

</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
   <div class="container">
         <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
            <span class="sr-only">切换导航</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="#">智WORK</a>
          <form class="navbar-form navbar-left" role="search">
         <div class="form-group">
            <input type="text" class="form-control" id="buildname" placeholder="小区名">
            <button type="button" class="btn btn-danger" id="allsearch">全区搜索</button>
            <span class="label label-info">今日总单数：</span>
            <span class="label label-success" id="allbl">0</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="label label-info">剩余总单数：</span>
            <span class="label label-danger" id="resuidbl">0</span>
         </div>        
      </form>  
      </div>
      <div class="collapse navbar-collapse" id="example-navbar-collapse">
         <ul class="nav navbar-nav  navbar-right" id="header">
           <li><a href="<?php echo U('Index/index');?>">主页</a></li>
           <li><a href="<?php echo U('Staff/index');?>">信息管理</a></li>
            <li class="dropdown">
             <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            修改配置 <span class="caret"></span>
             </a>
            <ul class="dropdown-menu">
                <li><a href="#" id="toconfig">今日配置</a></li>
               <li><a href="#" id="yconfig">表格配置</a></li>
             </ul>
             </li>
              <li class="dropdown">
             <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            EXCEL <span class="caret"></span>
             </a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo U('excel/exportexcel');?>">导出今日数据</a></li>
                <li><a href="<?php echo U('excel/exportexcel5');?>">导出5组数据</a></li>
                <li><a href="<?php echo U('excel/exportexcel8');?>">导出8组数据</a></li>
               <li><a href="#" id="importwh">导入维护EXCEL</a></li>
               <li><a href="#" id='importexcel'>导入新增数据</a></li>
             </ul>
             </li>
             <?php echo W('Tdtable/admin');?>
            <li><a href="#" id="logout">退出</a></li>
         </ul>
      </div>
      </div>
</nav>
<!-- 动态加载模态框 -->
<div id="modal"></div>

<div class="container">
<div id="fixadmin">
  	<label class="col-xs-1 btn  btn-danger">详细信息</label>
    <div class="col-xs-11">
        <input type="text" class="form-control" id="tdinfo" placeholder="表格信息 不要随意更改，后果很严重">
    </div>
  </div>
  </div>
  <div class="container">
<table class="table table-bordered table-hover table-condensed " cellspacing="0" width="100%" id="fixedbo">	
  <thead>
	<tr id="linfo">	
		<div class="input-group input-group-sm">
		<th> <input type="text" class="form-control" name='username' id='username' placeholder="用户名"></th>
		<th><input type="password" class="form-control" name='pwd' id='pwd' placeholder="密码"></th>
		<th><input type="text" class="form-control" name='userbck' id='userbck' placeholder="备注"></th>
			<th id='action'>
				<button type="button" class="btn btn-primary" id='usersub'>注册</button>
			</th>
		</div>
	</tr>
  <tr class="btn-primary"> 
    <div>
    <th>序号</th>
    <th>用户名</th>
    <th>备注</th>
    <th>操作</th>
    </div>
  </tr>
  </thead>
  <tbody id="getall">
		<?php echo W('Tdtable/Showuser');?>	
  </tbody>
 </table>
 </div>

<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center">
			<span class="alert alert-info">智工作组--一切尽在掌握中。
         版权所有©づsんáηɡ年、悤悤惘惘。有任何意见或建议，欢迎联系全能的
			<button class="btn btn-warning" id="developer">开发者</button>        
			</span>
		</div>
	</div>	
</div>
<!-- 错误提示模态框 -->
 <div class="modal fade" id="msgerror" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
            <h4 class="modal-title text-center" >
               <span class="label label-danger">信息提示！</span>
            </h4>
         </div>
         <div class="modal-body text-center" id="modalinfo"></div>
         <div class="modal-footer">
            <span  class="btn btn-info" data-dismiss="modal">确定</span>           
         </div>
      </div>
</div>
</div>
</body>
</html>