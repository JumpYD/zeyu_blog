<{include 'src/header.tpl'}>
<script type="text/javascript" src="../resource/datetimepicker-master/js/bootstrap-datetimepicker.js"></script>
<link href="../resource/datetimepicker-master/build/build_standalone.less" rel="stylesheet" type="text/css">
<link href="../resource/datetimepicker-master/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css">
<br/>
<br/>
<br/>
<div class="form-group">
	<label for="dtp_input1" class="col-md-2 control-label">DateTime Picking</label>
	<div class="input-group date form_datetime col-md-5" data-date="1979-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
		<input class="form-control" size="16" type="text" value="" readonly>
		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
	</div>
	<input type="hidden" id="dtp_input1" value="" /><br/>
</div>

<script type="text/javascript">
$(".form_datetime").datetimepicker({
	format: "dd MM yyyy - hh:ii",
	autoclose: true,
	todayBtn: true,
	pickerPosition: "bottom-left"
});
</script>       
<{include 'src/footer.tpl'}>
