<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class StaffController extends Controller {
	public function index()
	{
		$this->display('staff');
	}
//修改信息
	public function changeinfo()								
	{
		if($_GET['qclass']){
			$zd=str_replace('q', 'staff_', $_GET['qclass']);
			$staff=M('staff');
			$data[$zd]=$_GET['newinfo'];
			if($staff->where("staff_id='{$_GET['qxu']}'")->save($data)){ echo 1;}			
		}	
	}
	//删除数据
	public function del()
	{
		if($_GET['qxu']){
			$staff=M('staff');
    		$staff->where("staff_id={$_GET['qxu']}")->delete();
    		echo 1;
		}
	}
//写入师傅信息
	public function insert()		//写入数据
	{
		if($_GET['action']=='stasub'){
			$data['staff_name']=$_GET['staffname'];		//师傅姓名
			$data['staff_tel']=$_GET['tel'];		//师傅手机号
			$data['staff_mark']=$_GET['mark'];	//师傅所属区域
			$staff=M('staff');				//实例化今天的表
			$staff->add($data);					//写入数据
			$res=$staff->field('staff_id')->where("staff_name='{$_GET['staffname']}'")->find();
			echo $res['staff_id'];
		}	
	}
	//搜索并输出
	public function search()
	{
		if($_POST['search']){
			$where=	'1';
			foreach ($_POST['search'] as $value) {
				$search=explode(':', $value);
				if($search[1]!=''){
					if($search[0]=='tel'){
						$where .=" AND staff_$search[0] LIKE '%$search[1]%'";
					}else{
						$where .=" AND staff_$search[0]='$search[1]'";
					}					
				}
			}
			$table=A('Table');
			echo $table->showstaffinfo($where);
			exit();		
		}
	}
//用户管理界面
	public function admin()
	{
		$this->display();
	}
//添加用户
	public function adduser()
	{
		if($_GET['action']=='usersub'){
			$user=M('user');
			$count=$user->where("user_name='{$_GET['username']}' OR user_bck='{$_GET['userbck']}'")->count();
			if($count>0){
				echo 0;
				exit();
			}
			$data['user_name']=$_GET['username'];
			$data['user_pwd']=sha1($_GET['pwd']);
			$data['user_bck']=$_GET['userbck'];
			$user->add($data);					//写入数据
			$res=$user->field('user_id')->where("user_name='{$_GET['username']}'")->find();
			echo $res['user_id'];
		}
	}
//删除数据
	public function userdel()
	{
		if($_GET['qxu']){
			$user=M('user');
    		$user->where("user_id={$_GET['qxu']}")->delete();
    		echo 1;
		}
	}
//改变用户信息
	public function changeuserinfo()								//修改信息
	{
		if($_GET['qclass']){
			$zd=str_replace('q', 'user_', $_GET['qclass']);
			$user=M('user');
			$data[$zd]=$_GET['newinfo'];
			if($user->where("user_id='{$_GET['qxu']}'")->save($data)){ echo 1;}			
		}	
	}
}