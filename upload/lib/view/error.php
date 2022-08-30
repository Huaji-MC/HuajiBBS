<?php defined('ACCESS') or die('You have no access.'); ?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>HuajiBBS 错误调试页 - HuajiBBS</title>
	<style>
	body {
		padding: 10px;
	}
	table {
		width: 100%;
		border: 2px solid #B3B3B3;
		border-collapse: collapse;
		border-spacing: 1px;
	}
	td {
		border: 1px solid #C0C0C0;
		padding: 5px;
	}
	td.key {
		background-color: #FF4500;
	}
	td.value {
		background-color: #DCDCDC;
	}
	td.title {
		background-color: #DCDCDC;
		border-top: 2px solid black;
		border-bottom: 2px solid black;
		text-align: center;
		font-weight: bold;
	}
	</style>
</head>
<body>
	<table>
		<tr>
			<td colspan="2" class="title">
				HuajiBBS 错误调试页
			</td>
		</tr>
		<tr>
			<td class="key">错误码</td>
			<td class="value"><?php echo $errno; ?></td>
		</tr>
		<tr>
			<td class="key">错误信息</td>
			<td class="value"><?php echo $message; ?></td>
		</tr>
		<tr>
			<td class="key">错误文件</td>
			<td class="value"><?php echo $file; ?></td>
		</tr>
		<tr>
			<td class="key">错误行</td>
			<td class="value"><?php echo $line; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="value">
				我们已经记录了这次错误，给您带来不便请谅解！
			</td>
		</tr>
	</table>
</body>
</html>