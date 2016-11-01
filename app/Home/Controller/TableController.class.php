<?php 
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class TableController extends Controller{
	//输出预约表格			所有的class与数据库字段都有关系
	public function showtab($where="main_type='已派发'")
	{
		$time=date('Y-m-d');
    	$main=M("main_$time");
    	$showtab=$main->where($where." AND main_dosta!=1")->order('main_ctime ASC')->select();
    	$shownotab=$main->where('main_dosta=1')->order('main_ctime ASC')->select();
    	$con='';
		foreach ($shownotab as $tab) {
			$con .='<tr class="alert alert-info showdata">';
	    	$con .="<td class='qxu' style='display:none;'>".$tab['main_id']."</td>";
	    	$con .="<td class='qwo'>".$tab['main_wo']."</td>";
	    	$con .="<td class='qtel'>".$tab['main_tel']."</td>";
	    	$con .="<td class='qaddress'>".$tab['main_address']."</td>";
	    	$con .="<td class='qytime'>".$tab['main_ytime']."</td>";
	    	$con .="<td class='qmark'>".$tab['main_mark']."</td>";
	    	$con .="<td class='qstatus'>".$tab['main_status']."</td>";
	    	$con .="<td class='qres'>".$tab['main_res']."</td>";
	    	$con .="<td class='qstaff'>".$tab['main_staff']."</td>";
	    	$con .="<th class='qtype btn btn-primary'>".$tab['main_type']."</th>";
	    	$con .="<th>
	  		<div class='btn-group btn-group-xs czm'>
			  	<button type='button' class='btn  btn-primary sure'>确认</button>
				<button type='button' class='btn  btn-primary repair'>维修</button>
				<button type='button' class='btn  btn-primary delete'>删除</button>
			</div>
			</th>";
	    	$con .='<tr>';
		}
    	foreach ($showtab as $tab) {
	    	$con .='<tr class="alert alert-success">';
	    	$con .="<td class='qxu' style='display:none;'>".$tab['main_id']."</td>";
	    	$con .="<td class='qwo'>".$tab['main_wo']."</td>";
	    	$con .="<td class='qtel'>".$tab['main_tel']."</td>";
	    	$con .="<td class='qaddress'>".$tab['main_address']."</td>";
	    	$con .="<td class='qytime'>".$tab['main_ytime']."</td>";
	    	$con .="<td class='qmark'>".$tab['main_mark']."</td>";
	    	$con .="<td class='qstatus'>".$tab['main_status']."</td>";
	    	$con .="<td class='qres'>".$tab['main_res']."</td>";
	    	$con .="<td class='qstaff'>".$tab['main_staff']."</td>";
	    	$con .="<th class='qtype btn btn-primary'>".$tab['main_type']."</th>";
	    	$con .="<th>
	  		<div class='btn-group btn-group-xs czm'>
			  	<button type='button' class='btn  btn-primary other'>其他</button>
				<button type='button' class='btn  btn-primary repair'>维修</button>
				<button type='button' class='btn  btn-primary delete'>删除</button>
			</div>
			</th>";
	    	$con .='<tr>';			   					
    	}	
    	return $con;
	}
	//输出提单表格				所有的class与数据库字段都有关系		默认加载已派发数据
	public function showbltab($where="main_type='已派发'")
	{	
		$time=date('Y-m-d');
    	$main=M("main_$time");
    	$where .=" AND main_dosta != 1";
    	$showtab=$main->where($where)->order('main_ctime ASC')->select();
    	$con='';
    	foreach ($showtab as $tab) {
	    	$con .='<tr class="alert alert-success">';
	    	$con .="<td class='qxu' style='display:none;'>".$tab['main_id']."</td>";
	    	$con .="<td class='qwo'>".$tab['main_wo']."</td>";
	    	$con .="<td class='qtel'>".$tab['main_tel']."</td>";
	    	$con .="<td class='qaddress'>".$tab['main_address']."</td>";
	    	$con .="<td class='qctime'>".$tab['main_ctime']."</td>";
	    	$con .="<td class='qmark'>".$tab['main_mark']."</td>";
	    	$con .="<td class='qstatus'>".$tab['main_status']."</td>";
	    	$con .="<td class='qres'>".$tab['main_res']."</td>";
	    	$con .="<td class='qstaff'>".$tab['main_staff']."</td>";
	    	$con .="<th class='qtype btn btn-primary'>".$tab['main_type']."</th>";
	    	$con .="<th>
	  		<div class='btn-group btn-group-xs czm'>
			  	<button type='button' class='btn  btn-primary call'>".$tab['main_tda']."</button>
			 	<button type='button' class='btn  btn-primary other'>其他</button>
				<button type='button' class='btn  btn-primary repair'>维修</button>
			</div>
			</th>";
	    	$con .='<tr>';			   					
    	}	
    	return $con;
	}
//输出员工系信息
	public function showstaffinfo($where="1")
	{	
    	$staff=M('staff');
    	$showtab=$staff->where($where)->select();
    	$con='';
    	foreach ($showtab as $tab) {
	    	$con .='<tr class="alert alert-success">';
	    	$con .="<td class='qxu' style='display:none;'>".$tab['staff_id']."</td>";
	    	$con .="<td class='qname'>".$tab['staff_name']."</td>";
	    	$con .="<td class='qtel'>".$tab['staff_tel']."</td>";
	    	$con .="<td class='qmark'>".$tab['staff_mark']."</td>";
	    	$con .="<th><button type='button' class='btn  btn-primary btn-xs del'>删除</button></th>";
	    	$con .='<tr>';			   					
    	}	
    	return $con;
	}
//输出用户信息
	public function showuser($where="1")
	{	
    	$user=M('user');
    	$showuser=$user->where($where)->order('user_id asc')->select();
    	$con='';
    	foreach ($showuser as $users) {
	    	$con .='<tr class="alert alert-success">';
	    	$con .="<th class='qxu btn btn-warning'>".$users['user_id']."</th>";
	    	if($users['user_bck']=='AD'){
	    		$con .="<th class='qname'>".$users['user_name']."</th>";
	    		$con .="<th class='qbck'>".$users['user_bck']."</th>";
	    	}else{
		    	$con .="<td class='qname'>".$users['user_name']."</td>";
		    	$con .="<td class='qbck'>".$users['user_bck']."</td>";
	    	}
	    	if($users['user_bck']=='AD'){
	    		$con .="<th><button type='button' class='btn  btn-danger btn-xs undel'>无法删除</button></th>";
	    	}else{
	    		$con .="<th><button type='button' class='btn  btn-primary btn-xs del'>删除</button></th>";
	    	}
	    	$con .='<tr>';			   					
    	}	
    	return $con;
	}
}