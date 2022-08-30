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

class ThreadpraiseModel extends Model {
	public $table = 'thread_praise';
	
	public function tid($tid) {
		return $this->and("thread_praise.tid = '{$tid}'");
	}
	
	public function uid($uid) {
		return $this->and("thread_praise.uid = '{$uid}'");
	}
	
	public function praise($tid,$uid) {
		M('thread')->tid($tid)->update(['praises[+]' => 1]);
		if(M('thread')->tid($tid)->uid != $uid) M('message')->create(['uid1' => $uid,'uid2' => M('thread')->tid($tid)->uid,'type' => 2,'tid' => $tid]);
		M('user')->uid($uid)->update(['praises[+]' => 1]);
		$this->insert(['tid' => $tid,'uid' => $uid,'time' => time()]);
		return true;
	}
	
	public function unpraise($tid,$uid) {
		M('thread')->tid($tid)->update(['praises[-]' => 1]);
		M('message')->uid1($uid)->tid($tid)->type(2)->delete();
		M('user')->uid($uid)->update(['praises[-]' => 1]);
		$this->tid($tid)->uid($uid)->delete();
		return true;
	}
	
	public function is_praise() {
		return $this->list() ? true : false;
	}
	
	//Override
	public function getAll() {
		$list = $this->result->fetch_all(MYSQLI_ASSOC);
		if($list) {
			foreach($list as &$praise) {
				$praise['thread'] = M('thread')->tid($praise['tid'])->select()->getOne();
			}
		}
		return $list;
	}
}