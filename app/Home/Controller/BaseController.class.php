<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class BaseController extends Controller {
	//烟花程序
	public function yanhua() {
		$this->display();
	}
	public function addstaff($address, $getstaff) {
		$time = date('Y-m-d');
		require __NROOT__ . "Public/files/" . $time . "_staconf.php"; //引入人员配置文件
		$a = M('a');
		//D人员 F判断是否为置换小区 H 判断催装人员
		$res = $a->field('gims_D,gims_F,gims_H')->
			where("LOCATE(`gims_B`,'$address')>0 OR gims_B LIKE '%$address%'")->select();
		if (empty($res)) {echo 4;exit();}
		if ($getstaff == 1) {
			//维修
			if ($res[0]['gims_d'] == '注:代维(京信)') {return 3;exit();}
			if ($res[0]['gims_d'] == '铁通') {return '海之欣';exit();}
			if ($res[0]['gims_f'] == '置换小区' && $res[0]['gims_d'] != '铁通') {
				return $res[0]['gims_d'] . '-' . $res[0]['gims_f'];
				exit();
			}
			if ($res[0]['gims_d'] != '铁通' && $res[0]['gims_f'] != '置换小区') {
				$keys = array_keys($staff);
				if (in_array($res[0]['gims_d'], $keys)) {
					return $staff[$res[0]['gims_d']];
				} else {
					return $res[0]['gims_d'];
				}
				exit();
			}
		} else {
			//催装
			if ($res[0]['gims_d'] == '注:代维(京信)') {
				return 3;
				exit();
			}
			if ($res[0]['gims_f'] == '置换小区') {
				return '海之欣';
				exit();
			}
			if ($res[0]['gims_d'] != '注:代维(京信)' && $res[0]['gims_f'] != '置换小区') {
				if (preg_match('/5组/', $res[0]['gims_h'])) {
					//8组代表吕、吴
					return $staff['one'];
				}
				if (preg_match('/8组/', $res[0]['gims_h'])) {
					//5组代表刘、陈
					return $staff['two'];
				}
			}
		}
	}
//获取小区维护人员级备注
	public function dostaff($staff) {
		if ($staff == 3) {
			$data['staff'] = '代维京信';
		} elseif ($staff == '海之欣') {
			$data['staff'] = '海之欣';
			$data['mark'] = '铁通置换';
		} else {
			$info = explode('-', $staff);
			$data['staff'] = $info[0];
			if ($info[1] == '置换小区') {
				$data['mark'] = '铁通置换';
			}
		}
		return $data;
	}
	//自动获取人员
	public function autoadd() {
		if ($_GET['address']) {
			//自动添加人员
			$getstaff = $_GET['staff'];
			$address = $_GET['address'];
			echo self::addstaff($address, $getstaff);
		}
	}
	public function changeinfo() //修改信息
	{
		if ($_GET['qclass']) {
			$zd = str_replace('q', 'main_', $_GET['qclass']);
			$time = date('Y-m-d');
			$main = M("main_$time");
			$data[$zd] = $_GET['newinfo'];
			if ($main->where("main_id='{$_GET['qxu']}'")->save($data)) {echo 1;}
		}
	}
	//搜索并输出
	public function search() {
		if ($_POST['search']) {
			$where = '1';
			foreach ($_POST['search'] as $value) {
				$search = explode(':', $value);
				if ($search[1] != '') {
					if ($search[0] == 'tel' || $search[0] == 'address') {
						$where .= " AND main_$search[0] LIKE '%$search[1]%'";
					} else {
						$where .= " AND main_$search[0]='$search[1]'";
					}
				}
			}
			if ($_GET['action'] == 'yysearch') {
				//预约搜索
				$table = A('Table');
				echo $table->showtab($where);
				exit();
			}
			if ($_GET['action'] == 'tdsearch') {
				//提单搜索
				$table = A('Table');
				echo $table->showbltab($where);
				exit();
			}
		}
	}
	//删除数据
	public function del() {
		if ($_GET['qxu']) {
			$time = date('Y-m-d');
			$main = M("main_$time");
			$main->where("main_id={$_GET['qxu']}")->delete();
			echo 1;
		}
	}
	//登出
	public function logout() {
		if ($_GET['action'] == 'logout') {
			session_destroy();
			cookie('name', null);
			cookie('bck', null);
			cookie('chtype', null);
			echo 1;
		}
	}
	//永久更改人员配置
	public function yconfig() {
		if ($_GET['info'] == 'yconfig') {
			$conf = json_decode($_POST['conf']);
			foreach ($conf as $value) {
				if (!empty($value)) {
					$content = explode(':', $value);
					$staff[$content[0]] = $content[1];
				}
			}
			$a = M('a');
			foreach ($staff as $k => $val) {
				$data['gims_D'] = $val;
				$a->where("gims_D='$k'")->save($data);
			}
			echo 1;
		}
	}
	//文件上传
	public function upload() {
		/*if(is_uploaded_file($_FILES['file']['tmp_name'])){
			$upfile=$_FILES["file"];
			//获取数组里面的值
			$name=$upfile["name"];//上传文件的文件名
			$type=$upfile["type"];//上传文件的类型
			$size=$upfile["size"];//上传文件的大小
			$tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径
			echo $name.'<br>'.$type.'<br>'.$tmp_name.'<br>'.$size;
			}else{
				echo 'what';
		*/
		$upload = new \Think\Upload(); //实例化上传类
		$upload->maxSize = 3145728; //设置附件上传大小
		$upload->exts = array('xls', 'xlsx'); //设置附件上传类型
		$upload->subName = ''; //设置上传子目录
		$upload->saveName = 'weihu'; //设置上传文件名
		$upload->replace = true; //设置是否覆盖上传文件
		$upload->rootPath = './Public/files/'; //设置上传文件位置
		//$upload->savePath = __NROOT__.'Public/files/'; // 设置附件上传目录
		// 上传文件
		$info = $upload->uploadOne($_FILES['file']);
		if (!$info) {
			// 上传错误提示错误信息
			echo $upload->getError();
		} else {
			// 上传成功
			echo 1;
		}
	}
	//上传数据表格
	public function uploadfile() {
		$upload = new \Think\Upload(); //实例化上传类
		$upload->maxSize = 3145728; //设置附件上传大小
		$upload->exts = array('xls', 'xlsx'); //设置附件上传类型
		$upload->subName = ''; //设置上传子目录
		$upload->saveName = 'data'; //设置上传文件名
		$upload->replace = true; //设置是否覆盖上传文件
		$upload->rootPath = './Public/files/'; //设置上传文件位置
		//$upload->savePath = __NROOT__.'Public/files/'; // 设置附件上传目录
		// 上传文件
		$info = $upload->uploadOne($_FILES['file']);
		if (!$info) {
			// 上传错误提示错误信息
			echo $upload->getError();
		} else {
			// 上传成功
			echo 1;
		}
	}
	//显示表格名
	public function showsheet() {
		$excel = A('Excel');
		echo $excel->showsheet();
	}
	//修改表格数据
	public function changesheet() {
		$excel = A('Excel');
		if ($_GET['need'] == 'all') {
			echo $excel->excel();
		} else {
			echo $excel->changesheet($_GET['need']);
		}
	}
//处理得到的表格信息
	public function dodata() {
		$excel = A('Excel');
		$data = $excel->getdata();
		foreach ($data as $val) {
			$dodata['wo'] = $val['wo'];
			$dodata['address'] = $val['address'];
			$dodata['ytime'] = $val['ytime'];
			if ($val['tel1'] == $val['tel2']) {
				$dodata['tel'] = $val['tel1'];
			} else {
				$dodata['tel'] = $val['tel1'] . ' ' . $val['tel2'];
			}
			if ($val['address'] != '') {
				$address = $val['address'];
				if (preg_match('/安装不及时/', $val['reason'])) {
					$getstaff = 2;
				} else {
					$getstaff = 1;
				}
				$staff = self::addstaff($address, $getstaff);
				$info = self::dostaff($staff);
				foreach ($info as $key => $value) {
					$dodata[$key] = $value;
				}
			}
			$needdata[] = $dodata;
			$dodata = '';
		}
		return $needdata;
	}
//写入上传excel的数据
	public function insertdata() {
		if ($_GET['action'] == 'import') {
			$info = self::dodata();
			$tname = date("Y-m-d");
			$main = M("main_$tname");
			foreach ($info as $value) {
				if ($value['staff'] != '代维京信') {
					$data['main_ctime'] = date("Y-m-d H:i:s", (strtotime($value['ytime']) + 3600 * 22));
					$data['main_dosta'] = 1;
					$data['main_type'] = '已派发';
					foreach ($value as $key => $val) {
						$data['main_' . $key] = $val;
					}
				}
				$main->add($data);
				$data = '';
			}
			echo 1;
		}
	}
//发送邮件
	public function subsug() {
		if ($_GET['action'] == 'subsug') {
			$mail = A('Mail');
			$to = '552090340@qq.com';
			$subject = '智工作组使用情况反馈';
			$content = '反馈者:' . $_SESSION['bck'] . '<br>';
			$content .= '反馈意见:' . $_POST['suggest'];
			$mail->sendmail($to, $subject, $content);
			echo 1;
		}
	}
//全区搜索
	public function allsearch() {
		if ($_GET['action'] == 'allsearch') {
			$buildname = $_GET['buildname'];
			$c = M('c');
			$res = $c->field('gims_C')->
				where("LOCATE(`gims_A`,'$buildname')>0 OR gims_A LIKE '%$buildname%'")->select();
			if (!empty($res)) {
				foreach ($res as $value) {
					$temp[] = $value['gims_c'];
				}
				$res = array_unique($temp);
				foreach ($res as $val) {
					$resbuildname[] = urlencode($val);
				}
				$resbuild = json_encode($resbuildname);
				$resbuildname = urldecode($resbuild);
				echo $resbuildname;
			} else {
				echo 1;
			}

		}
	}
//获取工单总数及为处理工单数
	public function getblnum() {
		//if($_GET['action']=='getblnum'){
		$time = date('Y-m-d');
		$main = M("main_$time");
		$allblnum = $main->count();
		$reblnum = $main->where("main_type='已派发'")->count();
		echo $allblnum . '-' . $reblnum;
		//}
	}
}