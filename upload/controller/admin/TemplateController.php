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

class TemplateController extends BaseController {
	private $templates = [];
	
	public function index() {
		$this->set('templates',$this->templates)->title('模板管理')->show('index');
	}
	
	public function action_ajax(string $type) {
		$dir = P('post.dir');
		
		if(! $this->templates[$dir]) puts(0,'模板不存在或不符合规范');
		
		if($type == 'use') {
			setConfig(['current_template' => $dir]);
		} else if($type == 'delete') {
			if($dir == 'default') puts(0,'默认模板不能删除');
			if($dir == C('current_template'))
				setConfig(['current_template' => 'default']);
			
			deleteDir(TEMPLATE_PATH . $dir);
		} else if($type == 'copy') {
			copyDir(TEMPLATE_PATH . $dir,TEMPLATE_PATH . $dir . '-' . rand(1,1000));
		} else {
			puts(0,'如果你想要更多功能可以给我们提建议');
		}
		puts(1,'OK');
	}
	
	public function __construct() {
		parent::__construct();
		
		require LIB_PATH . 'function/file.php';
		$dirs = getDirs(TEMPLATE_PATH);
		$templates = [];
		foreach($dirs as $dir) {
			if(is_file(TEMPLATE_PATH . $dir . '/data.json')) {
				$data = json_decode(file_get_contents(TEMPLATE_PATH . $dir . '/data.json'),true);
				if(! $data) continue;
				
				$preview = URL . 'template/' . $dir . '/preview.png';
				$templates[$dir] = [
					'data' => $data,
					'dir' => $dir,
					'preview' => is_file(TEMPLATE_PATH . $dir . '/preview.png') ? $preview : null,
				];
			}
		}
		$this->templates = $templates;
	}
}