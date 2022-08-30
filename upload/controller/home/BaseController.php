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
use core\Controller;
defined('ACCESS') or die('You have no access.');

class BaseController extends Controller {
	protected $perm;
	
	public function __construct() {
		parent::__construct();
		
		$user = M('user')->token($_COOKIE['token'])->select()->getOne();
		$this->set('_U',$user);
		$this->perm = (Object) $user['group']['permission'];
		define('IS_LOGIN',$user ? true : false);
		define('UID',IS_LOGIN ? $user['uid'] : 0);
		
		$friendshiplinks = M('friendshiplink')->select()->getAll();
		$this->set('friendshiplinks',$friendshiplinks);
	}
}