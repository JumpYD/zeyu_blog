<!-- Modal -->
<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="margin:300px auto;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><strong>用户登录</strong></h4>
			</div>
			<div class="modal-body">
			暂未提供
			</div>
			<!--
				<div class="input-group" style="margin-top:10px;margin-bottom:10px;">
					<span class="input-group-addon" style="width:10px;">图片ID</span>
					<input type="text" class="form-control" name="insert_id" id="insert_id" placeholder="Picture-ID" style="width:300px;"/>&nbsp;&nbsp;
				</div>
				<div class="input-group" style="margin-top:10px;margin-bottom:10px;">
					<span class="input-group-addon" style="width:10px;">文件名</span>
					<input type="text" class="form-control" name="image_name" id="image_name" placeholder="Picture-Name" style="width:300px;"/>
				</div>
				<div class="input-group" style="margin-top:10px;margin-bottom:10px;">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						Category <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<{foreach item=cat from=$category_list}>
							<{if $cat!='all'}>
							<li><a href="javascript:void(0)" onclick="change_category('<{$cat}>', 'insert_category')"><{$cat}></a></li>
							<{/if}>
							<{/foreach}>
						</ul>
					<input value="article" type="text" class="form-control" id="insert_category" name="insert_category" style="width:200px;" readonly="readonly"/>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="insert_image()">添加或替换</button>
			</div>
			-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
