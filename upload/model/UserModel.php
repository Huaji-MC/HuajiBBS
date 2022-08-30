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

namespace model;
use core\Model;

class UserModel extends Model {
	public $table = 'user';
	
	public function uid($uid) {
		return $this->and("uid = '{$uid}'");
	}
	
	public function username($username) {
		return $this->and("username = '{$username}'");
	}
	
	public function email($email) {
		return $this->and("LOWER(email) = LOWER('{$email}')");
	}
	
	public function token($token) {
		return $this->and("token = '{$token}'");
	}
	
	public function login($account,$password,$type) {
		if($type == 'username') { //用户名密码登录
			$this->and("username = '{$account}'");
		} else if($type == 'email') { //邮箱密码登录
			$this->and("LOWER(email) = LOWER('{$account}')");
		}
		
		if($user = $this->select()->getOne()) {
			if($user['password'] == encrypt($password)) {
				$token = createToken($user['uid']);
				$this->and('uid = ' . $user['uid'])->update(['token' => $token]);
				return ['status' => 1,'user' => $user,'token' => $token];
			} else {
				return ['status' => 0,'msg' => '密码错误'];
			}
		} else {
			return ['status' => 0,'msg' => '用户不存在'];
		}
	}
	
	public function register($username,$password,$email,$description = '',$avatar = '',$point = 0,$gid = 1) {
		if($this->username($username)->uid) {
			return ['status' => 0,'msg' => '此用户名已被使用'];
		}
		if($this->email($email)->uid) {
			return ['status' => 0,'msg' => '此邮箱已被绑定'];
		}
		$token = createToken($username);
		$uid = $this->insert([
			'username' => $username,
			'password' => encrypt($password),
			'token' => $token,
			'email' => $email,
			'description' => $description,
			'avatar' => $avatar,
			'point' => $point,
			'gid' => $gid,
			'time' => time(),
			]);
		return ['status' => 1,'uid' => $uid,'token' => $token];
	}
	
	public function exit() {
		$this->update(['token' => createToken(rand(0,9999999))]);
	}
	
	//Override
	public function select(...$column) {
		if($column[0] == '_safe_') {
			$column = ['uid','username','email','description','avatar','point','gid','threads','comments','time'];
			return parent::select(...$column);
		} else {
			return parent::select(...$column);
		}
	}
	
	//Override
	public function getOne() {
		$user = $this->result->fetch_assoc();
		if($user['uid']) {
			$user['avatar1'] = $user['avatar'];
			$user['avatar'] = URL . ($user['avatar'] ?: C('default_avatar'));
			$user['description'] = $user['description'] ?: 'Ta 还没有设置介绍～';
			$user['group'] = M('usergroup')->gid($user['gid'])->select()->getOne();
			$user['is_moderator'] = function($fid) {
				return M('forum')->fid($fid)->is_moderator($user['uid']);
			};
			
			//自动根据积分修改用户组
			if($user['group']['point_max'] != -1) {
				$usergroup = M('usergroup')->and('point_min <= ' . $user['point'])->and('point_max > ' . $user['point'])->select()->getOne();
				if($usergroup['gid'] && $usergroup['gid'] != $user['gid']) {
					$this->uid($user['uid'])->update(['gid' => $usergroup['gid']]);
					M('message')->create(['uid1' => 1,'uid2' => $user['uid'],'type' => 1,'content' => '你的用户组已变更：从【' . $user['group']['name'] . '】变为【' . $usergroup['name'] . '】']);
				}
			}
			//如果用户所属用户组不存在（被误删或其他原因）自动切回普通用户组
			if(! M('usergroup')->gid($user['gid'])->gid) $this->uid($user['uid'])->update(['gid' => 1]);
		}
		return $user;
	}
	
	//Override
	public function getAll() {
		$users = $this->result->fetch_all(MYSQLI_ASSOC);
		if($users) {
			foreach($users as &$user) {
				$user['avatar1'] = $user['avatar'] ?: C('default_avatar');
				$user['avatar'] = URL . ($user['avatar'] ?: C('default_avatar'));
				$user['description'] = $user['description'] ?: 'Ta 还没有设置介绍～';
				$user['group'] = M('usergroup')->gid($user['gid'])->select()->getOne();
			}
		}
		return $users;
	}
	
	//Override
	public function delete() {
		$uid = $this->select()->getOne()['uid'];
		$this->update(['username' => '账号已注销' . $uid,'avatar' => '','password' => '','token' => createToken($uid),'email' => '','description' => '','point' => 0,'gid' => 1]);
	}
}