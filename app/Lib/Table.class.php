<?php
namespace Lib;
use Think\Controller;
use Think\Model;

class Table extends Controller{
//获取数据库所有表名
	public function gettables()
	{
		$tables=M()->query("SHOW TABLES");
		foreach ($tables as $table) {
			foreach ($table as $value) {
				$tab[]=$value;
			}
		}
		return $tab;
	}
//获取匹配之后的表名
	public function gettab($preg)
	{
		$pre="/$preg/";
		$tables=self::gettables();
		foreach ($tables as $value) {
			if(preg_match($pre, $value)){
				$value=preg_replace($pre,'', $value);
				$res[]=$value;
			}
		}
		return $res;
	}
//获取需要删除的表名
	public function gettimere()
	{
		$time=date("Y-m-d",strtotime("last month"));
		$table=self::gettab('smart_main_');
		foreach ($table as $value) {
			if($value < $time){$tabname[]='smart_main_'.$value;}
		}
		if(!empty($tabname)){
			return $tabname;
		}
	}
//删除表
	public function rettimeable()
	{
		$tabname=self::gettimere();
		if(!empty($tabname)){
			foreach ($tabname as $value) {
				$sql="DROP TABLE `$value`";
				M()->execute($sql);
			}
		}
	}
}