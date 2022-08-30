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
use core\Controller;
defined('ACCESS') or die('You have no access.');

class BaseController extends Controller {
	public function __construct() {
		parent::__construct();

		if($_SESSION['admin']) {
			$user = M('user')->uid($_SESSION['admin'])->select()->getOne();
			$this->set('_U',$user);
			define('UID',$_SESSION['admin']);
		} else {
			go('admin','login');
		}
	}
}