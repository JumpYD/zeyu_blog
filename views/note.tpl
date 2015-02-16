<{include 'header.tpl'}>
<script language="javascript">
function get_earnings()
{
	var det_beg_month = $('#beg_month').val();
	var det_end_month = $('#end_month').val();
	var drw_beg_month = $('#drw_beg_month').val();
	var drw_end_month = $('#drw_end_month').val();
	window.location.href = 'earnings.php?beg='+det_beg_month+'&'+'end='+det_end_month+'&drw_beg='+drw_beg_month+'&drw_end='+drw_end_month;
}
function drw_earnings()
{
	var det_beg_month = $('#det_beg_month').val();
	var det_end_month = $('#det_end_month').val();
	var drw_beg_month = $('#beg_month').val();
	var drw_end_month = $('#end_month').val();
	window.location.href = 'earnings.php?beg='+det_beg_month+'&'+'end='+det_end_month+'&drw_beg='+drw_beg_month+'&drw_end='+drw_end_month;
}
</script>
<br /><br /><br />
<input type="hidden" id="det_beg_month" class="form-control" value="<{$det_beg_month}>"/>
<input type="hidden" id="det_end_month" class="form-control" value="<{$det_end_month}>"/>
<input type="hidden" id="drw_beg_month" class="form-control" value="<{$drw_beg_month}>"/>
<input type="hidden" id="drw_end_month" class="form-control" value="<{$drw_end_month}>"/>
<div class="container projects">
	<div class="row">
		<{if $category_id==1}>
			<canvas id="myChart" width="980px" height="300px" class="span12"></canvas>
			<div class='span12'><br /></div>
			<div class='span2'>
				<input type="text" id="beg_month" class="form-control" placeholder="BEG_MONTH" value="<{$beg_month}>"/>
			</div>
			<div class='span2'>
				<input type="text" id="end_month" class="form-control" placeholder="END_MONTH" value="<{$end_month}>"/>
			</div>
			<div class='span2'>
				<button type="submit" onclick="get_earnings()" class="btn btn-default">详&nbsp;&nbsp;情</button>&nbsp;&nbsp;
				<button type="submit" onclick="drw_earnings()" class="btn btn-default">绘&nbsp;&nbsp;图</button>
			</div>
			<div class='span2'>
			</div>
			<div class='span2'>
				<input type="text" class="form-control" placeholder="Text input" readonly='readonly' value='平均月结余：<{$average}>'\>
			</div>
			<div class='span12'>
				<br /><br />
			</div>
		<{/if}>
		<{foreach item=info from=$infos}>
		<div class="col-sm-6 col-md-4 ">
			<div class="thumbnail">
				<a href="<{$info.idx_href}>" target="_blank"><img class="lazy" src="<{$info.image_path}>" <{if $category_id!=1}>style="height:396px;"<{/if}> data-src="<{$info.image_path}>"></a>
				<div class="caption">
					<h3> 
						<a href="<{$info.idx_href}>"><{$info.title}><br><small><{$info.descs}></small></a>
					</h3>
				</div>
			</div>
		</div>
		<{/foreach}>
	</div>
</div>
<script src="../resource/Chart.js-master/Chart.min.js"></script>
<script src="../resource/zeyu_blog/js/chart.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" language="javascript">
var data =
{
	labels : <{$labels}>,
	datasets : 
	[
		{
			fillColor : "rgba(220,220,220,0.8)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			data : <{$income}>
		},
		{
			fillColor : "rgba(68,114,169,0.4)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			data : <{$expend}> 
		}
	]
}
var ctx = document.getElementById("myChart").getContext("2d");
var myNewChart = new Chart(ctx).PolarArea(data);
new Chart(ctx).Line(data, canvas_options);
</script>
<{include 'footer.tpl'}>
