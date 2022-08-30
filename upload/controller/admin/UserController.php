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

class UserController extends BaseController {
	public function index() {
		$groups = M('usergroup')->list();
		$this->set('groups',$groups)->title('用户管理')->show('index');
	}
	
	public function search_ajax() {
		$user = M('user')->setClear(false);
		$uid = P('post.uid','int',false) and $user = $user->uid($uid);
		$username = P('post.username','sql',false) and $user = $user->username($username);
		$email = P('post.email','sql',false) and $user = $user->email($email);
		$gid = P('post.gid','int',false) and $user = $user->and('gid = ' . $gid);
		$start = strtotime(P('post.start','sql',false)) ?: 0 and $user = $user->from($start);
		$end = strtotime(P('post.end','sql',false)) ?: time() and $user = $user->to($end);
		
		$page = P('post.page','int');
		$limit = P('post.limit','int');
		putd(['status' => 1,'msg' => '查询成功','count' => $user->total(),'data' => $user->page($page,$limit)->select('_safe_')->getAll()]);
	}
	
	public function edit_ajax() {
		$uid = P('post.uid','int');
		$user = M('user')->setClear(false)->uid($uid);
		
		$username = P('post.username','sql',false);
		$password = P('post.password','sql',false);
		$email = P('post.email','sql',false);
		$description = P('post.description',['sql','html'],false);
		$avatar = P('post.avatar',['sql','html'],false);
		$point = P('post.point','int',false) and $user->update(['point' => $point]);
		$gid = P('post.gid','int',false);
		
		if(! empty($username)) {
			if(mb_strlen($username) <= 15) {
				if(! M('user')->username($username)->uid || $username == $user->username) {
					$user->update(['username' => $username]);
				} else {
					puts(0,'用户名已存在');
				}
			} else {
				puts(0,'用户名不能超过 15 字符');
			}
		}
		
		if(! empty($password)) {
			if(preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)([0-9a-zA-Z]){8,20}$/',$password)) {
				$user->update(['password' => encrypt($password)]);
			} else {
				puts(0,'密码不符合要求：只能包含字母和数字且在 8～20 字符之间');
			}
		}
		
		if(! empty($email)) {
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
				if(! M('user')->email($email)->uid || $email == $user->email) {
					$user->update(['email' => $email]);
				} else {
					puts(0,'邮箱已被绑定');
				}
			} else {
				puts(0,'邮箱格式不正确');
			}
		}
		
		if(! empty($description)) {
			if(mb_strlen($description) <= 50) {
				$user->update(['description' => $description]);
			} else {
				puts(0,'介绍不能超过 50 字符');
			}
		}
		
		if(! empty($avatar)) {
			if(is_file(ROOT_PATH . $avatar)) {
				$user->update(['avatar' => $avatar]);
			} else {
				puts(0,'头像文件不存在');
			}
		}
		
		if(! empty($gid)) {
			if(M('usergroup')->gid($gid)->select()->getOne()) {
				$user->update(['gid' => $gid]);
			} else {
				puts(0,'用户组不存在');
			}
		}
		
		puts(1,'修改成功');
	}
	
	public function create_ajax() {
		$username = P('post.username','sql');
		$password = P('post.password','sql');
		$email = P('post.email','sql');
		$description = P('post.description',['sql','html'],false);
		$avatar = P('post.avatar',['sql','html'],false);
		$point = P('post.point','int',false) ?: 0;
		$gid = P('post.gid','int');
		
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
			$user = M('user')->uid($register['uid']);
			$user->update(['description' => $description,'avatar' => $avatar,'point' => $point,'gid' => $gid]);
		}
		putd($register);
	}
	
	public function delete_ajax(int $uid) {
		M('user')->uid($uid)->delete();
		puts(1,'删除成功！');
	}
	
	public function send_sys_msg() {
		$uid = P('post.uid','int');
		$content = P('post.content',['sql','html']);
		
		M('message')->create(['uid1' => UID,'uid2' => $uid,'content' => $content,'type' => 1]);
		puts(1,'发送成功');
	}
}