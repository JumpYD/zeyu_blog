<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta name="renderer" content="webkit"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="../resource/jquery/js/jquery-1.7.min.js"></script>
		<script type="text/javascript" src="../resource/jquery/js/jquery.alerts.js"></script>
		<script type="text/javascript" src="../resource/bootstrap/js/bootstrap.min.js"></script>
		<script src="../resource/ace-master/build/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
		<link href="../resource/bootstrap/css/docs.css" rel="stylesheet" type="text/css">
		<link href="../resource/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
		<link href="../resource/bootstrap/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
		<link href="../resource/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../resource/bootstrap/css/prettify.css" rel="stylesheet" type="text/css">
		<link href="../resource/bootstrap/css/github.min.css" rel="stylesheet" type="text/css">
		<link href="../resource/jquery/css/jquery.alerts.css" rel="stylesheet" type="text/css">
		<link href="../resource/jquery/css/jquery.ui.css" rel="stylesheet" type="text/css">
		<link href="../resource/zeyu_blog/css/zeyu_blog.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="images/icon.png">
		<{if $category_id==2}>
			<{if $category_id==1}>
				<link href="../resource/bootstrap/css/site.css" rel="stylesheet">
			<{/if}>
		<{/if}>
		<{if $title=='龙潭相册'}>
			<script type="text/javascript" src="../resource/datetimepicker-master/js/bootstrap-datetimepicker.js"></script>
			<link href="../resource/datetimepicker-master/build/build_standalone.less" rel="stylesheet" type="text/css">
			<link href="../resource/datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
		<{/if}>
		<div id="navbar">
			<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
				<title><{if $title==''}>龍潭齋<{/if}><{if $title!=''}><{$title}><{/if}></title>
				<div class="container">
					<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
						<ul class="nav navbar-nav">
							<li class="active">
							<a href="/html/index.php">龙潭斋</a>
							</li>
							<li>
							<a href="/html/note.php">读书笔记</a>
							</li>
							<li>
							<a href="/html/debin.php?category=1">龙潭书斋</a>
							</li>
							<li>
							<a href="/html/debin.php?category=4">技术分享</a>
							</li>
							<li>
							<a href="/html/debin.php?category=3">龙渊阁记</a>
							</li>
							<{if $is_root}>
							<li>
							<a href="/html/debin.php?category=5">龙泉日记</a>
							</li>
							<li>
							<a href="/html/debin.php?category=mood">心情小说</a>
							</li>
							<li>
							<a href="/html/earnings.php">龙泉财报</a>
							</li>
							<{/if}>
							<li>
							<a href="/html/bloginfos.php">数据统计</a>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
						<{if $is_root}>
							<li>
							<a href="/stats/?">站长工具</a>
							</li>
							<li>
							<a href="/html/test.php">草稿</a>
							</li>
							<li>
							<a href="/html/pictures.php">相册</a>
							</li>
							<li>
							<a href="/html/search.php">检索</a>
							</li>
						<{else}>
							<li>
							<a href="/html/search.php">检索</a>
							</li>
							<li>
							<a href="javascript:void(0)" data-toggle="modal" data-target="#login_modal">登录</a>
							</li>
							<li>
							<a href="javascript:void(0)" onclick="jAlert('对不起，暂时不支持注册，有任何交流，请发邮件至 zeyu203@qq.com', '温馨提示')">注册</a>
							</li>
						<{/if}>
						</ul>
					</nav>
				</div>
			</header>
		</div>
	</head>
	<body style="background: url(<{$background}>) fixed no-repeat; background-size: cover;">
	<link href='../resource/bootstrap/css/nav.css'  rel="stylesheet">
	<{include 'modal.tpl'}>
