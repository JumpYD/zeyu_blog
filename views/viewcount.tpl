<{include 'header.tpl'}>
<br /><br /><br />
<div class="container projects">
	<div class="row">
			<canvas id="myChart" width="980px" height="300px" class="span12"></canvas>
			<div class='span12'><br /></div>
			<div class='span2'>
				<input type="text" class="form-control" readonly="readonly" value="今日页面浏览量：<{$today_pv}>"/>
			</div>
			<div class='span2'>
				<input type="text" class="form-control" readonly="readonly" value="今日浏览用户数：<{$today_uv}>"/>
			</div>
			<div class='span2'> </div>
			<div class='span2'>
				<input type="text" class="form-control" readonly="readonly" value="总页面浏览量：<{$all_pv}>"/>
			</div>
			<div class='span2'>
				<input type="text" class="form-control" readonly="readonly" value="总浏览用户数：<{$all_uv}>"/>
			</div>
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
			data : <{$pv}>
		},
		{
			fillColor : "rgba(68,114,169,0.4)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			data : <{$uv}> 
		}
	]
}
var ctx = document.getElementById("myChart").getContext("2d");
var myNewChart = new Chart(ctx).PolarArea(data);
new Chart(ctx).Line(data, canvas_options);
</script>
<{include 'footer.tpl'}>
