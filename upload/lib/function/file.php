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

function uploadImage($file,$dir) {
	$allowSuffix = ['gif','png','jpg','jpeg','webp'];
	if(! in_array($suffix = end(explode('.',$file['name'])),$allowSuffix))
		return [0,$suffix . ' 是不支持的后缀名'];
	
	if($file['error']) return [0,'发生错误：' . $file['error']];
	
	if(! file_exists(UPLOAD_PATH . $dir)) mkdir(UPLOAD_PATH . $dir,0777,true);
	
	$name = date('Y-m-d-U') . '_' . base64_encode(rand(1,100000) * rand(1,100000)) . '_' . uniqid() . '.png';
	move_uploaded_file($file['tmp_name'],UPLOAD_PATH . $dir . '/' . $name);
	return [1,URL . 'upload/' . $dir . '/' . $name,'upload/' . $dir . '/' . $name];
}

function setConfig($configs) {
	$file = CONFIG_PATH . 'config.php';
	$content = file_get_contents($file);
	foreach($configs as $name => $value) {
		$value = str_replace('"','\"',$value);
		$value = str_replace("\n",'\n',$value);
		$content = preg_replace("/\"{$name}\" => .+,/","\"{$name}\" => \"{$value}\",",$content);
	}
	file_put_contents($file,$content);
}

function getDirSize($path) {
	$size = 0;
	if(! is_dir($path)) return 0;
	if($handle = @opendir($path)) {
		while(false !== ($file = readdir($handle))) {
			$filePath = $path . '/' . $file;
			if($file != '.' && $file != '..') {
				if(is_dir($nextpath)) {
					$size += getDirSize($filePath); 
				} else if(is_file($filePath)) {
					$size += filesize($filePath);
				}
			}
		}
	}
	closedir($handle);
	return $size;
}

function sizeFormat($size) { 
	if($size < 1024 * 1024) {
		$size = round($size / 1024,1);
		return $size . ' KB';
	} else if($size < 1024 * 1024 * 1024) {
		$size = round($size / (1024 * 1024),1); 
		return $size . ' MB'; 
	} else {
		$size = round($size / (1024 * 1024 * 1024),1); 
		return $size . ' GB'; 
	}
}

function deleteDir($dir) {
	if(! is_dir($dir)) return;
	if($handle = @opendir($dir)) {
		while(($file = readdir($handle)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			if(is_dir($dir . '/' . $file)) {
				deleteDir($dir . '/' . $file);
			} else {
				unlink($dir . '/' . $file);
			}
		}
		closedir($handle);
		rmdir($dir);
	}
}

function copyDir($dir,$to) {
	if(! is_dir($dir)) return;
	if(! is_dir($to)) mkdir($to,0777,true);
	
	if($handle = @opendir($dir)) {
		while(($file = readdir($handle)) !== false) {
			if($file == '.' || $file == '..') {
				continue;
			}
			if(is_dir($dir . '/' . $file)) {
				copyDir($dir . '/' . $file,$to . '/' . $file);
			} else {
				copy($dir . '/' . $file,$to . '/' . $file);
			}
		}
		closedir($handle);
	}
}

function getDirs($path) {
	if(! is_dir($path)) {
		return false;
	}
	
	$arr = array();
	$data = scandir($path);
	foreach($data as $value) {
		if(is_dir($path . '/' . $value) && $value != '.' && $value != '..') {
			$arr[] = $value;
		}
	}
	return $arr;
}