<div class="container container-fluid">
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-tag"></span> Display Name</h3>
				</div>
				<div class="panel-body">
					<form>
						<div class="radio">
							<label>
								<input type="radio" name="rdDisplayName" id="rdDisplayName_Base" value="base" checked>
									<?php echo($username); ?>
								</input>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><span class="glyphicon glyphicon-cog"></span> Authentication</h3>
				</div>
				<table class="table">
					<thead><tr><th>Type</th><th colspan=2 style="white-space:nowrap; text-align:right;">Can Login</th></tr></thead>
					<tbody>
						<tr>
							<td>Password</td><td><a href="#">Edit</a></td><td></td></tr>
							<td>Facebook</td><td><a href="#">Edit</a></td><td><div class="checkbox-inline pull-right"><label><input type="checkbox"></label></div></td></tr>
						</tr>
					</tbody>
				</table>
				<div class="panel-footer">
					<a class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span> Add New</a>
				</div>
			</div>
		</div>
	</div>
</div>