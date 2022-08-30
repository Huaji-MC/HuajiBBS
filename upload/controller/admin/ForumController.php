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

class ForumController extends BaseController {
	public function index() {
		$this->title('版块管理')->show('index');
	}
	
	public function search_ajax() {
		$forum = M('forum')->setClear(false);
		$fid = P('post.fid','int',false) and $forum = $forum->fid($fid);
		$name = P('post.name','sql',false) and $forum = $forum->and("name like '%%{$name}%%'");
		
		$page = P('post.page','int');
		$limit = P('post.limit','int');
		putd(['status' => 1,'msg' => '查询成功','count' => $forum->total(),'data' => $forum->page($page,$limit)->select()->getAll()]);
	}
	
	public function edit_or_create_ajax(string $type) {
		$fid = P('post.fid','int',$type == 'create' ? false : true);
		$forum = M('forum')->fid($fid);
		
		$name = P('post.name',['sql','html']);
		$description = P('post.description',['sql','html']);
		$rule = P('post.rule',['sql','html']);
		$icon = P('post.icon',['sql','html'],false);
		
		if(mb_strlen($name) > 15) puts(0,'版块名称不能超过 15 字符');
		if(mb_strlen($description) > 100) puts(0,'版块描述不能超过 15 字符');
		
		if($type == 'create') {
			$forum->insert(['name' => $name,'description' => $description,'rule' => $rule,'icon' => $icon]);
			puts(1,'添加成功');
		} else if($type == 'edit') {
			$forum->update(['name' => $name,'description' => $description,'rule' => $rule,'icon' => $icon]);
			puts(1,'编辑成功');
		} else {
			puts(0,'请不要另辟蹊径');
		}
	}
	
	public function merge_ajax() {
		$from = array_filter(F(explode(',',P('post.from',null)),'int'));
		$to = P('post.to','int');
		
		if(! in_array($to,$from)) puts(0,'合并到的版块必须在合并版块中');
		if(! M('forum')->fid($to)->fid) puts(0,'合并到的版块不存在');
		
		foreach($from as $fid) {
			if($fid == $to) continue;
			M('thread')->fid($fid)->fid = $to;
			M('forum')->fid($to)->update(['threads[+]' => M('forum')->fid($fid)->threads]);
			M('forum')->fid($fid)->delete();
		}
		
		puts(1,'合并成功');
	}
	
	public function delete_moderator_ajax() {
		$fid = P('post.fid','int');
		$uid = P('post.uid','int');
		
		$forum = M('forum')->fid($fid)->setClear(false);
		if(! $forum->fid) puts(0,'版块不存在');
		if(! M('user')->uid($uid)->uid) puts(0,'用户不存在');
		
		$moderators = $forum->moderators1;
		$key = array_search($uid,$moderators);
		if($key === false) puts(0,'用户不是该版块版主');
		unset($moderators[$key]);
		$forum->update(['moderators' => implode(',',$moderators)]);
		M('message')->create(['uid1' => UID,'uid2' => $uid,'content' => "你在【{$forum->name}】版块的版主身份已被撤销",'type' => 1]);
		
		puts(1,'OK');
	}
	
	public function add_moderator_ajax() {
		$fid = P('post.fid','int');
		$uid = P('post.uid','int');
		
		$forum = M('forum')->fid($fid)->setClear(false);
		if(! $forum->fid) puts(0,'版块不存在');
		if(! M('user')->uid($uid)->uid) puts(0,'用户不存在');
		
		$moderators = $forum->moderators1;
		if(in_array($uid,$moderators)) puts(0,'用户已经是该版块版主了');
		
		$moderators[] = $uid;
		$forum->moderators = implode(',',$moderators);
		M('message')->create(['uid1' => UID,'uid2' => $uid,'content' => "你已成为【{$forum->name}】版块的版主",'type' => 1]);
		
		putd(['status' => 1,'msg' => 'OK','user' => M('user')->uid($uid)->select('_safe_')->getOne()]);
	}
}