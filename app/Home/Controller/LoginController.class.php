<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class LoginController extends Controller {
	public function index() {
		$this->display('login');
	}
//登录功能保存session及cookie
	public function login() {
		if ($_GET['action'] == 'login') {
			$user = M('user');
			$name = $_GET['name'];
			$pwd = sha1($_GET['pwd']);
			$res = $user->field('user_bck')->where("user_name='$name' AND user_pwd='$pwd'")->find();
			if (!empty($res)) {
				$_SESSION['name'] = $_GET['name'];
				$_SESSION['bck'] = $res['user_bck'];
				cookie('name', $_GET['name'], 60 * 60 * 14);
				cookie('bck', $res['user_bck'], 60 * 60 * 14);
				echo 1;
			} else {
				echo 0;
			}
			/*if($_SESSION['bck']=='PXM'){
				                $mail=A('Mail');
				                $mail->sendmail('552090340@qq.com','智工作组使用情况反馈','今天软件被很好的使用了！');
			*/
		}
	}
	//调用模版
	public function _before_choose() {
		if (cookie('name') && cookie('bck') && cookie('chtype')) {
			$_SESSION['name'] = cookie('name');
			$_SESSION['bck'] = cookie('bck');
			$_SESSION['chtype'] = cookie('chtype');
		}
		if ($_SESSION['name'] == '' || $_SESSION['bck'] == '') {
			$this->display(T('Login/login'));
			exit();
		} else {
			if ($_SESSION['chtype'] == '预约') {
				$this->display(T('Index/index'));
				exit();
			}
			if ($_SESSION['chtype'] == '提单') {
				$this->display(T('Bl/index'));
				exit();
			}
		}
	}
	public function choose() {
		$this->display();
	}
	//写入配置文件
	static function creatconf($staconf) {
		$time = date('Y-m-d');
		$sta = fopen(__NROOT__ . "Public/files/" . $time . "_staconf.php", "w");
		$config = '<?php '; //$config的内容就是要写入配置文件的内容
		$config .= "\r\n";
		$config .= "//此为配置文件每天登陆时清空非今日数据";
		$config .= "\r\n"; //   n是用来换行的。
		$config .= '$staff=array(';
		$config .= "\r\n";
		foreach ($staconf as $key => $value) {
			$config .= "'" . $key . "'=>'" . $value . "',";
			$config .= "\r\n";
		}
		$config .= ');';
		$config .= "\r\n";
		$config .= '?>';
		fwrite($sta, $config);
		fclose($sta);
		unset($config);
	}
//调用员工配置控制器写入配置
	public function config() {
		if ($_GET['info'] == 'config') {
			$conf = json_decode($_POST['conf']);
			foreach ($conf as $value) {
				if (!empty($value)) {
					$content = explode(':', $value);
					$staconf[$content[0]] = $content[1];
				}
			}
			$file = A('File');
			$file->frestaf();
			self::creatconf($staconf);
			echo 1;
		}
	}
//保存工作类型，用于展示界面
	public function chtype() {
		if ($_GET['chtype']) {
			$_SESSION['chtype'] = $_GET['chtype'];
			cookie('chtype', $_GET['chtype'], 60 * 60 * 14);
			echo 1;
		}
	}
	public function demo() {
		//cookie('chtype','提单',60*60*18);
		echo cookie('chtype');
	}
}