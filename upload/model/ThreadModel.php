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

class ThreadModel extends Model {
	public $table = 'thread';
	
	public function tid($tid) {
		return $this->and("thread.tid = '{$tid}'");
	}
	
	public function uid($uid) {
		return $this->and("thread.uid = '{$uid}'");
	}
	
	public function fid($fid) {
		return $this->and("thread.fid = '{$fid}'");
	}
	
	//$type：排序方式（0-最新回复，1-最新发布，2-浏览最多，3-评论最多，4-点赞最多，5-精华）倒序
	public function sort($type) {
		$columns = [
			0 => 'last_time',
			1 => 'time',
			2 => 'views',
			3 => 'comments',
			4 => 'praises',
			5 => 'essence',
		];
		$column = $columns[$type];
		return $this->order($column,'desc');
	}
	
	public function notice() {
		return $this->and('top = 2')->select()->getAll();
	}
	
	public function top() {
		return $this->and('top = 1')->select()->getAll();
	}
	
	public function normal() {
		return $this->and('top = 0')->select()->getAll();
	}
	
	public function create($uid,$fid,$title,$content,$image = '') {
		M('user')->uid($uid)->update(['threads[+]' => 1]);
		M('forum')->fid($fid)->update(['threads[+]' => 1,'hot[+]' => rand(1,250)]);
		return $this->insert(['uid' => $uid,'fid' => $fid,'title' => $title,'content' => $content,'image' => $image,'last_time' => time(),'time' => time()]);
	}
	
	public function edit($title,$content,$fid,$image) {
		return $this->update(['title' => $title,'content' => $content,'fid' => $fid,'image' => $image,'edit_time' => time()]);
	}
	
	public function search($keyword) {
		return $this->and("title like '%%{$keyword}%%'")->select()->getAll();
	}
	
	//Override
	public function delete() {
		$thread = $this->setClear(false)->select()->getOne();
		M('forum')->fid($thread['fid'])->update(['threads[-]' => 1]);
		M('user')->uid($thread['uid'])->update(['threads[-]' => 1]);
		M('message')->create(['uid1' => UID,'uid2' => $thread['uid'],'type' => 1,'content' => '你的主题《' . $thread['title'] . '》（TID：' . $thread['tid'] . '）已被删除']);
		return parent::delete();
	}
	
	//Override
	public function getOne() {
		$thread = $this->result->fetch_assoc();
		if($thread['uid']) {
			$thread['user'] = M('user')->uid($thread['uid'])->select('_safe_')->getOne();
			$thread['forum'] = M('forum')->fid($thread['fid'])->select()->getOne();
			$thread['images'] = _explode(',',$thread['image']);
		}
		return $thread;
	}
	
	//Override
	public function getAll() {
		$threads = $this->result->fetch_all(MYSQLI_ASSOC);
		if($threads) {
			foreach($threads as &$thread) {
				$thread['user'] = M('user')->uid($thread['uid'])->select('_safe_')->getOne();
				$thread['forum'] = M('forum')->fid($thread['fid'])->select()->getOne();
			$thread['images'] = _explode(',',$thread['image']);
			}
		}
		return $threads;
	}
}