<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class IndexController extends Controller {
	//检查是否复合登录条件
	public function _before_index() {
		$dabase = A('Dabase');
		$dabase->autocreat(); //自动创建工作数据表
		$dabase->dorptable(); //自动删除超过一个月的工作表
		if (cookie('name') && cookie('bck') && cookie('chtype')) {
			$_SESSION['name'] = cookie('name');
			$_SESSION['bck'] = cookie('bck');
			$_SESSION['chtype'] = cookie('chtype');
		}
		if ($_SESSION['name'] == '' || $_SESSION['bck'] == '') {
			$this->redirect('Login/index');
			exit();
		} else {
			if ($_SESSION['chtype'] == '提单') {
				$this->redirect('Bl/index');
				exit();
			}
		}
	}
	//加载页面
	public function index() {
		$this->display();
	}
	public function insert() //写入数据
	{
		if ($_GET['action'] == 'sub') {
			$tname = date("Y-m-d");
			require __NROOT__ . "Public/files/" . $tname . "_staconf.php";
			$date = date('j');
			$time = date('H:i:s');
			if ($time > '18:00:00') {
				$newtime = date("j", strtotime("+1 day"));
			} else {
				$newtime = date("j");
			}
			if ($_GET['mark'] == '铁通置换') {
				$yyb = "HZX 我处核实此小区已割接置换为铁通小区，用户属性已变更为铁通用户。派单人：" . $_SESSION['bck'] . "";
			} elseif ($_GET['staff'] != $staff['one'] && $_GET['staff'] != $staff['two']) {
				$staffinfo = M('staff');
				$yinfo = $staffinfo->field('staff_tel,staff_mark')->where("staff_name='{$_GET['staff']}'")->find();
				$xtime = date('H:i');
				$yyb = "HZX上门时间： " . $date . "号" . $xtime . "联系用户电话忙" . $newtime . "号上门/上门人员：" . $_GET['staff'] . "/上门人员联系方式：" . $yinfo['staff_tel'] . "/代维公司：铁通（" . $yinfo['staff_mark'] . "） 派单人：" . $_SESSION['bck'] . "/";
			} else {
				$yyb = "HZX " . $date . "号我处查看为催装，已发装机组处理。 派单人：" . $_SESSION['bck'] . "";
			}
			$indate = date('m.d');
			$data['main_date'] = $indate; //日期
			$data['main_wo'] = $_GET['wo']; //工单
			//$data['add']='';					//无用的单元格
			$data['main_tel'] = $_GET['tel']; //手机号
			//$data['nu']='';						//无用的单元格
			$data['main_address'] = $_GET['address']; //地址
			$data['main_ytime'] = $_GET['ytime']; //预约时间
			$data['main_ctime'] = date("Y-m-d H:i:s", (strtotime($data['main_ytime']) + 3600 * 22)); //超时时间
			//$data['main_cf']='';				//重复值不写入有excel表格计算
			$data['main_mark'] = $_GET['mark']; //备注 铁通置换
			$data['main_status'] = $_GET['sta']; //单子状态 ''代表已派发 1代表已提单 2代表先提单 其余的由自己决定
			$data['main_res'] = $_GET['res']; //师傅回单内容
			$data['main_staff'] = $_GET['staff']; //处理人员
			$data['main_type'] = $_GET['ylr']; //单子最终状态
			$data['main_yres'] = $newtime . '号上门'; //单子预处理
			$data['main_yyb'] = $yyb; //预约信息
			$data['main_tda'] = '拨号'; //拨号信息
			$main = M("main_$tname"); //实例化今天的表
			$main->add($data); //写入数据
			$res = $main->field('main_id')->where("main_wo='{$_GET['wo']}'")->find();
			echo $res['main_id'] . '_' . $_GET['wo'] . ' ' . $_GET['tel'] . ' ' . $_GET['address'] . ' ' . $data['main_yres'] . '_' . $yyb;
			unset($data);
		}
	}
	public function suredata() //确认数据
	{
		if ($_GET['action'] == 'suredata') {
			$tname = date("Y-m-d");
			require __NROOT__ . "Public/files/" . $tname . "_staconf.php";
			$date = date('j');
			$time = date('H:i:s');
			if ($time > '18:00:00') {
				$newtime = date("j", strtotime("+1 day"));
			} else {
				$newtime = date("j");
			}
			if ($_GET['mark'] == '铁通置换') {
				$yyb = "HZX 我处核实此小区已割接置换为铁通小区，用户属性已变更为铁通用户。派单人：" . $_SESSION['bck'] . "";
			} elseif ($_GET['staff'] != $staff['one'] && $_GET['staff'] != $staff['two']) {
				$staffinfo = M('staff');
				$yinfo = $staffinfo->field('staff_tel,staff_mark')->where("staff_name='{$_GET['staff']}'")->find();
				$xtime = date('H:i');
				$yyb = "HZX上门时间： " . $date . "号" . $xtime . "联系用户电话忙" . $newtime . "号上门/上门人员：" . $_GET['staff'] . "/上门人员联系方式：" . $yinfo['staff_tel'] . "/代维公司：铁通（" . $yinfo['staff_mark'] . "） 派单人：" . $_SESSION['bck'] . "/";
			} else {
				$yyb = "HZX " . $date . "号我处查看为催装，已发装机组处理。 派单人：" . $_SESSION['bck'] . "";
			}
			$indate = date('m.d');
			$data['main_date'] = $indate; //日期
			$data['main_tel'] = $_GET['tel']; //手机号
			$data['main_address'] = $_GET['address']; //地址
			$data['main_mark'] = $_GET['mark']; //备注 铁通置换
			$data['main_status'] = $_GET['status']; //单子状态
			$data['main_res'] = $_GET['res']; //师傅回单内容
			$data['main_staff'] = $_GET['staff']; //处理人员
			$data['main_type'] = $_GET['type']; //单子最终状态
			$data['main_yres'] = $newtime . '号上门'; //单子预处理
			$data['main_yyb'] = $yyb; //预约信息
			$data['main_tda'] = '拨号'; //拨号信息
			$data['main_dosta'] = 0; //工单处理状态
			$main = M("main_$tname"); //实例化今天的表
			$main->where("main_id='{$_GET['xu']}'")->save($data); //写入数据
			echo $_GET['xu'] . '_' . $_GET['wo'] . ' ' . $_GET['tel'] . ' ' . $_GET['address'] . ' ' . $data['main_yres'] . '_' . $yyb;
			unset($data);
		}
	}
	//输出需要的信息
	public function getmore() {
		if ($_GET['action'] == 'more') {
			$tname = date("Y-m-d");
			$main = M("main_$tname");
			$res = $main->field('main_wo,main_tel,main_address,main_yres,main_yyb')->
				where("main_id='{$_GET['qxu']}'")->find();
			echo $res['main_wo'] . ' ' . $res['main_tel'] . ' ' . $res['main_address'] . ' ' . $res['main_yres'] . '_' . $res['main_yyb'];
			unset($res);
		}
	}
	public function demo() {
		$staffinfo = M('staff');
		$_GET['sta'] = '许海林';
		$yinfo = $staffinfo->field('staff_tel,staff_mark')->where("staff_name='{$_GET['sta']}'")->find();
		print_r($yinfo);
	}
}