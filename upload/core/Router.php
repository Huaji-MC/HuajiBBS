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

class Router {
	public static function dispatch() {
		$request = preg_replace('/^(.+?index\.php)/','',$_SERVER['PHP_SELF']);
		$request = preg_replace('/(\.html)$/','',$request);
		$request = trim($request,'/');
		$parameters = explode('/',$request);
		
		$app = strtolower($parameters[0]) ?: 'home';
		$controller = ucfirst(strtolower($parameters[1])) ?: 'Index';
		$action = strtolower($parameters[2]) ?: 'index';
		array_splice($parameters,0,3);
		
		if(strpos($action,'__') === 0) {
			fail('违规操作！');
		}
		
		define('APP',$app);
		define('CONTROLLER',$controller);
		define('ACTION',$action);

		$class = "\\controller\\{$app}\\{$controller}Controller";

		if(class_exists($class)) {
			$obj = new $class();
			if(method_exists($obj,$action)) {
				foreach($parameters as $k => $parameter) {
					$obj->set('p_' . $k,$parameter);
				}
				$obj->$action(...$parameters);
			} else {
				fail('方法不存在');
			}
		} else {
			fail('控制器不存在');
		}
	}
}