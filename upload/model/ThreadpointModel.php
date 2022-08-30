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

class ThreadpointModel extends Model {
	public $table = 'thread_point';
	
	public function tid($tid) {
		return $this->and("thread_point.tid = '{$tid}'");
	}
	
	public function uid($uid) {
		return $this->and("thread_point.uid = '{$uid}'");
	}
	
	public function reward($tid,$uid,$points,$reason) {
		M('thread')->tid($tid)->update(['points[+]' => $points]);
		M('user')->uid($uid)->update(['point[-]' => $points]);
		M('user')->uid(M('thread')->tid($tid)->uid)->update(['point[+]' => $points]);
		M('message')->create(['uid1' => $uid,'uid2' => M('thread')->tid($tid)->uid,'type' => 3,'tid' => $tid,'content' => $reason,'content2' => $points]);
		$this->insert(['tid' => $tid,'uid' => $uid,'points' => $points,'reason' => $reason,'time' => time()]);
		return true;
	}
	
	//Override
	public function getAll() {
		$rewards = $this->result->fetch_all(MYSQLI_ASSOC);
		if($rewards) {
			foreach($rewards as &$reward) {
				$reward['user'] = M('user')->uid($reward['uid'])->select('_safe_')->getOne();
				$reward['thread'] = M('thread')->tid($reward['tid'])->select()->getOne();
			}
		}
		return $rewards;
	}
}