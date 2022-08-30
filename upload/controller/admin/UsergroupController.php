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

class UsergroupController extends BaseController {
	public function index() {
		$this->title('用户组管理')->show('index');
	}
	
	public function user_group_list() {
		$group = M('usergroup')->setClear(false);
		$page = P('post.page','int');
		$limit = P('post.limit','int');
		putd(['status' => 1,'msg' => '查询成功','count' => $group->total(),'data' => $group->page($page,$limit)->order('point_min','desc')->list()]);
	}
	
	public function edit_or_create_ajax(string $type) {
		$gid = P('post.gid','int',$type == 'create' ? false : true);
		$group = M('usergroup')->gid($gid);
		
		$name = P('post.name',['sql','html']);
		$min = P('post.min','int');
		$max = P('post.max','int');
		$fontColor = P('post.font_color','sql');
		$color = P('post.color','sql');
		$rewardMax = P('post.reward_max','int');
		$permission = P('post.permission');
		
		if(mb_strlen($name) <= 10)
			$group->update(['name' => $name]);
		else puts(0,'用户组名称不能超过 10 字符');
		
		if($min == -1 || $max == -1) $min = $max = -1;
		else if($max <= $min || $min < 0) puts(0,'如果非 -1，最大积分必须大于最小积分且均需为非负整数');
		
		if(! preg_match('/#[a-fA-F0-9]{6}/',$fontColor)) puts(0,'用户名颜色不正确！必须为十六进制颜色码');
		if(! preg_match('/#[a-fA-F0-9]{6}/',$color)) puts(0,'用户组标识颜色不正确！必须为十六进制颜色码');
		
		if($rewardMax < 0) puts(0,'每日打赏限额必须为非负整数');
		
		if(! is_array($permission)) puts(0,'请不要另辟蹊径');
		$perm2 = [];
		foreach($permission as $key => $value) {
			$perm2[$key] = $value ? true : false;
		}
		$permission = json_encode($perm2);
		
		$data = ['name' => $name,'point_min' => $min,'point_max' => $max,'font_color' => $fontColor,'color' => $color,'reward_max' => $rewardMax,'permission' => $permission];
		if($type == 'edit') {
			$group->update($data);
			puts(1,'修改成功');
		} else if($type == 'create') {
			$group->insert($data);
			puts(1,'添加成功');
		} else {
			puts(0,'请不要另辟蹊径');
		}
	}
	
	public function delete_ajax(int $gid) {
		M('usergroup')->gid($gid)->delete();
		puts(1,'删除成功！');
	}
}