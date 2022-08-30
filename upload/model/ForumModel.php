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

class ForumModel extends Model {
	public $table = 'forum';
	
	public function fid($fid) {
		$this->and("fid = '{$fid}'")->update(['hot[+]' => 10]);
		return $this->and("fid = '{$fid}'");
	}
	
	public function is_moderator($uid) {
		foreach($this->moderators as $user) {
			if($user['uid'] == $uid) {
				return true;
			}
		}
		return false;
	}
	
	//Override
	public function getOne() {
		$forum = $this->result->fetch_assoc();
		if($forum) {
			$moderators = _explode(',',$forum['moderators']);
			$forum['moderators1'] = $moderators;
			$forum['moderators2'] = $forum['moderators'];
			$forum['moderators'] = array();
			foreach($moderators as $uid) {
				$forum['moderators'][] = M('user')->uid($uid)->select()->getOne();
			}
			$forum['icon1'] = $forum['icon'] ? $forum['icon'] : C('default_forum_icon');
			$forum['icon'] = URL . ($forum['icon'] ? $forum['icon'] : C('default_forum_icon'));
		}
		return $forum;
	}
	
	//Override
	public function getAll() {
		$list = $this->result->fetch_all(MYSQLI_ASSOC);
		if($list) {
			foreach($list as &$forum) {
				$moderators = _explode(',',$forum['moderators']);
				$forum['moderators1'] = $moderators;
				$forum['moderators2'] = $forum['moderators'];
				$forum['moderators'] = array();
				foreach($moderators as $uid) {
					$forum['moderators'][] = M('user')->uid($uid)->select('_safe_')->getOne();
				}
				$forum['icon1'] = $forum['icon'] ? $forum['icon'] : C('default_forum_icon');
				$forum['icon'] = URL . ($forum['icon'] ? $forum['icon'] : C('default_forum_icon'));
			}
		}
		return $list;
	}
}