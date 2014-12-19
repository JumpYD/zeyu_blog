<{include 'header.tpl'}>
<br /><br /><br />
<div class="container projects">
	<div class="row">
		<canvas id="flowcount_canvas" width="980px" height="300px" class="span12"></canvas>
	</div>
</div>
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
			data : <{$allcount}>
		},
		{
			fillColor : "rgba(68,114,169,0.4)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			data : <{$allhits}> 
		}
	]
}
var options = 
{
	//Boolean - Whether we show the scale above or below the chart segments
	scaleOverlay : true,

	//Boolean - If we want to override with a hard coded scale
	scaleOverride : false,

	//** Required if scaleOverride is true **
	//Number - The number of steps in a hard coded scale
	scaleSteps : null,
	//Number - The value jump in the hard coded scale
	scaleStepWidth : null,
	//Number - The centre starting value
	scaleStartValue : null,

	//Boolean - Show line for each value in the scale
	scaleShowLine : true,

	//String - The colour of the scale line
	scaleLineColor : "rgba(0,0,0,.1)",

	//Number - The width of the line - in pixels
	scaleLineWidth : 1,

	//Boolean - whether we should show text labels
	scaleShowLabels : true,

	//Interpolated JS string - can access value
	scaleLabel : "<%=value%>",

	//String - Scale label font declaration for the scale label
	scaleFontFamily : "'Arial'",

	//Number - Scale label font size in pixels	
	scaleFontSize : 12,

	//String - Scale label font weight style	
	scaleFontStyle : "normal",

	//String - Scale label font colour	
	scaleFontColor : "#666",

	//Boolean - Show a backdrop to the scale label
	scaleShowLabelBackdrop : true,

	//String - The colour of the label backdrop	
	scaleBackdropColor : "rgba(255,255,255,0.75)",

	//Number - The backdrop padding above & below the label in pixels
	scaleBackdropPaddingY : 2,

	//Number - The backdrop padding to the side of the label in pixels	
	scaleBackdropPaddingX : 2,

	//Boolean - Stroke a line around each segment in the chart
	segmentShowStroke : true,

	//String - The colour of the stroke on each segement.
	segmentStrokeColor : "#fff",

	//Number - The width of the stroke value in pixels	
	segmentStrokeWidth : 2,

	//Boolean - Whether to animate the chart or not
	animation : true,

	//Number - Amount of animation steps
	animationSteps : 100,

	//String - Animation easing effect.
	animationEasing : "easeOutBounce",

	//Boolean - Whether to animate the rotation of the chart
	animateRotate : true,

	//Boolean - Whether to animate scaling the chart from the centre
	animateScale : false,

	//Function - This will fire when the animation of the chart is complete.
	onAnimationComplete : null
}
var ctx = document.getElementById("flowcount_canvas").getContext("2d");
var myNewChart = new Chart(ctx).PolarArea(data);
new Chart(ctx).Line(data,options);
</script>
<{include 'footer.tpl'}>
