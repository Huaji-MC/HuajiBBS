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

class IndexController extends BaseController {
	public function index() {
		$forumList = M('forum')->list();
		$this->title('首页')->set('forumList',$forumList)->show('index');
	}
	
	public function upload_ajax($type) {
		if(! IS_LOGIN) putd(['error' => '你还没有登录']);
		
		if($_FILES['upload']['size'] / 1024 > 1024) putd(['error' => '图片大小不能超过 1024 KB']);
		require LIB_PATH . 'function/file.php';
		$result = uploadImage($_FILES['upload'],$type);
		if(! $result[0]) {
			putd(['error' => $result[1]]);
		} else {
			putd(['status' => 0,'msg' => '上传成功','url' => $result[1],'path' => $result[2]]);
		}
	}
	
	public function unread_ajax() {
		if(! IS_LOGIN) puts(0,'你还没有登录');
		$unread = array_sum(M('message')->unread(UID));
		puts(1,$unread ? 1 : 0);
	}
}