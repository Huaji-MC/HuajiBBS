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
defined('ACCESS') or die('You have no access.');

class ConfigController extends BaseController {
	public function __construct() {
		parent::__construct();
		require LIB_PATH . 'function/file.php';
	}
	
	public function server() {
		$cacheHome = getDirSize(HOME_PATH . 'view/template_c/');
		$cacheAdmin = getDirSize(ADMIN_PATH . 'view/template_c/');
		$cache = sizeFormat($cacheHome + $cacheAdmin);
		$this->set('cache',$cache)->title('服务器信息')->show('server');
	}
	
	public function clear_cache() {
		deleteDir(TEMPLATE_PATH . C('current_template') . '/home/template_c/');
		deleteDir(TEMPLATE_PATH . C('current_template') . '/admin/template_c/');
	}
	
	public function site() {
		$this->title('站点配置')->show('site');
	}
	
	public function site_ajax() {
		$name = P('post.name','html');
		$logo = P('post.logo','html');
		$tail = P('post.title_end_content','html');
		setConfig(['site_name' => $name,'site_logo' => $logo,'title_end_content' => $tail]);
		puts(1,'修改成功');
	}
	
	public function bbs() {
		$this->title('社区配置')->show('bbs');
	}
	
	public function bbs_ajax() {
		$avatar = P('post.avatar','html');
		$icon = P('post.icon','html');
		$open_register = P('post.open_register',null,false) ? 1 : 0;
		$login_email_code = P('post.login_email_code',null,false) ? 1 : 0;
		$register_email_code = P('post.register_email_code',null,false) ? 1 : 0;
		$email_code_title = P('post.email_code_title');
		$email_code_content = P('post.email_code_content');
		$point_name = P('post.point_name','html');
		$create_thread_frequency = P('post.create_thread_frequency','int');
		setConfig([
			'default_avatar' => $avatar,
			'default_forum_icon' => $icon,
			'open_register' => $open_register,
			'login_email_code' => $login_email_code,
			'register_email_code' => $register_email_code,
			'email_code_title' => $email_code_title,
			'email_code_content' => $email_code_content,
			'point_name' => $point_name,
			'create_thread_frequency' => $create_thread_frequency,
		]);
		puts(1,'修改成功');
	}
	
	public function smtp() {
		$this->title('SMTP配置')->show('smtp');
	}
	
	public function smtp_ajax() {
		$host = P('post.host');
		$port = P('post.port');
		$username = P('post.username');
		$password = P('post.password');
		$email = P('post.email');
		$name = P('post.name');
		setConfig([
			'smtp_host' => $host,
			'smtp_port' => $port,
			'smtp_username' => $username,
			'smtp_password' => $password,
			'smtp_email' => $email,
			'smtp_name' => $name,
		]);
		puts(1,'修改成功');
	}
}