<!-- Modal -->
<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin:300px auto;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><strong>用户登录</strong></h4>
			</div>
			<div class="modal-body">
				<div class="input-group" style="margin:10px 10px 20px 100px">
					<span class="input-group-addon" style="width:10px;">用户名</span>
					<input type="text" class="form-control" name="username" id="username" style="width:300px;"/>&nbsp;&nbsp;
				</div>
				<div class="input-group" style="margin:0px 10px 0px 100px">
					<span class="input-group-addon" style="width:10px;">密&nbsp;&nbsp;&nbsp;码</span>
					<input type="password" class="form-control" name="password" id="password" style="width:300px;"/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="login()">登录</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
