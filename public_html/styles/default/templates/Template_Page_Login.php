<?php include('Template_Part_Header.php'); ?>
<div class="container">
	<div class="panel">
		<div class="panel-body">
			<?php if($loggedIn): ?>
				<form action="Login/Logout" method="post" name="login_form">
					<button type="submit">Logout</button>
				</form>
			<?php else: ?>
				<div class="row">
					<div class="col-sm-6">
						<form role="form" action="Login/Login" method="post" name="login_form">
							<div class="form-group">
								<label for="txtEmail">Email Address</label>
								<input type="email" name="email" class="form-control" id="txtEmail" placeholder="Enter email">
							</div>
							<div class="form-group">
								<label for="txtPassword">Password</label>
								<input type="password" name="password" class="form-control" id="txtPassword" placeholder="Password">
							</div>
							<button type="submit" class="btn btn-default">Login</button>
						</form>
						<hr />
						<form role="form" action="Login/Register" method="post" name="register_form">
							<div class="form-group">
								<label for="txtEmail">Email Address</label>
								<input type="email" name="email" class="form-control" id="txtEmail" placeholder="Enter email">
							</div>
							<button type="submit" class="btn btn-default">Register</button>
						</form>
						<hr class="visible-xs" />
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php include('Template_Part_Footer.php'); ?>
