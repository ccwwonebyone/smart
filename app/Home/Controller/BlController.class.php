<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class BlController extends Controller {
//检查是否登录，登录的类型
	public function _before_index() {
		if (cookie('name') && cookie('bck') && cookie('chtype')) {
			$_SESSION['name'] = cookie('name');
			$_SESSION['bck'] = cookie('bck');
			$_SESSION['chtype'] = cookie('chtype');
		}
		if ($_SESSION['name'] == '' || $_SESSION['bck'] == '' || $_SESSION['chtype'] == '') {
			$this->redirect('Login/index');
			exit();
		} else {
			if ($_SESSION['chtype'] == '预约') {
				$this->redirect('Index/index');
				exit();
			}
		}
	}
	public function index() {
		$this->display();
	}
//拨号处理
	public function call() {
		if ($_GET['action'] == 'call') {
			$time = date('Y-m-d');
			$main = M("main_$time");
			$res = $main->field('main_tda,main_tdb')->where("main_id='{$_GET['xu']}'")->find();
			if ($res['main_tda'] == '拨号') {
				echo 1;
				exit();
			} else {
				echo $res['main_tdb'];
			}
		}
	}
//返回其他按钮的内容
	public function getother() {
		if ($_GET['action'] == 'getother') {
			$time = date('Y-m-d');
			$main = M("main_$time");
			$res = $main->field('main_tdb')->where("main_id='{$_GET['xu']}'")->find();
			if (!empty($res['main_tdb'])) {
				echo $res['main_tdb'] . '_ 结单人：' . $_SESSION['bck'];
			} else {
				echo 1;
			}
		}
	}
}