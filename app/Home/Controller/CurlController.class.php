<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;

class CurlController extends Controller {
	public function index() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://localhost/thinkphp/index.php/Home/Login/login");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$res = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		curl_close($ch);

	}
	public function session() {
		$url = "http://localhost/thinkphp/index.php/Home/Index/index";
		$post_data = array(
			"username" => "小兰",
		);
		$cookie_file = tempnam('./temp', 'cookie');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！
		curl_setopt($ch, CURLOPT_POST, 1);
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_exec($ch);
		curl_close($ch);

		$lurl = "http://localhost/thinkphp/index.php/Home/Infor/index";
		$ch = curl_init($lurl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		$output = curl_exec($ch);
		preg_match("/<li>(.*)<\/li>/i", $output, $arr);

		var_dump($arr);
		curl_close($ch);

	}
	public function demo() {
		$url = "http://211.103.0.97/webgisams/";
		$post_data = array(
			"j_username" => "nj-huangxiaobin",
			"j_password" => "hxb@NJ123456",
			"loginCode" => "",
		);
		$cookie_file = tempnam('./temp', 'cookie');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 我们在POST数据哦！
		curl_setopt($ch, CURLOPT_POST, 1);
		// 把post的变量加上
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);

		curl_exec($ch);
		curl_close($ch);

		$lurl = "http://211.103.0.97/webgisams/authen/toMainPage.do";
		$ch = curl_init($lurl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		$output = curl_exec($ch);
		curl_close($ch);

	}
	public function post_302() {
		$ip = $_SERVER['REMOTE_ADDR'];
		$head = array(
			'X-FORWARDED-FOR:' . $ip,
			'CLIENT-IP:' . $ip,
			'Accept-Language: zh-cn',
			'Accept-Encoding:gzip,deflate',
			'Connection: Keep-Alive',
			'Cache-Control: no-cache',
		);
		function post($url, $head = false, $foll = 1, $ref = false, $post) {
			$curl = curl_init(); // 启动一个CURL会话
			if ($head) {
				curl_setopt($curl, CURLOPT_HTTPHEADER, $head); //模似请求头
			}
			curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
			@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $foll); // 使用自动跳转
			if ($ref) {
				curl_setopt($curl, CURLOPT_REFERER, $ref); //带来的Referer
			} else {
				curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
			}

			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
			curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']); // 存放Cookie信息的文件名称
			curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']); // 读取上面所储存的Cookie信息
			curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); //解释gzip
			curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
			if ($foll == 1) {
				curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
			} else {
				curl_setopt($curl, CURLOPT_HEADER, 1); // 显示返回的Header区域内容
			}
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
			$tmpInfo = curl_exec($curl); // 执行操作
			if (curl_errno($curl)) {echo 'Errno' . curl_error($curl);}
			curl_close($curl); // 关键CURL会话
			$tmpInfo = preg_replace('/<li>/', 'js', $tmpInfo);
			return $tmpInfo; // 返回数据
		}
		$lurl = "http://211.103.0.97/webgisams/baseOrderMgmt/toOrderPage.do?levelType=13&workflowType=GeneralOrder&needFollow=true&dataId=6314";
		$post_data = array(
			"j_username" => "nj-huangxiaobin",
			"j_password" => "hxb@NJ123456",
			"loginCode" => "",
		);
		echo post($lurl, $post_data);
	}
}
