<?php defined('ACCESS') or die('You have no access.'); ?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>HuajiBBS 异常调试页 - HuajiBBS</title>
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
		background-color: #228B22;
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
				HuajiBBS 异常调试页
			</td>
		</tr>
		<tr>
			<td class="key">异常码</td>
			<td class="value"><?php echo $code; ?></td>
		</tr>
		<tr>
			<td class="key">异常信息</td>
			<td class="value"><?php echo $message; ?></td>
		</tr>
		<tr>
			<td class="key">异常文件</td>
			<td class="value"><?php echo $file; ?></td>
		</tr>
		<tr>
			<td class="key">异常行</td>
			<td class="value"><?php echo $line; ?></td>
		</tr>
		<?php if($trace) { ?>
		<tr>
			<td colspan="2" class="title">
				堆栈跟踪（Trace）
			</td>
		</tr>
			<?php foreach($trace as $key => $row) { ?>
			<tr>
				<td colspan="2" class="value" style="text-align: center">
					# <?php echo $key; ?>
				</td>
			</tr>
			<tr>
				<td class="key">文件</td>
				<td class="value"><?php echo $row['file']; ?></td>
			</tr>
			<tr>
				<td class="key">行号</td>
				<td class="value"><?php echo $row['line']; ?></td>
			</tr>
			<tr>
				<td class="key">调用</td>
				<td class="value">\<?php echo $row['class']; ?><?php echo $row['type']; ?><?php echo $row['function']; ?>({{implode(',',$row['args'])}})</td>
			</tr>
			<?php } ?>
		<?php } ?>
		<tr>
			<td colspan="2" class="value">
				我们已经记录了这次异常，给您带来不便请谅解！
			</td>
		</tr>
	</table>
</body>
</html>