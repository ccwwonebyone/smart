<?php 
namespace Home\Widget;
use Think\Controller;
use Think\Model;

class TdtableWidget extends Controller{
	//输出预约表格			所有的class与数据库字段都有关系 默认加载已派发数据
	public function showtab()
	{
		$table=A('Table');
		return $table->showtab();
	}
	//输出提单表格				所有的class与数据库字段都有关系		默认加载已派发数据
	public function showbltab()
	{
		$table=A('Table');
		return $table->showbltab();
	}
	//输出已有员工名字
	public function showstaff()
	{
		$time=date('Y-m-d');
    	$main=M("main_$time");
    	$staffs=$main->field('main_staff')->select();
    	foreach ($staffs as $value) {
    		$staff[]=$value['main_staff'];
    	}
    	$staff=array_unique($staff);
    	$con ='';
    	foreach ($staff as $value) {
    		$con .="<li><a href='#' class='wostaff'>$value</a></li>";
    	}
    	return $con;
    	unset($con);
	}
	public function showstaffinfo()
	{
		$table=A('Table');
		return $table->showstaffinfo($_GET['need']);
	}
	public function admin()
	{
		if($_SESSION['bck']=='AD'){
			return '<li><a href="'.U('Staff/admin').'" id="userman">用户管理</a></li>';
		}
	}
	public function showuser()
	{
		$table=A('Table');
		return $table->showuser($_GET['need']);
	}	
}