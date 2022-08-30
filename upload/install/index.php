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

if(is_file('../config/config.php')) {
	die('你已经安装过了，如果需要重新安装，请删除 /config/config.php 文件');
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>HuajiBBS 安装程序 - Powered by HuajiMC</title>
	
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="../static/js/app.js"></script>
	<link rel="stylesheet" href="../static/css/app.css">
	<link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.css">
</head>
<body style="background-color:#f6f6f6">
	<div class="jumbotron text-center text-white mb-0" style="background-color:#009688;">
		<h1 class="display-3">HuajiBBS 安装程序</h1>
		<h3>HuajiBBS 致力于为用户提供最优质的 BBS 系统</h3>
	</div>
	<div class="container-fluid p-5" id="1">
		<div class="card">
			<div class="card-body">
				<h5 class="text-center">HuajiBBS 授权协议</h5>
				<p></p>
				<p>版权所有 &copy; 2022，滑稽MC（HuajiMC）个人保留所有权利。</p>
				<p>感谢您的选择 HuajiBBS，我们将努力为您提供最优质的服务、最愉快的体验，HuajiBBS官网为<a href="http://huajimc.gitee.io/HuajiBBS">http://huajimc.gitee.io/HuajiBBS</a>。</p>
				<p>根据《中华人民共和国著作权法》，滑稽MC依法享有 HuajiBBS 著作权，受到法律和国际公约保护。请使用者均需仔细阅读此协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用 HuajiBBS 系统。</p>
				<p>滑稽MC拥有对本授权协议的最终解释权。</p>
				<ol style="list-style-type:cjk-ideographic">
					<li>
						协议许可的权利
						<ol>
							<li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用；</li>
							<li>您可以在协议规定的约束和限制范围内修改 HuajiBBS 源代码或界面风格以适应您的网站要求；</li>
							<li>您拥有使用本软件构建的社区中全部用户资料、主题及相关信息的所有权，并独立承担与主题内容的相关法律义务；</li>
							<li>获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术支持内容，自购买时刻起， 在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。</li>
						</ol>
					</li>
					<li>
						协议规定的约束和限制
						<ol>
							<li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆<a href="http://huajimc.gitee.io/HuajiBBS">http://huajimc.gitee.io/HuajiBBS</a> 参考相关说明；</li>
							<li>不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证；</li>
							<li>无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用 HuajiBBS 的整体或任何部分，未经书面许可，页面页脚处的版权信息和官网网站的链接（<a href="http://huajimc.gitee.io/HuajiBBS">http://huajimc.gitee.io/HuajiBBS</a> ）都必须保留，而不能清除或修改；</li>
							<li>未经作者允许，禁止 HuajiBBS 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发；</li>
							<li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
						</ol>
					</li>
					<li>
						有限担保和免责声明
						<ol>
							<li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的；</li>
							<li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任；</li>
							<li>滑稽MC不对使用本软件构建的社区中的主题或信息承担责任，全部责任由您自行承担；</li>
							<li>滑稽MC对HuajiBBS的及时性、准确性、安全性不作担保，由于不可抗因素、滑稽MC无法控制的因素（包括黑客攻击、停断电等）等造成软件使用和服务中止或终止，滑稽MC不对此承担责任；</li>
							<li>滑稽MC为了保障个人业务发展和调整的自主权，滑稽MC拥有随时经或未经事先通知而修改服务内容、中止或终止部分或全部软件使用和服务的权利，修改会公布于 HuajiBBS 官网相关页面上，一经公布视为通知。滑稽MC行使修改或中止、终止部分或全部软件使用服务的权利而造成损失的，滑稽MC不需对您或任何第三方负责。</li>
						</ol>
					</li>
				</ol>
				<p>有关 HuajiBBS 最终用户授权协议、商业授权与技术服务的详细内容，均由 HuajiBBS 官方网站独家提供。滑稽MC拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>
				
				<button type="button" class="btn btn-primary btn-block" onclick="$('#1').hide();$('#2').show();">我已同意上述协议，继续安装</button>
			</div>
		</div>
	</div>
	<div class="container-fluid p-5" id="2" style="display:none">
		<?php $e = 0; ?>
		<h3>环境检测</h3>
		<table class="table table-light">
			<thead>
				<tr>
					<th>项目</th>
					<th>要求</th>
					<th>结果</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>PHP版本</td>
					<td>7.*</td>
					<td><?php echo PHP_VERSION; ?>
						<?php if(explode('.',PHP_VERSION)[0] == 7) { ?>
						<i class="fa fa-check text-success"></i>
						<?php } else { $e ++; ?>
						<i class="fa fa-remove text-danger"></i>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>mysqli扩展</td>
					<td>开启</td>
					<td><?php echo extension_loaded('mysqli') ? '开启' : '未开启'; ?>
						<?php if(extension_loaded('mysqli')) { ?>
						<i class="fa fa-check text-success"></i>
						<?php } else { $e ++; ?>
						<i class="fa fa-remove text-danger"></i>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>mbstring扩展</td>
					<td>开启</td>
					<td><?php echo extension_loaded('mbstring') ? '开启' : '未开启'; ?>
						<?php if(extension_loaded('mbstring')) { ?>
						<i class="fa fa-check text-success"></i>
						<?php } else { $e ++; ?>
						<i class="fa fa-remove text-danger"></i>
						<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
		<h3>目录权限检测</h3>
		<table class="table table-light">
			<thead>
				<tr>
					<th>目录</th>
					<th>要求</th>
					<th>结果</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>/config</td>
					<td>可读可写</td>
					<td>
						<?php if(@file_put_contents('../config/install', '1') === false) { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可写
			 			<?php } else if(@file_get_contents('../config/install') !== '1') { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可读
			 			<?php } else { ?>
			 			<?php unlink('../config/install'); ?>
						<i class="fa fa-check text-success"></i> 可读可写
			 			<?php } ?>
					</td>
				</tr>
				<tr>
					<td>/upload</td>
					<td>可读可写</td>
					<td>
						<?php if(@file_put_contents('../upload/install', '1') === false) { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可写
			 			<?php } else if(@file_get_contents('../upload/install') !== '1') { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可读
			 			<?php } else { ?>
			 			<?php unlink('../upload/install'); ?>
						<i class="fa fa-check text-success"></i> 可读可写
			 			<?php } ?>
					</td>
				</tr>
				<tr>
					<td>/template</td>
					<td>可读可写</td>
					<td>
						<?php if(@file_put_contents('../template/install', '1') === false) { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可写
			 			<?php } else if(@file_get_contents('../template/install') !== '1') { $e ++; ?>
						<i class="fa fa-remove text-danger"></i> 不可读
			 			<?php } else { ?>
			 			<?php unlink('../template/install'); ?>
						<i class="fa fa-check text-success"></i> 可读可写
			 			<?php } ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php if($e) { ?>
		<button type="button" class="btn btn-primary btn-block disabled">您有<?php echo $e; ?> 个项目不符合要求，请修改后再来安装</button>
		<?php } else { ?>
		<button type="button" class="btn btn-primary btn-block" onclick="$('#2').hide();$('#3').show();">符合要求，继续安装</button>
		<?php } ?>
	</div>
	<div class="container-fluid p-5" id="3" style="display:none">
		<div class="layout-tips"></div>
		<form id="config" class="mb-3">
			<h3>数据库设置</h3>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库类型（目前仅支持 MySQL）</label>
				<div class="col-sm-9">
					<label><input type="radio" name="db_type" value="mysql" checked> mysql</label>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库引擎（无特殊要求无需修改）</label>
				<div class="col-sm-9">
					<label><input type="radio" name="db_engine" value="myisam" checked> MyISAM</label>
					<label><input type="radio" name="db_engine" value="innodb"> InnoDB</label>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库服务器</label>
				<div class="col-sm-9">
					<input type="text" name="db_host" class="form-control" value="localhost" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库名</label>
				<div class="col-sm-9">
					<input type="text" name="db_name" class="form-control" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库用户名</label>
				<div class="col-sm-9">
					<input type="text" name="db_username" class="form-control" value="root" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库密码</label>
				<div class="col-sm-9">
					<input type="text" name="db_password" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">数据库端口</label>
				<div class="col-sm-9">
					<input type="number" name="db_port" class="form-control" value="3306" required>
				</div>
			</div>
			
			<h3>管理员设置</h3>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">管理员用户名</label>
				<div class="col-sm-9">
					<input type="text" name="admin_username" class="form-control" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">管理员密码</label>
				<div class="col-sm-9">
					<input type="text" name="admin_password" class="form-control" required>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 form-control-label">管理员邮箱</label>
				<div class="col-sm-9">
					<input type="text" name="admin_email" class="form-control" required>
				</div>
			</div>
			
			<button type="submit" class="btn btn-primary btn-block">开始安装</button>
		</form>
		<h3>安装过程</h3>
		<div class="card">
			<div class="card-body" id="step"></div>
		</div>
		<script>
		HuajiBBS.submit('#config',function(data) {
			$('#step').append('<p class="text-success">开始安装...</p>');
			$.post('./install.php',data.field,function(data) {
				if(data.status) {
					$('#step').append('<p class="text-success">'+data.msg+'</p>');
					$('#step').append('<p><a href="../">访问前台</a> ｜ <a href="../index.php/admin">访问后台</a></p>');
				} else {
					$('#step').append('<p class="text-danger">'+data.msg+'</p>');
				}
			});
		});
		</script>
	</div>
	<div class="text-center">
		Copyright &copy; HuajiMC. All rights reserved.
	</div>
</body>
</html>