<?php
return array(
	//'SHOW_PAGE_TRACE' =>true, // 显示页面Trace信息
	// 开启路由
	'URL_ROUTER_ON'   => true, 
	//URL模式，去掉index.php
    //'URL_MODEL'=>2,
    //路由伪静态设置 默认为html 可更换为其他的
    //'URL_HTML_SUFFIX'=>'html',
	'AUTOLOAD_NAMESPACE' => array( 			//建立应用核心函数目录Lib
	   'Lib' => APP_PATH.'Lib',
	 ),
	//数据库连接
	'DB_TYPE'=>'mysql',
	'DB_HOST'=>'localhost',
	'DB_USER'=>'root',
	'DB_PWD'=>'123456',
	'DB_NAME'=>'smart',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'smart_',
	'DB_CHARSET'=> 'UTF8', 
);