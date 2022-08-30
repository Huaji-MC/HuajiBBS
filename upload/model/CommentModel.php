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

class CommentModel extends Model {
	public $table = 'comment';
	
	public function cid($cid) {
		return $this->and("comment.cid = '{$cid}'");
	}
	
	public function uid($uid) {
		return $this->and("comment.uid = '{$uid}'");
	}
	
	public function tid($tid) {
		return $this->and("comment.tid = '{$tid}'");
	}
	
	public function author() {
		return $this->join('thread')->and('thread.tid = comment.tid')->and('thread.uid = comment.uid')->select('comment.*')->getAll();
	}
	
	public function post($uid,$tid,$content) {
		M('user')->uid($uid)->update(['comments[+]' => 1]);
		M('thread')->tid($tid)->update(['comments[+]' => 1,'last_time' => time()]);
		if($uid != M('thread')->tid($tid)->uid) {
			M('message')->create(['uid1' => $uid,'uid2' => M('thread')->tid($tid)->uid,'type' => 0,'content' => $content,'tid' => $tid]);
		}
		return $this->insert(['tid' => $tid,'uid' => $uid,'content' => $content,'time' => time()]);
	}
	
	//Override
	public function getOne() {
		$comment = $this->result->fetch_assoc();
		if($comment['uid']) {
			$comment['user'] = M('user')->uid($comment['uid'])->select('_safe_')->getOne();
			$comment['thread'] = M('thread')->tid($comment['tid'])->select()->getOne();
		}
		return $comment;
	}
	
	//Override
	public function getAll() {
		$comments = $this->result->fetch_all(MYSQLI_ASSOC);
		if($comments) {
			foreach($comments as &$comment) {
				$comment['user'] = M('user')->uid($comment['uid'])->select('_safe_')->getOne();
				$comment['thread'] = M('thread')->tid($comment['tid'])->select()->getOne();
			}
		}
		return $comments;
	}
}