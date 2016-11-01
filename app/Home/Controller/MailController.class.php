<?php 
namespace Home\Controller;
use Think\Controller;
Vendor('email.phpmailer');
Vendor('email.smtp');

class MailController extends Controller{
	/**
	 * @param to  string 收件人 
	 * @param subject string 标题	
	 * @param content string 内容
	 * @return no return
	 */
	public function sendmail($to,$subject,$content)
	{
		$mail  = new \PHPMailer(); 
		$mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
		$mail->IsSMTP();                            // 设定使用SMTP服务
		$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
		//$mail->SMTPSecure = "ssl";                  // SMTP 安全协议	163无ssl 开启后会显示smtp无法连接
		$mail->Host       = "smtp.163.com";       // SMTP 服务器
		$mail->Port       = 25;                    // SMTP服务器的端口号
		$mail->Username   = "13852292859@163.com";  // SMTP服务器用户名
		$mail->Password   = "z06148795";        // SMTP服务器密码	不是邮箱的密码
		$mail->SetFrom('13852292859@163.com', '智工作组');    // 设置发件人地址和名称
		$mail->AddReplyTo("13852292859@163.com","反馈者"); // 设置邮件回复人地址和名称
		$mail->Subject    = $subject;                     // 设置邮件标题
		$mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";  // 可选项，向下兼容考虑         
		$mail->MsgHTML($content);            // 设置邮件内容
		$mail->AddAddress($to, "反馈者");             //设置收件人邮箱，名称
		//$mail->AddAttachment("images/phpmailer.gif"); // 附件 
		$mail->Send();
		/*if(!$mail->Send()) {
		    echo "发送失败：" . $mail->ErrorInfo;
		} else {
		    echo "恭喜，邮件发送成功！";
		}*/
	}
}
