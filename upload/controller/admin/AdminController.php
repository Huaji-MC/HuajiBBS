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

namespace controller\admin;
use core\Controller;
defined('ACCESS') or die('You have no access.');

class AdminController extends Controller {
	public function login() {
		$this->show('login');
	}
	
	public function login_ajax() {
		$account = P('post.account','sql');
		$password = P('post.password','sql');
		
		$accountType = filter_var($account,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$user = M('user')->$accountType($account)->select()->getOne();
		if($user && $user['password'] == encrypt($password) && $user['gid'] == 3) {
			$_SESSION['admin'] = $user['uid'];
			puts(1,'登录成功');
		} else {
			puts(0,'用户名或密码错误');
		}
	}
	
	public function logout() {
		unset($_SESSION['admin']);
		success('退出成功',url('admin','login'));
	}
}