<?php
// +----------------------------------------------------------------------
// | HuajiBBS
// +----------------------------------------------------------------------
// | Copyright © 2021 HuajiMC. All rights reserved.
// +----------------------------------------------------------------------
// | License: GNU General Public License 3.0
// +----------------------------------------------------------------------
// | Author: 滑稽mc（HuajiMC） <HuajiMC@126.com>
// +----------------------------------------------------------------------

define('ACCESS',true);
session_start();

include './core/HuajiBBS.php';
\core\HuajiBBS::run();