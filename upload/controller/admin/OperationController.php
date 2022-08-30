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

class OperationController extends BaseController {
	public function friendshiplinks() {
		$this->set('friendshiplinks',M('friendshiplink')->list())->show('friendshiplinks');
	}
	
	public function delete_friendshiplink_ajax(int $id) {
		M('friendshiplink')->id($id)->delete();
		puts(1,'删除成功');
	}
	
	public function friendshiplink_edit_or_create_ajax(string $type) {
		$id = P('post.id','int',$type == 'create' ? false : true);
		$friendshiplink = M('friendshiplink')->id($id);
		
		$name = P('post.name',['sql','html']);
		$url = P('post.url',['sql','html']);
		
		if(mb_strlen($name) > 100) puts(0,'网站名称不能超过 100 字符');
		
		if($type == 'create') {
			$friendshiplink->insert(['name' => $name,'url' => $url]);
		} else if($type == 'edit') {
			$friendshiplink->update(['name' => $name,'url' => $url]);
		} else {
			puts(0,'请不要另辟蹊径');
		}
		
		puts(1,'OK');
	}
}