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

class IndexController extends BaseController {
	public function index() {
		$this->show('index');
	}
	
	public function console() {
		$data = [
			'thread' => [
				'today' => M('thread')->from('today')->total(),
				'total' => M('thread')->total(),
			],
			'comment' => [
				'today' => M('comment')->from('today')->total(),
				'total' => M('comment')->total(),
			],
			'user' => [
				'today' => M('user')->from('today')->total(),
				'total' => M('user')->total(),
			],
		];
		for($i = 6,$j = $i - 1; $i >= 0; $i --) {
			$data['thread']['7days'][] = M('thread')->from("today - {$i} day")->to("today - {$j} day")->total();
			$data['comment']['7days'][] = M('comment')->from("today - {$i} day")->to("today - {$j} day")->total();
			$data['user']['7days'][] = M('user')->from("today - {$i} day")->to("today - {$j} day")->total();
		}
		$this->title('控制台')->set('data',$data)->show('console');
	}
	
	public function upload_ajax($type) {
		if($_FILES['file']['size'] / 1024 > 1024) puts(0,'图片大小不能超过 1024 KB');
		require LIB_PATH . 'function/file.php';
		$result = uploadImage($_FILES['file'],$type);
		if(! $result[0]) {
			puts(0,$result[1]);
		} else {
			putd(['status' => 1,'msg' => '上传成功','path' => $result[2]]);
		}
	}
}