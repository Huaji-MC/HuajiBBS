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

defined('ACCESS') or die('You have no access.');

//获取用户IP
function ip() {
	if(isset($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'],'unknown')) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],'unknown')) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			if(isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
				$ip = $_SERVER['REMOTE_ADDR'];
			} else {
				if(isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],'unknown')) {
					$ip = $_SERVER['REMOTE_ADDR'];
				} else {
					$ip = '0.0.0.0';
				}
			}
		}
	}
	return addslashes($ip);
}

//判断是否手机访问
function isMobile() {
	if(empty($_SERVER['HTTP_USER_AGENT']) ) {
		$isMobile = false;
	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false) {
		$isMobile = true;
	} else {
		$isMobile = false;
	}
	return $isMobile;
}

//加密算法
function encrypt($str) {
	return hash('sha256',$str);
}

//跳转（使用控制器，方法加参数）
function go($c,$a,...$p) {
	$p = $p ? '/' . implode('/',$p) : '';
	header('location:' . URL . "index.php/" . APP . "/{$c}/{$a}{$p}.html");
}

//获取URL字符串（使用控制器，方法加参数）
function url($c,$a,...$p) {
	$p = $p ? '/' . implode('/',$p) : '';
	return URL . "index.php/" . APP . "/{$c}/{$a}{$p}.html";
}

//获取URL字符串（前台）
function homeUrl($c,$a,...$p) {
	$p = $p ? '/' . implode('/',$p) : '';
	return URL . "index.php/home/{$c}/{$a}{$p}.html";
}

//返回json格式信息（传入数组）
function putd($arr) {
	header('Content-Type: application/json; Charset=UTF-8');
	$json = json_encode($arr,JSON_UNESCAPED_UNICODE);
	die($json);
}

//返回json格式信息（传入status和msg）
function puts($status,$msg) {
	header('Content-Type: application/json; Charset=UTF-8');
	$json = json_encode(['status' => $status,'msg' => $msg],JSON_UNESCAPED_UNICODE);
	die($json);
}

//获取配置信息
function C($key = null) {
	$config = is_file(CONFIG_PATH . 'config.php') ? require CONFIG_PATH . 'config.php' : [];
	if($key) return $config[$key];
	return $config;
}

//获取指定Model对象
function M($model) {
	static $objs = [];
	$model = ucfirst(strtolower($model));
	if($objs[$model]) return $objs[$model];
	$class = '\\model\\' . $model . 'Model';
	$obj = new $class;
	$objs[$model] = $obj;
	return $objs[$model];
}
// function M($model) {
	// $model = ucfirst(strtolower($model));
	// $class = '\\model\\' . $model . 'Model';
	// return new $class;
// }

//获取参数并过滤
function P($key,$type = null,$nullCheck = true) {
	$key = explode('.',$key);
	if($key[0] == 'get') {
		$value = $_GET[$key[1]];
	} else if($key[0] == 'post') {
		$value = $_POST[$key[1]];
	}
	if(empty($value) && $value !== 0 && $nullCheck) {
		putd(['status' => 0,'msg' => '参数“' . $key[1] . '”不能为空！','field' => $key[1]]);
	} else {
		return F($value,$type);
	}
}

//过滤一个值
function F($value,$type) {
	if(is_array($value)) {
		foreach($value as $k => $v) {
			$value[$k] = F($v,$type);
		}
	} else {
		if(! is_array($type)) {
			$type = [$type];
		}
		if(in_array('int',$type)) {
			$value = (int) $value;
		}
		if(in_array('html',$type)) {
			$value = htmlspecialchars($value);
		}
		if(in_array('sql',$type)) {
			$value = addslashes($value);
		}
	}
	return $value;
}

//成功页面
function success($msg,$url = null,$time = 3) {
	$state = true;
	$url = $url ?? $_SERVER['HTTP_REFERER'];
	require LIB_PATH . 'view/tips.php';
	die(0);
}

//失败页面
function fail($msg,$url = null,$time = 3) {
	$state = false;
	$url = $url ?? $_SERVER['HTTP_REFERER'];
	require LIB_PATH . 'view/tips.php';
	die(-1);
}

//设置COOKIE
function cookie($k,$v,$t = null) {
	setcookie($k,$v,$t ?? time() + 86400 * 30,'/');
}

//生成分页
function pagination($totalRows,$pageRows,$currentPage,$url) {
	return (new \lib\Pagination)->create($totalRows,$pageRows,$currentPage,$url);
}

//时间戳格式化
function timeFormat($time) {
	$diff = time() - $time;
	if($diff <= 60) {
		return $diff . '秒前';
	} else if($diff <= 3600) {
		return floor($diff / 60) . '分钟前';
	} else if($diff <= 86400) {
		return floor($diff / 3600) . '小时前';
	} else if($diff <= 604800) {
		return floor($diff / 86400) . '天前';
	} else {
		return date('Y-m-d H:i:s',$time);
	}
}

//数字格式化（非负数前加上+，负数前加上-）
function number($number) {
	if($number >= 0) {
		return '+' . $number;
	}
	return $number;
}

//把字符串中的换行符\n转化为字符串\n
function nl2str($text) {
	return str_replace("\n",'\n',$text);
}

//内容转义
function content($content) {
	//$content = htmlspecialchars($content);
	$content = str_replace('&','\&',$content);
	return $content;
}

//评论引用框
function quote_comment($comment) {
	$comment = is_int($comment) ? M('comment')->cid($comment)->getData() : $comment;
	$html = '<div class="comment-quote">'
	      . "	<h6>{$comment['user']['username']}：</h6>"
	      . '	<p>' . content($comment['content']) . '</p>'
	      . '</div>';
	return $html;
}

//生成token
function createToken($k) {
	return md5(time()) . md5($k) . md5(rand(-10000,-1)) . md5(rand(1,10000));
}

//优化系统explode()函数在字符串为空时数组也会有一个空元素
function _explode($char,$string) {
	if(empty($string)) {
		return [];
	} 
	return explode($char,$string);
}

//将时间戳转换为 Y-m-d H:i:s 格式的日期
function _date($timestamp) {
	return date('Y-m-d H:i:s',$timestamp);
}

//比较版本号
function compareVersions($v1,$v2) {
	$v1 = (int) str_replace('.','',$v1);
	$v2 = (int) str_replace('.','',$v2);
	return $v1 <=> $v2;
}