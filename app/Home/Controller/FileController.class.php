<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class FileController extends Controller {
//获取目标文件所有的文件名
	public function getallfile($dir) {
		$handle = opendir($dir);
		//定义用于存储文件名的数组
		$array_file = array();
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$array_file[] = $file; //输出文件名
			}
		}
		closedir($handle);
		return $array_file;
	}
	//得到所有需要被删除的配置文件
	public function getrefile($array_file) {
		$time = date('Y-m-d');
		foreach ($array_file as $value) {
			$file = explode('.', $value);
			if ($file[1] == 'php') {
				$name = explode('_', $file[0]);
				if ($name[0] != $time) {$refile[] = $value;}
			}
		}
		return $refile;
	}
	//删除所有需要被删除的文件
	public function refile($dir, $refile) {
		if (is_array($refile)) {
			foreach ($refile as $value) {
				unlink($dir . '/' . $value);
			}
		} else {
			unlink($dir . '/' . $refile);
		}
	}
	//删除文件
	public function frestaf() {
		$dir = __NROOT__ . 'Public/files';
		$array_file = self::getallfile($dir);
		$refile = self::getrefile($array_file);
		self::refile($dir, $refile);
	}
}