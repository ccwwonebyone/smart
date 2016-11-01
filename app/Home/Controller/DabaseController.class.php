<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class DabaseController extends Controller {
//创建每天的数据库
	public function autocreat() {
		$ttime = date("Y-m-d");
		$tname = 'smart_main_' . $ttime;
		/*$table=new \Lib\Table;
		$tables=$table->gettables();	//判断是否需要创建数据表*/
		//if(!in_array($tname, $tables)){
		$sql = "CREATE TABLE IF NOT EXISTS `smart`.`$tname` ( `main_id` INT NOT NULL AUTO_INCREMENT COMMENT '自增id' , `main_date` VARCHAR(100) NOT NULL COMMENT '录入日期' , `main_wo` VARCHAR(100) NOT NULL COMMENT '工单号' , `main_add` VARCHAR(100) NULL COMMENT '无用' , `main_tel` TEXT NOT NULL COMMENT '手机号' , `main_address` CHAR(255) NOT NULL COMMENT '地址' , `main_nu` VARCHAR(100) NULL COMMENT '无用' , `main_ytime` DATETIME NOT NULL COMMENT '预约日期' , `main_ctime` DATETIME NOT NULL COMMENT '结单日期' , `main_cf` SMALLINT NOT NULL COMMENT '重复值' , `main_mark` VARCHAR(100) NOT NULL COMMENT '备注' , `main_status` CHAR(255) NOT NULL COMMENT '状态' , `main_res` TEXT NOT NULL COMMENT '提单结果' , `main_staff` VARCHAR(100) NOT NULL COMMENT '处理人员' , `main_type` VARCHAR(100) NOT NULL COMMENT '单子状态' , `main_yres` CHAR(100) NOT NULL COMMENT '预处理' , `main_yyb` TEXT NOT NULL COMMENT '预约信息2' , `main_tda` TEXT NOT NULL COMMENT '提单信息1' , `main_tdb` TEXT NOT NULL COMMENT '提单信息2' ,  `main_dosta` SMALLINT NOT NULL COMMENT '工单处理状态' ,PRIMARY KEY (`main_id`)) ENGINE = MyISAM COMMENT = '日工作表';";
		M()->execute($sql);
		//}
	}
//删除超时的数据库
	public function dorptable() {
		$tables = new \Lib\Table;
		$table = $tables->rettimeable(); //超过1个月的单子会被删除
	}
}