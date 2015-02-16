<{include 'header.tpl'}>
<link href="../resource/bootstrap/css/site.css" rel="stylesheet">
<script src="../resource/bootstrap/js/jquery.js"></script>
<script src="../resource/zeyu_blog/js/search.js"></script>

<div id="myCarousel" class="carousel slide">
	<div class="carousel-inner">
		<div class="item active masthead">
			<div class="container" style="margin:50px">
				<div class="carousel-caption">
					<h1 style="margin:0 0 60px 0">龍潭齋</h1>
					<p>
					<form class="navbar-form bs3-link" style="margin:0 0 55px 0" action="javascript:void(0)"; role="search">
						<div class="form-group">
							<input type="text" style="width:400px;height:40px" class="form-control" id="search" placeholder="Search" value="<{$search_text}>">
						</div>&nbsp;&nbsp;
						<button type="button" class="btn btn-default dropdown-toggle" value="content" style="height:40px" data-toggle="dropdown" name="chose_btn" id="chose_btn">
						内容 <span class="caret"></span>
						</button>
						<ul id="search_ul" name="search_ul" style="position:absolute; left:812px; top:230px; text-shadow: none; height:116px;" class="dropdown-menu" role="menu">
							<li><a href="javascript:void(0)" onclick="set_opt_type('title')" id="title">标题</a></li>
							<li><a href="javascript:void(0)" onclick="set_opt_type('content')" id="content">内容</a></li>
							<li><a href="javascript:void(0)" onclick="set_opt_type('all')" id="all">内容（全部）</a></li>
							<li><a href="javascript:void(0)" onclick="set_opt_type('mood')" id="mood">心情</a></li>
						</ul>
						<button type="submit" class="btn btn-default" style="height:40px" onclick="js_commit()">检&nbsp;&nbsp;索</button>
					</form>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container bs-docs-container" style="background-color:rgba(0,0,0,0)">
	<div class="row">
		<div class="span7" style="margin: 75px 0 0 0">
			<div class="alert">
				<div style="scrollbar-face-color: #889b9f;  scrollbar-highlight-color: #c3d6da; overflow: auto;scrollbar-shadow-color: #3d5054; scrollbar-3dlight-color: #3d5054; scrollbar-arrow-color: #ffd6da;scrollbar-darkshadow-color: #85989c; height: 500px">
					<table class="table table-hover" style="background-color:rgba(255, 255, 255, 0)" frame=void border=0 cellpadding=0 cellspacing=0 bordercolor=rgba(0,0,0,0)>
						<tr><td style='color:#fff; font-family:"PT Serif","Georgia","Helvetica Neue",Arial,sans-serif'>TAGS</td><td></td><td></td><td></td><td></td><td></td></tr>
						<{foreach item=info from=$tags name=tag}>
						<{if $smarty.foreach.tag.index % 3 == 0}>
						<tr>
						<{/if}>
							<td>
								<div id="label_icon_<{$info.tag_id}>">
									<span class="label chosen_label" id="<{$info.tag_id}>" onclick="js_label('tag_<{$info.tag_id}>')" style="height:40px">
										<i class="glyphicon glyphicon-bookmark tag_icon" id="icon_tag_<{$info.tag_id}>"></i>
										&nbsp;&nbsp;<{$info.tag_name}>
									</span>
								</div>
							</td>
							<td style="color:#fff"><{$info.article_count}></td>
						<{if $smarty.foreach.tag.index % 3 == 2}>
						</tr>
						<{/if}>
						<{/foreach}>
						<{if $tags_count % 3 != 0}>
						</tr>
						<{/if}>
					</table>
				</div>
			</div>
		</div>
		<div class="span4" style="margin: 75px 0 0 0">
			<div class="alert">
				<div style="scrollbar-face-color: #889b9f;  scrollbar-highlight-color: #c3d6da; overflow: auto;scrollbar-shadow-color: #3d5054; scrollbar-3dlight-color: #3d5054; scrollbar-arrow-color: #ffd6da;scrollbar-darkshadow-color: #85989c; height: 500px">
					<table class="table table-hover" style="background-color:rgba(255, 255, 255, 0)" frame=void border=0 cellpadding=0 cellspacing=0 bordercolor=rgba(0,0,0,0)>
						<tr style='font-family:"PT Serif","Georgia","Helvetica Neue",Arial,sans-serif'><td style="color:#fff">MONTH</td><td style="color:#fff">ARTICLE</td><td style="color:#fff">MOOD</td></tr>
						<{foreach item=info from=$dates}>
						<tr>
							<td>
								<div id="label_icon_<{$info.id}>">
									<span class="label chosen_label" id="<{$info.id}>" onclick="js_label('date_<{$info.id}>')" style="height:40px">
										<i class="glyphicon glyphicon-bookmark date_icon" id="icon_date_<{$info.id}>"></i>
										&nbsp;&nbsp;<{$info.month}>
									</span>
								</div>
							</td>
							<td style="color:#fff"><{$info.article}></td>
							<td style="color:#fff"><{$info.mood}></td>
						</tr>
						<{/foreach}>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<{include 'footer.tpl'}>
