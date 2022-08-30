var HuajiBBS = {
	//提示框
	tips: function(msg,type) {
		var html = $('<div class="alert alert-'+type+'">'+msg+'</div>');
		$('.layout-tips').append(html);
		
		var time = 5;
		setInterval(function() {
			time --;
			if(time == 0) {
				html.remove();
				return;
			}
		},1000);
		return this;
	},
	//表单提交
	submit: function(selector,func) {
		var form = $(selector);
		form.attr('onsubmit','javascript:return false;');
		var submit = form.find('[type="submit"]');
		submit.click(function() {
			var controls = form.find('[required]');
			controls.each(function() {
				if(! $(this).val()) {
					HuajiBBS.tips('请填写表单必填项！','warning');
					$(this).focus();
					throw SyntaxError();
				}
			});
			var data = form.serializeArray(),field = {};
			$.each(data, function() {
				field[this.name] = this.value;
			});
			data = {form: form,field: field};
			func(data);
		});
		return this;
	},
	//文本颜色
	color: function() {
		$('[color]').each(function() {
			$(this).css('color',$(this).attr('color'));
		});
		return this;
	},
	//背景颜色
	bgcolor: function() {
		$('[bgcolor]').each(function() {
			$(this).css('background-color',$(this).attr('bgcolor'));
		});
		return this;
	},
	//设置COOKIE
	setCookie: function(name,value) {
		var exp = new Date();
		exp.setTime(exp.getTime() + 30 * 86400 * 1000);
		document.cookie = name + '=' + escape(value) + ';expires=' + exp.toGMTString();
		return this;
	},
	//读取COOKIE
	getCookie: function(name) {
		var arr,reg = new RegExp('(^| )' + name + '=([^;]*)(;|$)');
		if(arr = document.cookie.match(reg)) {
			return unescape(arr[2]);
		} else {
			return '';
		}
	},
	//复制文本
	copy: function(text) {
		var elem = document.createElement('textarea');
		document.body.appendChild(elem);
		elem.value = text;
		elem.select();
		document.execCommand('copy');
		document.body.removeChild(elem);
		return this;
	},
	//选项卡
	tab: function(tabsE,contentE) {
		tabs = $(tabsE);
		content = $(contentE);
		content.children('div').hide();
		content.children('#'+tabs.children('.active').attr('tab')).show();
		tabs.children('li').click(function() {
			content.children('div').hide();
			content.children('#'+$(this).attr('tab')).show();
			tabs.children('.active').removeClass('active');
			$(this).addClass('active');
		});
		return {
			change: function(tab) {
				content.children('div').hide();
				content.children('#'+tab).show();
				tabs.children('.active').removeClass('active');
				tabs.find('li[tab="'+tab+'"]').addClass('active');
			},
		};
	},
	//时间戳转日期
	timestamp2date: function(timestamp) {
		var date = new Date(timestamp * 1000);
		
		var Y = date.getFullYear() + '-';
		var M = (date.getMonth() + 1 < 10 ? "0" + (date.getMonth() + 1) : date.getMonth() + 1) + "-";
		var D = date.getDate() < 10 ? "0" + date.getDate() : date.getDate() + " ";
		var h = " " + (date.getHours() < 10 ? " 0" + date.getHours() : date.getHours()) + ":";
		var m = (date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes()) + ":";
		var s = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
		
		return Y + M + D + h + m + s;
	},
	//html转义
	htmlEncode: function(html) {
		var temp = document.createElement("div");
		(temp.textContent != undefined) ? (temp.textContent = html) : (temp.innerText = html);
		var output = temp.innerHTML;
		temp = null;
		return output;
	},
	//html反转义
	htmlDecode: function(text) {
		var temp = document.createElement("div");
		temp.innerHTML = text;
		var output = temp.innerText || temp.textContent;
		temp = null;
		return output;
	},
	//post请求
	request: function(url) {
		var params = arguments[1] ? arguments[1] : {};
		var options = arguments[2] ? arguments[2] : {};
		var defaultOptions = {
			success: function(data) {
				HuajiBBS.tips(data.msg,'success');
				setTimeout(function() {
					location.reload();
				},1000);
    		},
    		error: function(data) {
    			HuajiBBS.tips(data.msg,'danger');
    		}
		};
		var callback = function(data) {
			if(data.status) {
				options.success ? options.success(data) : defaultOptions.success(data);
			} else {
				options.error ? options.error(data) : defaultOptions.error(data);
			}
		}
		$.post(url,params,callback);
	}
};