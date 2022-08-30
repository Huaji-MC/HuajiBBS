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

class ThreadController extends BaseController {
	public function index() {
		$forums = M('forum')->list();
		$this->set('forums',$forums)->title('主题管理')->show('index');
	}
	
	public function search_ajax() {
		$thread = M('thread')->setClear(false);
		$tid = P('post.tid','int',false) and $thread = $thread->tid($tid);
		$uid = P('post.uid','int',false) and $thread = $thread->uid($uid);
		$fid = P('post.fid','int',false) and $thread = $thread->fid($fid);
		$title = P('post.title','sql',false) and $thread = $thread->and("title like '%%{$title}%%'");
		$content = P('post.content','sql',false) and $thread = $thread->and("content like '%%{$content}%%'");
		$image = P('post.image','int',false) and $thread = $thread->and('image ' . (($image - 1) ? '<>' : '=') . ' ""');
		$top = P('post.top','int',false) and $thread = $thread->and('top = ' . ($top - 1));
		$essence = P('post.essence','int',false) and $thread = $thread->and('essence = ' . ($essence - 1));
		$lock = P('post.lock','int',false) and $thread = $thread->and('`lock` = ' . ($lock - 1));
		$start = strtotime(P('post.start','sql',false)) ?: 0 and $thread = $thread->from($start);
		$end = strtotime(P('post.end','sql',false)) ?: time() and $thread = $thread->to($end);
		
		$page = P('post.page','int');
		$limit = P('post.limit','int');
		putd(['status' => 1,'msg' => '查询成功','count' => $thread->total(),'data' => $thread->order('time','desc')->page($page,$limit)->select()->getAll()]);
	}
	
	public function delete_ajax(int $tid) {
		M('thread')->tid($tid)->delete();
		puts(1,'删除成功');
	}
	
	public function lock_ajax(int $tid) {
		$Thread = M('thread')->tid($tid)->setClear(false);
		$Thread->lock = 1;
		M('message')->create(['uid1' => UID,'uid2' => $Thread->uid,'content' => "你的主题《{$Thread->title}》（TID：{$tid}）已被锁定",'type' => 1]);
		puts(1,'锁定成功');
	}
	
	public function unlock_ajax(int $tid) {
		$Thread = M('thread')->tid($tid)->setClear(false);
		$Thread->lock = 0;
		M('message')->create(['uid1' => UID,'uid2' => $Thread->uid,'content' => "你的主题《{$Thread->title}》（TID：{$tid}）已被取消锁定",'type' => 1]);
		puts(1,'取消锁定成功');
	}
}