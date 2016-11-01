<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class ChooseController extends Controller {
//写入人员配置文件
	public function config() {
		if ($_GET['info'] == 'config') {
			$conf = json_decode($_POST['conf']);
			foreach ($conf as $value) {
				if (!empty($value)) {
					$content = explode(':', $value);
					$stacont[$content[0]] = $content[1];
				}
			}
			$myfile = fopen(__NROOT__ . 'Public/files/staconf.php', "w");
			$config = "<?php "; //$config的内容就是要写入配置文件的内容
			$config .= "\r\n";
			$config .= "//此为配置文件每天登陆时清空前一天的数据";
			$config .= "\r\n"; //   /n是用来换行的。
			$config .= '$staff=array(';
			$config .= "\r\n";
			foreach ($stacont as $key => $value) {
				$config .= "'" . $key . "'=>'" . $value . "',";
				$config .= "\r\n";
			}
			$config .= ');';
			$config .= "\r\n";
			$config .= "?>";
			fwrite($myfile, $config);
			fclose($myfile);
			echo 1;
		}
	}
}