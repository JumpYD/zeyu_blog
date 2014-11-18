<{include 'header.tpl'}>
<link href="../resource/bootstrap/css/site.css" rel="stylesheet">
<script src="../resource/bootstrap/js/jquery.js"></script>
<script language="javascript">
function js_label(label_id)
{
	var item = $('#icon_'+label_id);
	if (item.hasClass('glyphicon-bookmark'))
	{
		item.removeClass('glyphicon-bookmark');
		item.addClass('glyphicon-ok');
	}
	else
	{
		item.removeClass('glyphicon-ok');
		item.addClass('glyphicon-bookmark');
	}
}

function set_opt_type(opt_type)
{
	$("#chose_btn").html($('#'+opt_type).html() + ' <span class="caret"></span>');
	$("#chose_btn").val($('#'+opt_type).attr("id"));
}

function js_commit()
{
	var form = document.createElement("form");
	form.setAttribute("method", 'post');
	form.setAttribute("action", 'debin.php');

	hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", 'category');

	if ($("#chose_btn").val() != 'mood')
		hiddenField.setAttribute("value", '0');
	else
		hiddenField.setAttribute("value", 'mood');

	form.appendChild(hiddenField);

	hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", 'opt_type');
	hiddenField.setAttribute("value", $("#chose_btn").val());
	form.appendChild(hiddenField);

	hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", 'search');
	hiddenField.setAttribute("value", $('#search').val());
	form.appendChild(hiddenField);

	var tags = Array();

	var items = $('.tag_icon');
	for (var i=0; i<items.length; ++i)
	{
		var item = items.eq(i);
		if (item.hasClass('glyphicon-ok'))
		{
			tags.push(item.attr('id'));
		}
	}
	
	var items = $('.date_icon');
	for (var i=0; i<items.length; ++i)
	{
		var item = items.eq(i);
		if (item.hasClass('glyphicon-ok'))
		{
			tags.push(item.attr('id'));
		}
	}

	hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", 'tags');
	hiddenField.setAttribute("value", tags);
	form.appendChild(hiddenField);

	document.body.appendChild(form);
	form.submit();
}
</script>
<style>
.chosen_label
{
	cursor: pointer;
}
</style>

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
						<ul style="position:absolute; left:812px; top:230px; text-shadow: none; height:116px;" class="dropdown-menu" role="menu">
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
						<tr><td style='font-family:"PT Serif","Georgia","Helvetica Neue",Arial,sans-serif'>TAGS</td><td></td><td></td><td></td><td></td><td></td></tr>
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
							<td><{$info.article_count}></td>
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
						<tr style='font-family:"PT Serif","Georgia","Helvetica Neue",Arial,sans-serif'><td>MONTH</td><td>ARTICLE</td><td>MOOD</td></tr>
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
							<td><{$info.article}></td>
							<td><{$info.mood}></td>
						</tr>
						<{/foreach}>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<{include 'footer.tpl'}>
