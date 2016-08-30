<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="/monitor/Public/css/bootstrap.css" rel="stylesheet">
	<script src="/monitor/Public/js/html5shiv.min.js"></script>
	<script src="/monitor/Public/js/respond.min.js"></script>
	<script src="/monitor/Public/js/zDialog.js"></script>
	<script src="/monitor/Public/js/zDrag.js"></script>
	<script src="/monitor/Public/js/my.js"></script>
	<style>
		html {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}
		body {
			font-family: 'Microsoft Yahei', '微软雅黑', '宋体', \5b8b\4f53, Tahoma, Arial, Helvetica, STHeiti;
			margin: 0;
		}
		.main-nav {
			margin-left: 1px;
		}
			.main-nav.nav-tabs.nav-stacked > li {
			}
				.main-nav.nav-tabs.nav-stacked > li > a {
					padding: 10px 8px;
					font-size: 12px;
					font-weight: 600;
					color: #4A515B;
					background: #E9E9E9;
					background: -moz-linear-gradient(top, #FAFAFA 0%, #E9E9E9 100%);
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FAFAFA), color-stop(100%,#E9E9E9));
					background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
					background: -o-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
					background: -ms-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
					background: linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9');
					-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9')";
					border: 1px solid #D5D5D5;
					border-radius: 4px;
				}
					.main-nav.nav-tabs.nav-stacked > li > a > span {
						color: #4A515B;
					}
				.main-nav.nav-tabs.nav-stacked > li.active > a, #main-nav.nav-tabs.nav-stacked > li > a:hover {
					color: #FFF;
					background: #3C4049;
					background: -moz-linear-gradient(top, #4A515B 0%, #3C4049 100%);
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4A515B), color-stop(100%,#3C4049));
					background: -webkit-linear-gradient(top, #4A515B 0%,#3C4049 100%);
					background: -o-linear-gradient(top, #4A515B 0%,#3C4049 100%);
					background: -ms-linear-gradient(top, #4A515B 0%,#3C4049 100%);
					background: linear-gradient(top, #4A515B 0%,#3C4049 100%);
					filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049');
					-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#4A515B', endColorstr='#3C4049')";
					border-color: #2B2E33;
				}
					#main-nav.nav-tabs.nav-stacked > li.active > a, #main-nav.nav-tabs.nav-stacked > li > a:hover > span {
						color: #FFF;
					}
			.main-nav.nav-tabs.nav-stacked > li {
				margin-bottom: 4px;
			}
		.nav-header.collapsed > span.glyphicon-chevron-toggle:before {
			content: "\e114";
		}
		.nav-header > span.glyphicon-chevron-toggle:before {
			content: "\e113";
		}
		footer.duomi-page-footer {
			background-color: white;
		}
			footer.duomi-page-footer .beta-message {
				color: #a4a4a4;
			}
				footer.duomi-page-footer .beta-message a {
					color: #53a2e4;
				}
			footer.duomi-page-footer .list-inline a, footer.authenticated-footer .list-inline li {
				color: #a4a4a4;
				padding-bottom: 30px;
			}
		footer.duomi-page-footer {
			background-color: white;
		}
			footer.duomi-page-footer .beta-message {
				color: #a4a4a4;
			}
				footer.duomi-page-footer .beta-message a {
					color: #53a2e4;
				}
			footer.duomi-page-footer .list-inline a, footer.authenticated-footer .list-inline li {
				color: #a4a4a4;
				padding-bottom: 30px;
			}
		/*********************************************自定义部分*********************************************/
		.secondmenu a {
			font-size: 12px;
			color: #4A515B;
			text-align: left;
			border-radius: 4px;
		}
		.secondmenu > li > a:hover {
			background-color: #6f7782;
			border-color: #428bca;
			color: #fff;
		}
		.secondmenu li.active {
			background-color: #6f7782;
			border-color: #428bca;
			border-radius: 4px;
		}
			.secondmenu li.active > a {
				color: #ffffff;
			}
		.navbar-static-top {
			background-color: #212121;
			margin-bottom: 5px;
		}
		.navbar-brand {
			background: url("/monitor/Public/images/ruyo_net_w_32.png") no-repeat 10px 8px;
			display: inline-block;
			vertical-align: middle;
			padding-left: 50px;
			color: #fff;
		}
			.navbar-brand:hover {
				color: #fff;
			}
		.collapse.glyphicon-chevron-toggle, .glyphicon-chevron-toggle:before {
			content: "\e113";
		}
		.collapsed.glyphicon-chevron-toggle:before {
			content: "\e114";
		}
	</style>
</head>

<body>
<script>
	var xmlhttp;
	function loadXMLDoc(func){
		xmlhttp=null;
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				if(func=='passmonit'){
					document.getElementById("result").innerHTML=xmlhttp.responseText;
				}else{
					var diag = new Dialog();
					diag.Width = 750;
					diag.Height = 350;
					diag.Title = '内容页为html代码的窗口';
					diag.InnerHtml=xmlhttp.responseText;
					diag.OKEvent = function(){diag.close();};//点击确定后调用的方法
					diag.show();
				}
			}
		}
		xmlhttp.open("POST","/monitor/index.php/Home/Index/getajax",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");xmlhttp.send("func="+func);
	}
	function doaction(func){
		xmlhttp=null;
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				/*
				if(xmlhttp.responseText==1 || xmlhttp.responseText==2){
					if(xmlhttp.responseText==1){
						document.getElementById("setmanu").innerHTML="手动测试";
					}else{
						document.getElementById("setmanu").innerHTML="自动测试";
					}
				*/
				if(xmlhttp.responseText==1){
					alert('已发送短信！');
				}else{
					//alert("设置失败，请联系系统管理员！")
					alert(xmlhttp.responseText)
				}
			}
		}
		xmlhttp.open("POST","/monitor/index.php/Home/Index/getajax",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("func="+func);
	}
	function foritems2(all,url="foritems2",outid="result",warn="N",warnword="确定吗？",add="正在加载。。。"){
		if(warn=="Y"){
			if(!confirm(warnword)){
				return false;
			}
		}
		xmlhttp=null;
		if(window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				//document.getElementById(outid).innerHTML=xmlhttp.responseText;
				set_innerHTML(outid,xmlhttp.responseText);
			}else{
				//document.getElementById(outid).innerHTML=add;
				//foritems2(all,url,outid,warn,warnword,add);
				set_innerHTML(outid,add);
			}
		}
		xmlhttp.open("POST","/monitor/index.php/Home/Index/"+url,false);//控制ajax请求为异步还是同步
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(all);
	}
	function saveadd(){
		var checkbox=document.getElementsByName("optionsRadios");
		for(var i=0;i<checkbox.length;i++){
			if(checkbox[i].checked){
				var itm_type=checkbox[i].value;
			}
		}
		//alert("item2id=6&act=saveadd"+"&itm_name="+document.getElementById("itm_name").value+"&itm_type="+itm_type+"&itm_id="+document.getElementById("itm_id").value+"&itm_belo="+document.getElementById("itm_belo").value+"&itm_from="+document.getElementById("itm_from").value+"&itm_cols="+document.getElementById("itm_cols").value);
		foritems2(all="act=saveadd&item2id=6"+"&itm_name="+document.getElementById("itm_name").value+"&itm_type="+itm_type+"&itm_id="+document.getElementById("itm_id").value+"&itm_belo="+document.getElementById("itm_belo").value+"&itm_from="+document.getElementById("itm_from").value,url="foritems2",outid="message",warn="N",warnword="确定吗？",add="数据库插入错误，请检查添加信息！");
	}
	function saveedit(){
		var checkbox=document.getElementsByName("optionsRadios");
		for(var i=0;i<checkbox.length;i++){
			if(checkbox[i].checked){
				var itm_type=checkbox[i].value;
			}
		}
		//alert("act=saveedit&item2id=6"+"&itm_title="+document.getElementById("itm_name").value+"&itm_type="+itm_type+"&itm_id="+document.getElementById("itm_id").value+"&itm_belo="+document.getElementById("itm_belo").value+"&itm_from="+document.getElementById("itm_from").value);
		foritems2(all="act=saveedit&item2id=6"+"&itm_name="+document.getElementById("itm_name").value+"&itm_type="+itm_type+"&itm_num="+document.getElementById("itm_num").value+"&itm_belo="+document.getElementById("itm_belo").value+"&itm_id="+document.getElementById("itm_id").value+"&old_itm_sec="+document.getElementById("old_itm_sec").value+"&old_itm_type="+document.getElementById("old_itm_type").value+"&itm_from="+document.getElementById("itm_from").value,url="foritems2",outid="message",warn="Y",warnword="确定提交吗？",add="数据库错误，请检查添加信息！");
	}
</script>
	<div class="navbar navbar-duomi navbar-static-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#" id="logo">监控管理系统
				</a>
			</div>
			<div class="navbar-right">
				<span class="navbar-brand" style="background:none">欢迎使用 [<a id="logout" href="logout">退出</a>]
				</span>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2">
				<ul id="main-nav" class="main-nav nav nav-tabs nav-stacked" style="">
				<?php $__FOR_START_2058843408__=1;$__FOR_END_2058843408__=$items_max+1;for($i=$__FOR_START_2058843408__;$i < $__FOR_END_2058843408__;$i+=1){ if($items1[$i] != null): if($items2[$i] != null): ?><li><a href="#<?php echo ($items1[$i]['title']); ?>" class="nav-header collapsed" data-toggle="collapse"><?php echo ($items1[$i]['title']); ?></a>
							<ul id="<?php echo ($items1[$i]['title']); ?>" class="nav nav-list secondmenu collapse" style="height: 0px;">
							<?php $__FOR_START_1004676103__=0;$__FOR_END_1004676103__=$items2s[$i];for($n=$__FOR_START_1004676103__;$n < $__FOR_END_1004676103__;$n+=1){ ?><li><a href="#" onclick=foritems2("item2id=<?php echo ($items2[$i]['id'][$n]); ?>&item_type=2&act=list&url=/monitor/index.php/Home/Index/foritems2")><?php echo ($items2[$i]['title'][$n]); ?></a></li><?php } ?>
							</ul>
						<?php else: ?>
							<li><a href="#" class="nav-header collapsed" data-toggle="collapse" onclick=foritems2("item1id=<?php echo ($items1[$i]['id']); ?>&item_type=1&act=list&url=/monitor/index.php/Home/Index/foritems2")><?php echo ($items1[$i]['title']); ?></a><?php endif; endif; ?>
					</li><?php } ?>
				</ul>
			</div>
			<div class="col-md-10" id="result">
				<font style="font-size:0.6cm">欢迎使用云集监控平台</font>
			</div>
		</div>
	</div>
	<script src="/monitor/Public/js/jquery.js"></script>
	<script src="/monitor/Public/js/bootstrap.min.js"></script>
</body>
</html>