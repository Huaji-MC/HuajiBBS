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

namespace controller\home;
defined('ACCESS') or die('You have no access.');

class ForumController extends BaseController {
	public function index($id,$sort,$page) {
		$Thread = M('thread');
		$forum = M('forum')->fid($id)->select()->getOne();
		$noticeThreads = $Thread->notice();
		$topThreads = $Thread->fid($id)->top();
		$normalThreads = $Thread->fid($id)->sort($sort)->page($page)->normal();
		$threads = array_merge($noticeThreads,$topThreads,$normalThreads);
		$this->title($forum['name'])->set('forum',$forum)->set('threads',$threads)->show('index');
	}
}