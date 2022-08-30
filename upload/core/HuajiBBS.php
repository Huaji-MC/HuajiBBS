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

class HuajiBBS {
	public static function run() {
		header('Content-Type: text/html; Charset=UTF-8');
		date_default_timezone_set('PRC');
		spl_autoload_register([__CLASS__,'autoload']);
		set_error_handler([__CLASS__,'error']);
		set_exception_handler([__CLASS__,'exception']);
		self::set_constant();
		include LIB_PATH . 'function/common.php';
		
		if(! is_file(ROOT_PATH . 'config/config.php')) {
			header('location: ' . URL . 'install');
			die(0);
		}
		
		Router::dispatch();
	}
	
	public static function set_constant() {
		define('ROOT_PATH',dirname(__DIR__) . '/');
		define('CONFIG_PATH',ROOT_PATH . 'config/');
		define('CORE_PATH',ROOT_PATH . 'core/');
		define('LIB_PATH',ROOT_PATH . 'lib/');
		define('MODEL_PATH',ROOT_PATH . 'model/');
		define('UPLOAD_PATH',ROOT_PATH . 'upload/');
		define('TEMPLATE_PATH',ROOT_PATH . 'template/');
		define('CONTROLLER_PATH',ROOT_PATH . 'controller/');
		define('URL','http://' . $_SERVER['HTTP_HOST'] . preg_replace('/(index\.php.*)|(\?.*)/','',$_SERVER['REQUEST_URI']));
		define('VERSION','1.4.2');
	}
	
	public static function autoload($class) {
		if(class_exists($class) || !preg_match('/\\\\.+/',$class)) {
			return;
		}
		
		$className = ltrim($class,'\\');
		$className = str_replace('\\','/',$class);
		$file = ROOT_PATH . $className . '.php';
		
		if(is_file($file)) {
			require_once $file;
		}
	}
	
	public static function error($errno,$message,$file,$line) {
		if($errno == 8) return; //E_ALL & ~E_NOTICE
		include LIB_PATH . 'view/error.php';
		die(1);
	}
	
	public static function exception($e) {
		$code    = $e->getCode();
		$message = $e->getMessage();
		$file    = $e->getFile();
		$line    = $e->getLine();
		$trace   = $e->getTrace();
		include LIB_PATH . 'view/exception.php';
		die(1);
	}
}