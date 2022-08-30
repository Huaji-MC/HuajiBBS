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

class ThreadController extends BaseController {
	public function index(int $tid,int $floor,int $sort,int $page) {
		M('thread')->tid($tid)->views ++;
		$thread = M('thread')->tid($tid)->select()->getOne() or fail('帖子不存在或已被删除');
		$comments = $floor ? M('comment')->tid($tid)->order('time',$sort ? 'desc' : 'asc')->page($page)->author() : M('comment')->tid($tid)->order('time',$sort ? 'desc' : 'asc')->page($page)->list();
		$commentTotal = $floor ? count(M('comment')->tid($tid)->author()) : $thread['comments'];
		$isPraise = M('threadpraise')->tid($tid)->uid(UID)->is_praise();
		$todayReward = M('threadpoint')->uid(UID)->from('today')->sum('points') ?? 0;
		$isModerator = M('forum')->fid($thread['fid'])->is_moderator(UID);
		$this->title($thread['title'])->set('thread',$thread)->set('comments',$comments)->set('commentTotal',$commentTotal)->set('isPraise',$isPraise)->set('todayReward',$todayReward)->set('isModerator',$isModerator)->show('index');
	}
	
	public function praise_ajax(int $tid) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		if(! $this->perm->thread_praise) puts(0,'你所属用户组无权进行点赞操作');
		
		$thread = M('thread')->tid($tid)->select()->getOne() or puts(0,'主题不存在');
		if($thread['lock']) puts(0,'主题已锁定');
		
