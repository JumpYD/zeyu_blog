<{include 'header.tpl'}>
<style>
p
{
	text-indent:2em;
	word-wrap:break-word; 
}
</style>
<div class="bs-header" id="content" style="FILTER: progid:DXImageTransform.Microsoft.Gradient (GradientType=1, StartColorStr=#d9e45d EndColorStr=darkolivegreen .opacity{ opacity:0.3; filter: alpha(opacity=30); background-color:#000; }" >
	<div class="header container">
		<br />
		<h1><{$title}></h1>
		<p style="text-indent:0em;"><{$title_desc}></p>
		<p style="text-indent:5em;" class="bs-header-small"><{$inserttime}></p>
	</div>
</div>

<br/>
<br/>
<br/>

<div class="container_wrapper">
	<div class="container bs-docs-container">
		<{if $contents!=''}>
		<div class="row">
			<{if $index!=''}>
			<script src="../resource/stickUp-master/stickUp.min.js"></script>
			<style>
				.isStuck
				{
					width:56%;
				}
			</style>
			<div class="navbar-wrapper">
				<div class="col-md-3">
					<div class="bs-sidebar hidden-print" role="complementary">
						<ul class="nav bs-sidenav">
							<li>
							<a href="#">回到顶端</a>
							</li>
							<{foreach item=idx_val key=idx_key from=$index}>
							<li>
							<a href="#<{$idx_key}>"><{$idx_val}></a>
							</li>
							<{/foreach}>
							<{if $category_id!='2'}>
							<{if $category_id!='5'}>
							<{if $category_id!='6'}>
							<li>
							<a href="#tags">标签</a>
							</li>
							<{/if}>
							<{/if}>
							<{/if}>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-3"></div>
			<div class="col-md-9" role="main">
				<{else}>
				<div class="col-md-12" role="main">
					<{/if}>
					<div class="bs-docs-section">
						<{$contents}>
						<{if $category_id!="2"}>
						<{if $category_id!="5"}>
						<{if $category_id!='6'}>
						<br /><br /><br /><br /><br />
						<div class="page-header">
							<div id="tags">标签</div>
						</div>
						<{foreach item=tag from=$tags}>
						<{$tag}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<{/foreach}>
						<{/if}>
						<{/if}>
						<{/if}>
					</div>
					<br /><br />
				</div>
			</div>
			<{/if}>
		</div>
	</div>
</div>

<{if $title=="测试页面"}>
<script language="JavaScript">
function myrefresh()
{
	window.location.reload();
}
setTimeout('myrefresh()',5000); //指定1秒刷新一次
</script>
<{/if}>

<{include 'footer.tpl'}>
