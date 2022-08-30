<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $msg; ?> - HuajiBBS</title>
	<script src="<?php echo URL; ?>/static/js/jquery.min.js"></script>
</head>
<body>
	<div style="text-align:center;margin-top:100px">
		<img src="<?php echo URL; ?>/static/images/<?php echo $state ? 'success' : 'fail'; ?>.svg" width="15%">
		<h1 style="font-weight:lighter"><?php echo $msg; ?></h1>
		<p>正在 <a href="<?php echo $url; ?>" style="color:black">跳转</a> ，等待时间：<b id="s"><?php echo $time; ?></b></p>
		<p>如果你被困在提示页面，请点击 <a href="<?php echo URL; ?>" style="color:black">返回首页</a></p>
	</div>
	
	<script>
		(function(){
		var wait = document.getElementById('s'),href = "<?php echo $url; ?>";
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time == 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
		})();
	</script>
</body>
</html>