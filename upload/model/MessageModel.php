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

class MessageModel extends Model {
	public $table = 'message';
	
	public function mid($mid) {
		return $this->and("mid = '{$mid}'");
	}
	
	public function uid1($uid1) {
		return $this->and("uid1 = '{$uid1}'");
	}
	
	public function uid2($uid2) {
		return $this->and("uid2 = '{$uid2}'");
	}
	
	public function tid($tid) {
		return $this->and("tid = '{$tid}'");
	}
	
	public function type($type) {
		return $this->and("type = '{$type}'");
	}
	
	public function isread($isread) {
		return $this->and("isread = '{$isread}'");
	}
	
	public function create($data) {
		$data = array_merge($data,['time' => time()]);
		return $this->insert($data);
	}
	
	public function unread($uid) {
		$data = [];
		$data[] = M('message')->uid2($uid)->type(0)->isread(0)->total();
		$data[] = M('message')->uid2($uid)->type(1)->isread(0)->total();
		$data[] = M('message')->uid2($uid)->type(2)->isread(0)->total();
		$data[] = M('message')->uid2($uid)->type(3)->isread(0)->total();
		return $data;
	}
	
	//Override
	public function getAll() {
		$messages = $this->result->fetch_all(MYSQLI_ASSOC);
		if($messages) {
			foreach($messages as &$message) {
				$message['user'] = M('user')->uid($message['uid1'])->select('_safe_')->getOne();
				if($message['tid']) {
					$message['thread'] = M('thread')->tid($message['tid'])->select()->getOne();
				}
				M('message')->mid($message['mid'])->update(['isread' => 1]);
			}
		}
		return $messages;
	}
}