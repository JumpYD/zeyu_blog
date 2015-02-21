<{include 'header.tpl'}>
<br />
<br />
<br />
<br />
<div id="customDoc">
	<script>window.wpo={start:new Date*1,pid:109,page:'qingBlog2x'}</script>
	<!--[if IE]>
	<script type="text/javascript">(function(){var e="abbr,article,aside,audio,canvas,datalist,details,dialog,eventsource,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video".split(","),t=e.length;while(t--)document.createElement(e[t])})();</script>
	<![endif]-->
	<!--[if (lt IE 8.0)]>
	<link href="../resource/hibaidu/css/d097b2a7.css" type=text/css rel=stylesheet>
	<![endif]-->
	<!--[if (!IE)|(gte IE 8.0)]>
	<!-->
	<link href="../resource/hibaidu/css/c2391e47.css" type=text/css rel=stylesheet>
	<!--<![endif]-->
	<!--[if (lt IE 8.0)]>
	<link rel=stylesheet type=text/css href="../resource/hibaidu/css/5ba753ab.css">
	<![endif]-->
	<!--[if (!IE)|(gte IE 8.0)]>
	<!-->
	<link rel=stylesheet type=text/css href="../resource/hibaidu/css/a90beb01.css">
	<!--<![endif]-->
	<!--[if (lt IE 8.0)]>
	<link rel=stylesheet href="../resource/hibaidu/css/806cd731.css">
	<![endif]-->
	<!--[if (!IE)|(gte IE 8.0)]>
	<!-->
	<link rel=stylesheet href="../resource/hibaidu/css/e05e57b1.css">
	<!--<![endif]-->
	<style type="text/css">
		#customDoc
		{
			background:url(<{$background}>) fixed no-repeat;
			background-size:cover;
		}
		#customDoc .mod-cs-contentblock
		{
			background-color:rgba(255,255,255,.4);
			-moz-border-radius: 15px;
			-webkit-border-radius: 15px;
			border-radius:15px;
			position:relative;
			_overflow:hidden
		}
		#customDoc .mod-cs-contentblock .cs-contentblock-bg
		{
			display:block;
			position:absolute;
			bottom:0;
			top:0;
			left:0;
			right:0;
			z-index:-1;
			_height:2000px;
			_width:100%;
			_zoom:1;
			background-color:rgb(255,252,252);
			-moz-opacity: 0;
			-webkit-opacity:0;
			opacity: 0;
			filter:alpha(opacity=0);
		}
		#customDoc .mod-cs-header
		{
			margin-top:17px
		}
		#customDoc .mod-cs-header .cs-header-spacename,#customDoc .mod-cs-header .cs-header-spacesummary
		{
			color:rgba(0, 124, 215, 0.63)
		}
		#customDoc .mod-cs-sidebar span.cs-sidebar-follow
		{
			border-color:rgb(84,156,245)
		}
		#customDoc .mod-cs-pagebar a:hover,#customDoc .mod-cs-pagebar span,#customDoc .mod-cs-sidebar a.cs-sidebar-hoverbglink:hover,#customDoc .mod-cs-contentblock a.cs-contentblock-hoverbglink:hover
		{
			background:rgb(84,156,245)
		}
		#qBack2topBtn .back2top-ani-icon .back2top-icon-real
		{
			background:url(images/default.png) left top no-repeat;
			background:url(images/default.gif) left top no-repeat\9
		}
		#qBack2topBtn .back2top-ani-icon .back2top-icon-bg
		{
			background-color:rgb(84,156,245)
		}
	</style>
	<script src="../resource/hibaidu/js/4d5f41d2.js">
	</script>
	<script>wpo.head=new Date*1;</script>
	<section class=mod-page-body>
	<div class="mod-page-main wordwrap clearfix">
		<div class=x-page-container>
			<section class="mod-topspaceinfo mod-cs-header">
			<div class=head-topbar>
			</div>
			<div class=container>
				<h1>
					<div class="space-name cs-header-spacename">
						<{$category}>
					</div>
				</h1>
				<br />
				<br />
				<br />
			</div>
			<div class=head-footer>
			</div>
			</section>
			<section class="grid-98 mod-blogpage">
			<section class="mod-bloglist left">
			<{if $list|@count gte 2}>
			<section class="mod-blog-pagerbar mod-cs-pagebar" style="display: block;font-size:62.5%">
			<div class="mod-pagerbar" id="top_bar">
				<{if $first != ''}>
					<a href="javascript:void(0)" onclick="js_submit('<{$first}>')" class="first">首页</a>
					<a href="javascript:void(0)" onclick="js_submit('<{$pre}>')">&lt;&lt;上一页</a>
				<{/if}>
				<{foreach item=pagenum from=$list}>
					<{if $page==$pagenum}>
						<span><{$pagenum}></span>
					<{else}>
						<a href="javascript:void(0)" onclick="js_submit('<{$pagenum}>')" hidefocus=""><{$pagenum}></a>
					<{/if}>
				<{/foreach}>
				<{if $end != ''}>
					<a href="javascript:void(0)" onclick="js_submit('<{$last}>')" class="next">下一页&gt;&gt;</a>
					<a href="javascript:void(0)" onclick="js_submit('<{$end}>')" class="last">尾页</a>
				<{/if}>
			</div>
			</section>
			<{/if}>
			<{foreach item=article_info from=$infos}>
			<article class="mod-blogitem mod-item-text">
			<div class="mod-real-text">
				<div class=box-postdate>
					<div class=q-day>
						<{$article_info['date']}>
					</div>
					<div class=q-month-year>
						<{$article_info['month']}>
					</div>
				</div>
			</div>
			<div class="mod-realcontent mod-cs-contentblock">
				<div class=cs-contentblock-bg>
				</div>
				<div class=item-head>
					<{if $ismood!=true}>
					<a href="article.php?id=<{$article_info['article_id']}>" class="a-incontent a-title cs-contentblock-hoverlink" target=_blank>
						<{else}>
						<div class="a-incontent a-title cs-contentblock-hoverlink" style='font-family:"PT Serif","Georgia","Helvetica Neue",Arial,sans-serif' >
						<{/if}>
						<{$article_info['title']}>
						<{if $ismood!=true}>
						</a>
						<{else}>
					</div>
					<{/if}>
				</div>
				<div class="item-content cs-contentblock-detailcontent">
					<div class=q-summary>
						<{$article_info['contents']}>
					</div>
				</div>
				<{if $ismood!=true}>
				<div class="item-foot clearfix">
					<span class=box-tag>
						<{foreach item=tag from=$article_info['tags']}>
						<span class=q-tag>#<{$tag['tag_name']}>
						</span>&nbsp;&nbsp;
						<{/foreach}>
					</span>
				</div>
				<{/if}>
				<div class=blog-cmt-wraper>
				</div>
			</div>
			</article>
			<{/foreach}>
			<{if $list|@count gte 2}>
			<section id=pagerBar class="mod-blog-pagerbar mod-cs-pagebar" style="display: block;font-size:62.5%">
			<div class="mod-pagerbar" style="background-color:rgba(255, 255, 255, 0.4)">
				<{if $first != ''}>
					<a href="javascript:void(0)" onclick="js_submit('<{$first}>')" class="first">首页</a>
					<a href="javascript:void(0)" onclick="js_submit('<{$pre}>')">&lt;&lt;上一页</a>
				<{/if}>
				<{foreach item=pagenum from=$list}>
					<{if $page==$pagenum}>
						<span><{$pagenum}></span>
					<{else}>
						<a href="javascript:void(0)" onclick="js_submit('<{$pagenum}>')" hidefocus=""><{$pagenum}></a>
					<{/if}>
				<{/foreach}>
				<{if $end != ''}>
					<a href="javascript:void(0)" onclick="js_submit('<{$last}>')" class="next">下一页&gt;&gt;</a>
					<a href="javascript:void(0)" onclick="js_submit('<{$end}>')" class="last">尾页</a>
				<{/if}>
			</div>
			</section>
			<{/if}>
			</section>
			<section class="mod-rightsidebar clearfix mod-cs-sidebar">
			<div class=mod-siderbar>
				<section id=qFriendlyLinks style="font-size:14px;">
				<div  class=mod-friendlyLinks>
					<p style="text-indent:0em;"><h1 class=friendlyLinks-title><strong style="font-size:26px;">龍&nbsp;泉&nbsp;居&nbsp;士</strong></h1></p>
					<br />
					<p style="text-indent:0em;"><img src="images/psb.jpg" width="158px"></p>
					<br />
					<div class=friendlyLinks-linkList>
						<ul class=friendlyLinks-linkListContainer>
							<li>
							<center>
								<h1 style="font-size: 18px; color: rgb(63, 28, 143); margin: 20px;">
									<strong>最热文章</strong>
								</h1>
							</center>
							</li>
						</ul>
						<ul class=friendlyLinks-linkListContainer style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
							<{foreach item=infos from=$hot_articles}>
							<li>
							<h2 style="line-height:30px">
								<a href="article.php?id=<{$infos['article_id']}>" target="_blank"><{$infos['title']}></a>
							</h2>
							</li>
							<{/foreach}>
						</ul>
						<ul class=friendlyLinks-linkListContainer>
							<li>
							<center>
								<h1 style="font-size: 18px; color: rgb(63, 28, 143); margin: 20px;">
									<strong>最近更新</strong>
								</h1>
							</center>
							</li>
						</ul>
						<ul class=friendlyLinks-linkListContainer style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
							<{foreach item=infos from=$new_articles}>
							<li>
							<h2 style="line-height:30px">
								<a href="article.php?id=<{$infos['article_id']}>" target="_blank"><{$infos['title']}></a>
							</h2>
							</li>
							<{/foreach}>
						</ul>
						<ul class=friendlyLinks-linkListContainer>
							<li>
							<center>
								<h1 style="font-size: 18px; color: rgb(63, 28, 143); margin: 20px;">
									<strong>标签</strong>
								</h1>
							</center>
							</li>
						</ul>
						<ul class=friendlyLinks-linkListContainer style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
							<{foreach item=infos from=$rand_tags}>
							<li>
							<h2 style="line-height:30px">
								<a href="debin.php?category=0&tags=icon_tag_<{$infos['tag_id']}>" target="_blank"><{$infos['tag_name']}></a>
							</h2>
							</li>
							<{/foreach}>
						</ul>
						<ul class=friendlyLinks-linkListContainer>
							<li>
							<center>
								<h1 style="font-size: 18px; color: rgb(63, 28, 143); margin: 20px;">
									<strong>关于博主</strong>
								</h1>
							</center>
							</li>
						</ul>
						<ul>
							<li>
							<h2 style="line-height:30px">
								<a href="article.php?id=10182614">博客简介</a>
							</h2>
							</li>
							<li>
							<h2 style="line-height:30px">
								<strong>QQ：1053038465</strong>
							</h2>
							</li>
							<li>
							<h2 style="line-height:30px">
								<strong>邮箱：zeyu203@qq.com</strong>
							</h2>
							</li>
						</ul>
					</div>
				</div>
				</section>
			</div>
			</section>
			<script src="http://hi.bdimg.com/static/qbase/js/mod/mod_foot.js?v=382c615f.js">
			</script>
			<script>qext.stat.ns('m_20120425_20001');</script>
			</script>
			<!--[if (lt IE 8.0)]>
			<link rel=stylesheet type=text/css href="http://hi.bdimg.com/static/qmessage/css/qmessage_mod_msg_bubble.css?v=a727011c.css">
			<![endif]-->
			<!--[if (!IE)|(gte IE 8.0)]>
			<!-->
			<link rel=stylesheet type=text/css href="http://hi.bdimg.com/static/qmessage/css/qmessage_mod_msg_bubble_datauri.css?v=6ddd3ba9.css">
			<!--<![endif]-->
			</script>
			<link href="http://hi.bdimg.com/static/qbase/css/yoyo/yoyo.css?v=4dd11df1.css" type=text/css rel=stylesheet>
			</script>
			<script>wpo.tti=new Date*1;</script>
			<script src="http://hi.baidu.com/cm/static/js/allsite.js?v=75674092.js?v=201401041944">
			</script>
			<script src="http://hi.bdimg.com/static/qbase/js/mod/mod_bubble.js?v=a9273ef3.js">
			</script>
			<script>document.write(unescape("%3Cscript src='http://hm.baidu.com/h.js%3F8c869b543955d43e496c2efee5b55823' type='text/javascript'%3E%3C/script%3E"));qext.stat.ns('m_20120713_qing_pv');</script>
		</div>
<form action="/html/debin.php" method="post" name="params_form" id="params_form">
	<input type="hidden" id='page' name='page' value="<{$query_info['page']}>"/>
	<input type="hidden" id='limit' name='limit' value="<{$query_info['limit']}>"/>
	<input type="hidden" id='tags' name='tags' value="<{$query_info['tags']}>"/>
	<input type="hidden" id='category' name='category' value="<{$query_info['category']}>"/>
	<input type="hidden" id='category' name='search' value="<{$query_info['search']}>"/>
	<input type="hidden" id='opt_type' name='opt_type' value="<{$query_info['opt_type']}>"/>
	<input type="hidden" id="search" name="search" value="<{$query_info['search']}>"/>
</form>
<{include 'footer.tpl'}>
<script>
	function js_submit(pagenum)
	{
		$('#page').val(pagenum);
		$('#params_form')[0].submit();
	}
</script>
