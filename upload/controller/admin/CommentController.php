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

class CommentController extends BaseController {
	public function index() {
		$this->title('评论管理')->show('index');
	}
	
	public function search_ajax() {
		$comment = M('comment')->setClear(false);
		$cid = P('post.cid','int',false) and $comment = $comment->cid($cid);
		$uid = P('post.uid','int',false) and $comment = $comment->uid($uid);
		$tid = P('post.tid','int',false) and $comment = $comment->tid($tid);
		$content = P('post.content','sql',false) and $comment = $comment->and("content like '%%{$content}%%'");
		$start = strtotime(P('post.start','sql',false)) ?: 0 and $comment = $comment->from($start);
		$end = strtotime(P('post.end','sql',false)) ?: time() and $comment = $comment->to($end);
		
		$page = P('post.page','int');
		$limit = P('post.limit','int');
		putd(['status' => 1,'msg' => '查询成功','count' => $comment->total(),'data' => $comment->order('time','desc')->page($page,$limit)->select()->getAll()]);
	}
	
	public function delete_ajax(int $cid) {
		M('comment')->cid($cid)->delete();
		puts(1,'删除成功');
	}
	
	public function edit_ajax() {
		$cid = P('post.cid','int');
		$comment = M('comment')->setClear(false)->cid($cid);
		
		$content = P('post.content',['sql','html']);
		if(mb_strlen($content) > 60000) puts(0,'评论内容不能超过 60000 字符！');
		$comment->update(['content' => $content]);
		
		puts(1,'编辑成功');
	}
}