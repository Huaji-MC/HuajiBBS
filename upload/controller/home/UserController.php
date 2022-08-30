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

namespace controller\home;
use \lib\Mailer;
defined('ACCESS') or die('You have no access.');

class UserController extends BaseController {
	public function login() {
		if(IS_LOGIN) {
			fail('你已经登录过了，不需要再登录了。');
		} else {
			$this->title('登录')->show('login');
		}
	}
	
	public function login_ajax() {
		$account = P('post.account','sql');
		$password = P('post.password','sql');
		
		if(C('login_email_code')) {
			$code = P('post.code','int');
			if(time() > $_SESSION['email_code_valid_time'])
				puts(0,'验证码已过期，请重新获取～');
			if($code != $_SESSION['email_code'] || $_SESSION['email_code_type'] != 'login')
				puts(0,'验证码不正确');
			if($account !== $_SESSION['email_code_for_account'])
				puts(0,'该验证码只对 ' . $_SESSION['email_code_for_account'] . ' 用户有效，请重新获取验证码！');
			unset($_SESSION['email_code'],$_SESSION['email_code_type'],$_SESSION['email_code_for_account'],$_SESSION['email_code_email'],$_SESSION['email_code_valid_time'],$_SESSION['email_code_send_time']);
		}
		
		$accountType = filter_var($account,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$login = M('user')->login($account,$password,$accountType);
		if($login['status']) {
			cookie('token',$login['token']);
		}
		putd($login);
	}
	
	public function register() {
		if(IS_LOGIN) {
			fail('你已经登录过了，不需要再注册了。');
		} else {
			$this->title('注册')->show('register');
		}
	}
	
	public function register_ajax() {
		$username = P('post.username','sql');
		$password = P('post.password');
		$email = P('post.email');
		
		if(C('register_email_code')) {
			$code = P('post.code','int');
			if(time() > $_SESSION['email_code_valid_time'])
				puts(0,'验证码已过期，请重新获取～');
			if($code != $_SESSION['email_code'] || $_SESSION['email_code_type'] != 'register')
				puts(0,'验证码不正确');
			if($email !== $_SESSION['email_code_for_account'])
				puts(0,'该验证码只对 ' . $_SESSION['email_code_for_account'] . ' 邮箱有效，请重新获取验证码！');
		}
		
		//用户名判断
		if(mb_strlen($username) > 15)
			puts(0,'用户名不符合要求：必须少于 15 字符');
		
		//密码强度判断（只能包含字母和数字且为8-20字符）
		if(! preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)([0-9a-zA-Z]){8,20}$/',$password))
			puts(0,'密码不符合要求：只能包含字母和数字且在 8～20 字符之间');
		
		//邮箱判断
		if(! filter_var($email,FILTER_VALIDATE_EMAIL))
			puts(0,'邮箱格式不正确');
		
		$register = M('user')->register($username,$password,$email);
		if($register['status']) {
			cookie('token',$register['token']);
		}
		unset($_SESSION['email_code'],$_SESSION['email_code_type'],$_SESSION['email_code_for_account'],$_SESSION['email_code_email'],$_SESSION['email_code_valid_time'],$_SESSION['email_code_send_time']);
		putd($register);
	}
	
	public function exit() {
		M('user')->uid(UID)->exit();
		cookie('token',null,time() - 3600);
		success('退出登录成功');
	}
	
	public function forget() {
		$this->title('找回密码')->show('forget');
	}
	
	public function forget_ajax($step) {
		if($step == 1) { //第一步：验证邮箱
			$account = P('post.account','sql');
			$code = P('post.code','int');
			if(time() > $_SESSION['email_code_valid_time'])
				puts(0,'验证码已过期，请重新获取～');
			if($code != $_SESSION['email_code'] || $_SESSION['email_code_type'] != 'forget')
				puts(0,'验证码不正确');
			if($account !== $_SESSION['email_code_for_account'])
				puts(0,'该验证码只对 ' . $_SESSION['email_code_for_account'] . ' 用户有效，请重新获取验证码！');
			$_SESSION['forget_pass_email'] = true;
			unset($_SESSION['email_code'],$_SESSION['email_code_type'],$_SESSION['email_code_for_account'],$_SESSION['email_code_valid_time'],$_SESSION['email_code_send_time']);
			puts(1,'验证通过');
		} else { //第二步：修改密码
			$password = P('post.password');
			if(! $_SESSION['forget_pass_email'])
				puts(0,'请先进行邮箱验证！');
			if(! preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)([0-9a-zA-Z]){8,20}$/',$password))
				puts(0,'新密码不符合要求：只能且必须包含字母和数字且在 8～20 字符之间');
			M('user')->email($_SESSION['email_code_email'])->password = encrypt($password);
			unset($_SESSION['email_code_email']);
			puts(1,'修改成功');
		}
	}
	
	public function email_code_ajax(string $type) {
		$account = P('post.account','sql');
		if(($type == 'login' && C('login_email_code')) || $type == 'forget') {
			if(filter_var($account,FILTER_VALIDATE_EMAIL)) {
				$user = M('user')->email($account)->select()->getOne();
			} else {
				$user = M('user')->username($account)->select()->getOne();
			}
			if(! $email = $user['email']) {
				puts(0,'邮箱或用户不存在！');
			}
			if(time() - $_SESSION['email_code_send_time'] < 120) {
				puts(0,'邮件发送间隔为 120 秒！');
			}
			$username = $user['username'];
		} else if($type == 'register' && C('register_email_code')) {
			if(filter_var($account,FILTER_VALIDATE_EMAIL)) {
				$email = $account;
			} else {
				puts(0,'邮箱格式不正确');
			}
			$username = '新用户';
		} else {
			puts(0,'未知操作');
		}
		
		$code = rand(100000,999999);
		$_SESSION['email_code'] = $code;
		$_SESSION['email_code_type'] = $type;
		$_SESSION['email_code_for_account'] = $account;
		$_SESSION['email_code_email'] = $email;
		$_SESSION['email_code_valid_time'] = time() + 600;
		$_SESSION['email_code_send_time'] = time();
		
		$config = C();
		$mailer = new Mailer($config);
		$content = str_replace('{code}',$code,$config['email_code_content']);
		$content = str_replace('{username}',$username,$content);
		$content = str_replace('{action}',['login' => '登录','register' => '注册','forget' => '重置密码'][$type],$content);
		$mailer->to($email)->sendEmail($config['email_code_title'],$content);
		puts(1,'发送成功');
	}
	
	public function index(int $uid) {
		$user = M('user')->uid($uid)->select()->getOne() or fail('用户不存在');
		$this->set('user',$user)->set('show','index')->title('首页中心 - ' . $user['username'] . '的主页')->show('index');
	}
	
	public function threads(int $uid,int $page) {
		$user = M('user')->uid($uid)->select()->getOne() or fail('用户不存在');
		$threads = M('thread')->uid($uid)->order('time','desc')->page($page)->list();
		$this->set('user',$user)->set('threads',$threads)->set('show','threads')->title('主题列表 - ' . $user['username'] . '的主页')->show('threads');
	}
	
	public function comments(int $uid,int $page) {
		$user = M('user')->uid($uid)->select()->getOne() or fail('用户不存在');
		$comments = M('comment')->uid($uid)->order('time','desc')->page($page)->list();
		$this->set('user',$user)->set('comments',$comments)->set('show','comments')->title('评论列表 - ' . $user['username'] . '的主页')->show('comments');
	}
	
	public function praises(int $uid,int $page) {
		$user = M('user')->uid($uid)->select()->getOne() or fail('用户不存在');
		$praises = M('threadpraise')->uid($uid)->order('time','desc')->page($page)->list();
		$count = M('threadpraise')->uid($uid)->total();
		$this->set('user',$user)->set('praises',$praises)->set('count',$count)->set('show','praises')->title('点赞列表 - ' . $user['username'] . '的主页')->show('praises');
	}
	
	public function rewards(int $uid,int $page) {
		$user = M('user')->uid($uid)->select()->getOne() or fail('用户不存在');
		$rewards = M('threadpoint')->uid($uid)->order('time','desc')->page($page)->list();
		$count = M('threadpoint')->uid($uid)->total();
		$this->set('user',$user)->set('rewards',$rewards)->set('count',$count)->set('show','rewards')->title('打赏列表 - ' . $user['username'] . '的主页')->show('rewards');
	}
	
	public function message(int $type,int $page) {
		if(! IS_LOGIN) fail('你还没有登录');
		
		$unread = M('message')->unread(UID);
		$messages = M('message')->uid2(UID)->type($type)->order('time','desc')->page($page)->list();
		$count = M('message')->uid2(UID)->type($type)->total();
		$this->set('messages',$messages)->set('count',$count)->set('unread',$unread)->title('消息中心')->show('message');
	}
	
	public function setting(int $type) {
		if(! IS_LOGIN) fail('你还没有登录');
		if(! $this->perm->user_setting) fail('你所属用户组无权进行修改资料操作');
		
		$this->set('type',$type)->title('基本设置')->show('setting');
	}
	
	public function set_avatar_ajax() {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		if(! $this->perm->user_setting) puts(0,'你所属用户组无权进行修改资料操作');
		
		$path = P('post.path','sql');
		if(is_file(ROOT_PATH . $path)) {
			M('user')->uid(UID)->update(['avatar' => $path]);
			puts(1,'上传成功');
		} else {
			puts(0,'文件不存在');
		}
	}
	
	public function set_password() {
		if(! IS_LOGIN) fail('你还没有登录');
		if(! $this->perm->user_setting) puts(0,'你所属用户组无权进行修改资料操作');
		
		$old = P('post.old','sql');
		$new = P('post.new','sql');
		$again = P('post.again','sql');
		if($new != $again) fail('两次密码不一致');
		if(encrypt($old) != M('user')->uid(UID)->password) fail('原密码输入错误');
		if(! preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)([0-9a-zA-Z]){8,20}$/',$new))
			fail('密码不符合要求：只能且必须包含字母和数字且在 8～20 字符之间');
		
		M('user')->uid(UID)->update(['password' => encrypt($new)]);
		success('修改成功');
	}
	
	public function set_data() {
		if(! IS_LOGIN) fail('你还没有登录');
		if(! $this->perm->user_setting) puts(0,'你所属用户组无权进行修改资料操作');
		
		$description = P('post.description',['sql','html']);
		if(mb_strlen($description) > 50) fail('介绍不能超过 50 字符');
		
		M('user')->uid(UID)->update(['description' => $description]);
		success('修改成功');
	}
}