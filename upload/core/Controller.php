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

namespace core;
defined('ACCESS') or die('You have no access.');

class Controller {
	private $smarty;
	private $vars = [];
	
	public function __construct() {
		include LIB_PATH . 'Smarty/Smarty.class.php';
		$this->smarty = new \Smarty();
		$this->smarty->template_dir = TEMPLATE_PATH . C('current_template') . '/' . APP . '/' . CONTROLLER . '/';
		$this->smarty->cache_dir = TEMPLATE_PATH . C('current_template') . '/' . APP . '/cache/';
		$this->smarty->compile_dir = TEMPLATE_PATH . C('current_template') . '/' . APP . '/template_c/';
		$this->smarty->left_delimiter = '<{';
		$this->smarty->right_delimiter = '}>';
	}
	
	public function set($k,$v) {
		$this->smarty->assign($k,$v);
		$this->vars[$k] = $v;
		return $this;
	}
	
	public function get($k) {
		return $this->vars[$k];
	}
	
	public function title($title) {
		$this->set('title',$title);
		return $this;
	}
	
	public function show($html) {
		$this->smarty->display($html . '.html');
	}
}