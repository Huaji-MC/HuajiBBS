<?php
// +----------------------------------------------------------------------
// | HuajiBBS
// +----------------------------------------------------------------------
// | Copyright © 2022 HuajiMC. All rights reserved.
// +----------------------------------------------------------------------
// | License: GNU General Public License 3.0
// +----------------------------------------------------------------------
// | Author: 滑稽mc（HuajiMC） <HuajiMC@126.com>
// +----------------------------------------------------------------------

namespace lib;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception;

class Mailer {
	private $to;
	private $smtp;
	
	public function __construct($smtp) {
		$this->smtp = $smtp;
	}
	
	public function to($email) {
		$this->to = $email;
		return $this;
	}
	
	public function sendEmail($title,$detail) {
		require LIB_PATH . 'Mailer/src/Exception.php';
		require LIB_PATH . 'Mailer/src/PHPMailer.php';
		require LIB_PATH . 'Mailer/src/SMTP.php';
		
		$mail = new PHPMailer(true);
		
		try {
			//服务器配置
			$mail->CharSet ='UTF-8';
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
			$mail->Host = $this->smtp['smtp_host'];
			$mail->SMTPAuth = true;
			$mail->Username = $this->smtp['smtp_username'];
			$mail->Password = $this->smtp['smtp_password'];
			$mail->SMTPSecure = 'ssl';
			$mail->Port = $this->smtp['smtp_port'];
		
			$mail->setFrom($this->smtp['smtp_email'], $this->smtp['smtp_name']);
			$mail->addAddress($this->to, '');
			$mail->addReplyTo($this->to, '');
		
			//主体
			$mail->isHTML(true);
			$mail->Subject = $title;
			$mail->Body = $detail;
			$mail->AltBody = $detail;
		
			$mail->send();
			
			unset($this->to);
			
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
}