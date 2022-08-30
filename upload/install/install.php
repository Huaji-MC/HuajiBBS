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

define('ACCESS',true);

if(is_file('../config/config.php')) {
	die('你已经安装过了，如果需要重新安装，请删除 /config/config.php 文件');
}

require '../lib/function/common.php';

$engine = P('post.db_engine');
$host = P('post.db_host');
$port = P('post.db_port');
$dbusername = P('post.db_username');
$dbpassword = P('post.db_password',null,false);
$name = P('post.db_name');

try {
	$conn = new mysqli($host,$dbusername,$dbpassword,$name,$port);
} catch(Exception $e) {
	puts(0,'数据库连接失败');
}

$username = P('post.admin_username',['sql','html']);
$password = encrypt(P('post.admin_password'));
$email = P('post.admin_email','sql');

$tempToken = createToken(rand(0,250));
$time = time();

$sqls = <<<EOF
--
-- 表的结构 `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `cid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `forum`
--

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `fid` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(15) NOT NULL,
  `hot` int(11) NOT NULL DEFAULT '0',
  `threads` int(11) NOT NULL DEFAULT '0',
  `moderators` text NOT NULL,
  `description` varchar(100) NOT NULL,
  `rule` text NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `friendshiplink`
--

DROP TABLE IF EXISTS `forum`;
CREATE TABLE `friendshiplink` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

--
-- 转储表的索引
--

--
-- 表的索引 `friendshiplink`
--
ALTER TABLE `friendshiplink`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `friendshiplink`
--
ALTER TABLE `friendshiplink`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- 表的结构 `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `mid` int(11) NOT NULL,
  `uid1` int(11) NOT NULL,
  `uid2` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `content` text,
  `content2` text,
  `tid` int(11) DEFAULT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `thread`
--

DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread` (
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `praises` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `image` text NOT NULL,
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `lock` tinyint(1) NOT NULL DEFAULT '0',
  `essence` tinyint(1) NOT NULL DEFAULT '0',
  `last_time` int(11) NOT NULL,
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `thread_point`
--

DROP TABLE IF EXISTS `thread_point`;
CREATE TABLE `thread_point` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `reason` varchar(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `thread_praise`
--

DROP TABLE IF EXISTS `thread_praise`;
CREATE TABLE `thread_praise` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(64) NOT NULL,
  `token` varchar(128) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL,
  `point` int(11) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '1',
  `threads` int(11) NOT NULL DEFAULT '0',
  `comments` int(11) NOT NULL DEFAULT '0',
  `praises` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `username`, `password`, `token`, `email`, `description`, `avatar`, `point`, `gid`, `threads`, `comments`, `praises`, `time`) VALUES
(1, '{$username}', '{$password}', '{$tempToken}', '{$email}', '', '', 0, 3, 0, 0, 0, {$time});

-- --------------------------------------------------------

--
-- 表的结构 `user_group`
--

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `gid` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `point_min` int(11) NOT NULL DEFAULT '-1',
  `point_max` int(11) NOT NULL DEFAULT '-1',
  `font_color` varchar(7) NOT NULL DEFAULT '#000000',
  `color` varchar(7) NOT NULL DEFAULT '#D3D3D3',
  `reward_max` int(11) NOT NULL,
  `permission` text NOT NULL
) ENGINE={$engine} DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user_group`
--

INSERT INTO `user_group` (`gid`, `name`, `point_min`, `point_max`, `font_color`, `color`, `reward_max`, `permission`) VALUES
(1, '普通用户', -1, -1, '#000000', '#D3D3D3', 200, '{\"thread_praise\":true,\"thread_reward\":true,\"thread_post\":true,\"thread_create\":true,\"thread_admin\":false,\"thread_author\":true,\"comment_admin\":false,\"comment_author\":true,\"user_setting\":true}'),
(2, '封禁用户', -1, -1, '#000000', '#D3D3D3', 0, '{}'),
(3, '管理员', -1, -1, '#ff0000', '#000000', 10000, '{\"thread_praise\":true,\"thread_reward\":true,\"thread_post\":true,\"thread_create\":true,\"thread_admin\":true,\"thread_author\":true,\"comment_admin\":true,\"comment_author\":true,\"user_setting\":true}');

--
-- 转储表的索引
--

--
-- 表的索引 `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `tid` (`tid`),
  ADD KEY `uid` (`uid`);

--
-- 表的索引 `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`fid`);

--
-- 表的索引 `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`mid`),
  ADD KEY `uid2` (`uid2`,`type`) USING BTREE,
  ADD KEY `uid1` (`uid1`) USING BTREE;

--
-- 表的索引 `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `fid` (`fid`),
  ADD KEY `views` (`views`),
  ADD KEY `comments` (`comments`),
  ADD KEY `praises` (`praises`),
  ADD KEY `top` (`top`),
  ADD KEY `lock` (`lock`),
  ADD KEY `essence` (`essence`),
  ADD KEY `points` (`points`);

--
-- 表的索引 `thread_point`
--
ALTER TABLE `thread_point`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`,`uid`);

--
-- 表的索引 `thread_praise`
--
ALTER TABLE `thread_praise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tid` (`tid`,`uid`);

--
-- 表的索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE,
  ADD UNIQUE KEY `token` (`token`) USING BTREE;

--
-- 表的索引 `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`gid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `comment`
--
ALTER TABLE `comment`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `forum`
--
ALTER TABLE `forum`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `thread`
--
ALTER TABLE `thread`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `thread_point`
--
ALTER TABLE `thread_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `thread_praise`
--
ALTER TABLE `thread_praise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `user_group`
--
ALTER TABLE `user_group`
  MODIFY `gid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT

EOF;

$sqls = explode(';',$sqls);
foreach($sqls as $sql) {
	$result = $conn->query($sql);
	if(! $result) {
		puts(0,'安装失败：SQL执行失败：' . $sql);
	}
}

$config = <<<EOF
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

//请不要随意修改配置文件，否则后果自负

return [
	//站点配置
	"site_name" => "HuajiBBS",
	"site_logo" => "static/images/logo.png",
	"title_end_content" => " - Powered By HuajiMC",
	
	//社区配置
	"default_avatar" => "static/images/avatar.png",
	"default_forum_icon" => "static/images/forum.png",
	"open_register" => "1",
	"login_email_code" => "0",
	"register_email_code" => "0",
	"email_code_title" => "HuajiBBS 邮箱验证码",
	"email_code_content" => "<h1><center>HuajiBBS邮箱验证码</center></h1>\\n<hr>\\n<p>尊敬的 {username}，<br>\\n你正在进行 {action} 操作，你的验证码为\\n<center><a href=\"#\" style=\"text-decoration:none\"><h1 style=\"color:red\">{code}</h1></a></center>\\n<b>* 验证码有效期 <b>10</b> 分钟，过期后请重新获取</b></p>\\n<hr>\\n<center style=\"color:gray\">请不要将验证码告诉他人，否则后果自负</center>",
	"point_name" => "积分",
	"create_thread_frequency" => "0",
	"current_template" => "default",
	
	//SMTP配置
	"smtp_host" => "",
	"smtp_username" => "",
	"smtp_password" => "",
	"smtp_port" => "",
	"smtp_email" => "",
	"smtp_name" => "",
	
	//数据库配置（mysql）
	"mysql_host" => "{$host}",
	"mysql_username" => "{$dbusername}",
	"mysql_password" => "{$dbpassword}",
	"mysql_dbname" => "{$name}",
	"mysql_port" => "{$port}",
	"mysql_charset" => "utf8",
];
EOF;
file_put_contents('../config/config.php',$config);

puts(1,'安装成功');