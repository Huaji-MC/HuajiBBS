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

namespace model;
use core\Model;

class UsergroupModel extends Model {
	public $table = 'user_group';
	
	public function gid($gid) {
		return $this->and("user_group.gid = '{$gid}'");
	}
	
	//Override
	public function getOne() {
		$usergroup = $this->result->fetch_assoc();
		if($usergroup) {
			$usergroup['permission'] = json_decode($usergroup['permission'],true);
		}
		return $usergroup;
	}
	
	//Override
	public function getAll() {
		$usergroups = $this->result->fetch_all(MYSQLI_ASSOC);
		if($usergroups) {
			foreach($usergroups as & $usergroup) {
				$usergroup['permission'] = json_decode($usergroup['permission'],true);
			}
		}
		return $usergroups;
	}
}