		if(M('threadpraise')->tid($tid)->uid(UID)->is_praise()) {
			M('threadpraise')->unpraise($tid,UID);
			puts(1,'取消点赞成功！');
		} else {
			M('threadpraise')->praise($tid,UID);
			puts(1,'点赞成功！');
		}
	}
	
	public function post_ajax(int $tid) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		if(! $this->perm->thread_post) puts(0,'你所属用户组无权进行回复操作');
		
		$thread = M('thread')->tid($tid)->select()->getOne() or puts(0,'主题不存在');
		if($thread['lock']) puts(0,'主题已锁定');
		
		$content = P('post.content',['sql','html']);
		if(mb_strlen($content) > 60000) puts(0,'回复内容不能超过 60000 字符！');
		
		$lastTime = M('comment')->uid(UID)->last()['time'];
		if(time() - $lastTime <= 10) puts(0,'回复间隔为 10s');
		
		M('comment')->post(UID,$tid,$content);
		puts(1,'回复成功！');
	}
	
	public function create(int $fid) {
		$forum = M('forum')->fid($fid)->select()->getOne();
		$this->title('发表主题 - ' . $forum['name'])->set('forum',$forum)->show('create');
	}
	
	public function create_ajax(int $fid) {
		if(! IS_LOGIN) fail('你还没有登录');
		if(! $this->perm->thread_create) fail('你所属用户组无权发表主题');
		
		$forum = M('forum')->fid($fid)->select()->getOne() or fail('版块不存在');
		
		$title = P('post.title',['sql','html']);
		$content = P('post.content',['sql','html']);
		$images = P('post.images','sql',false);
		if(mb_strlen($title) > 30) fail('标题字数不能超过 30 字');
		if(mb_strlen($content) > 60000) fail('内容字数不能超过 60000 字');
		if(count(explode(',',$images)) > 10) fail('图片不能超过 10 张');
		
		$lastTime = M('thread')->uid(UID)->last()['time'];
		if(time() - $lastTime <= C('create_thread_frequency')) fail('发表主题间隔时间为 ' . C('create_thread_frequency') . 's');
		
		$tid = M('thread')->create(UID,$fid,$title,$content,$images);
		success('发表成功！',url('thread','index',$tid,0,0,1));
	}
	
	public function delete_comment_ajax(int $cid) {
		if(! IS_LOGIN) fail('你还没有登录');
		
		$comment = M('comment')->cid($cid)->select()->getOne() or puts(0,'评论不存在！');
		$thread = M('thread')->tid($comment['tid'])->select()->getOne() or puts(0,'主题不存在！');
		if($thread['lock']) puts(0,'主题已锁定');
		
		if(! ($this->perm->comment_author && ($comment['uid'] == UID || $thread['uid'] == UID) || M('forum')->fid($thread['fid'])->is_moderator(UID) || $this->perm->comment_admin)) puts(0,'你没有权限删除！');
		M('comment')->cid($cid)->delete();
		puts(1,'删除成功！');
	}
	
	public function edit(int $tid) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$thread = M('thread')->tid($tid)->select()->getOne();
		if(! ($this->perm->thread_admin || M('forum')->fid($thread['fid'])->is_moderator(UID)) && ! ($this->perm->thread_author && $thread['uid'] == UID)) puts(0,'你所属用户组无权操作');
		$this->title('编辑主题 - ' . $thread['title'])->set('thread',$thread)->show('edit');
	}
	
	public function edit_ajax(int $tid) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$thread = M('thread')->tid($tid)->select()->getOne() or puts(0,'主题不存在');
		if(! ($this->perm->thread_admin || M('forum')->fid($thread['fid'])->is_moderator(UID)) && ! ($this->perm->thread_author && $thread['uid'] == UID)) puts(0,'你所属用户组无权操作');
		
		$title = P('post.title',['sql','html']);
		$content = P('post.content',['sql','html']);
		$images = P('post.images','sql',false);
		if(mb_strlen($title) > 30) puts(0,'标题字数不能超过 30 字');
		if(mb_strlen($content) > 60000) puts(0,'内容字数不能超过 60000 字');
		if($title == $thread['title'] && $content == $thread['content'] && $images == $thread['image']) puts(0,'你还没有编辑任何内容！');
		if(count(explode(',',$images)) > 10) fail('图片不能超过 10 张');
		
		$tid = M('thread')->tid($tid)->edit($title,$content,$thread['fid'],$images);
		puts(1,'发表成功！');
	}
	
	public function action_ajax(int $tid,string $type) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$thread = M('thread')->setClear(false)->tid($tid);
		if(! $thread->tid) puts(0,'主题不存在');
		
		if($this->perm->thread_admin || M('forum')->fid($thread->fid)->is_moderator(UID)) {
			if($type == 'essence') {
				$thread->essence = (int) ! $thread->essence;
				M('message')->create(['uid1' => UID,'uid2' => $thread->uid,'content' => "你的主题《{$thread->title}》（TID：{$tid}）已被" . ($thread->essence ? '' : '取消') . "精华",'type' => 1]);
				puts(1,'操作成功');
			} else if($type == 'lock') {
				$thread->lock = (int) ! $thread->lock;
				M('message')->create(['uid1' => UID,'uid2' => $thread->uid,'content' => "你的主题《{$thread->title}》（TID：{$tid}）已被" . ($thread->lock ? '' : '取消') . "锁定",'type' => 1]);
				puts(1,'操作成功');
			} else if($type == 'top1') {
				$thread->top = $thread->top == 1 ? 0 : 1;
				M('message')->create(['uid1' => UID,'uid2' => $thread->uid,'content' => "你的主题《{$thread->title}》（TID：{$tid}）已被" . ($thread->top ? '' : '取消') . "版块置顶",'type' => 1]);
				puts(1,'操作成功');
			} else if($type == 'top2') {
				$thread->top = $thread->top == 2 ? 0 : 2;
				M('message')->create(['uid1' => UID,'uid2' => $thread->uid,'content' => "你的主题《{$thread->title}》（TID：{$tid}）已被" . ($thread->top == 2 ? '' : '取消') . "全站置顶",'type' => 1]);
				puts(1,'操作成功');
			}
		}
		puts(0,'未知操作');
	}
	
	public function delete_ajax(int $tid) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$thread = M('thread')->setClear(false)->tid($tid);
		if(! $thread->tid) puts(0,'主题不存在');
		if(! ($this->perm->thread_admin || M('forum')->fid($thread->fid)->is_moderator(UID)) && ! ($this->perm->thread_author && $thread->uid == UID)) puts(0,'你所属用户组无权操作');
		
		if($thread->delete()) {
			puts(1,'删除成功');
		} else {
			puts(0,'删除失败');
		}
	}
	
	public function reward_ajax(int $tid,int $points) {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$thread = M('thread')->tid($tid)->select()->getOne() or puts(0,'主题不存在');
		if(! $this->perm->thread_reward) puts(0,'你所属用户组无权操作');
		if($thread['uid'] == UID) puts(0,'不要自己给自己打赏');
		if($this->get('_U')['point'] < $points) puts(0,'你的' . C('point_name') . '不足');
		if($points <= 0) puts(0,'打赏' . C('point_name') . '数必须为正');
		
		$reason = P('post.reason',['sql','html']);
		if(mb_strlen($reason) > 50) puts(0,'理由不能超过 50 字');
		
		$todayCount = M('threadpoint')->uid(UID)->from('today')->sum('points');
		if($todayCount + $points > $this->get('_U')['group']['reward_max']) puts(0,'你的打赏已超过今日最高限额，请明日再来');
		M('threadpoint')->reward($tid,UID,$points,$reason);
		puts(1,'打赏成功');
	}
	
	public function reward_list_ajax(int $tid,int $page) {
		$list = M('threadpoint')->tid($tid)->page($page)->order('time','desc')->list();
		if($list) {
			putd(['status' => 1,'msg' => '打赏列表加载成功','data' => $list]);
		} else {
			puts(0,'没有更多了');
		}
	}
	
	public function search(string $keyword,int $page) {
		$keyword = addslashes(urldecode($keyword));
		$threads = M('thread')->page($page)->order('time','desc')->search($keyword);
		$count = count(M('thread')->search($keyword));
		$this->title($keyword . ' - 搜索结果')->set('threads',$threads)->set('count',$count)->show('search');
	}
	
	public function test_json() {
		puts(1,'');
	}
}