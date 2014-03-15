<?php include('Template_Part_Header.php'); ?>
<div class="panel">
	<div class="panel-body">
		<!--<?php if($loggedIn): ?>
			<form action="Login/Logout" method="post" name="login_form">
				<button type="submit">Logout</button>
			</form>
		<?php else: ?>
			<form action="Login/Login" method="post" name="login_form">
				Email: <input type="text" name="email" /><br />
				Password: <input type="password" name="password" id="password"/><br />
				<button type="submit">Login</button>
			</form>
		<?php endif; ?>-->
		<div class="media">
			<a class="pull-left" href="#">
				<img class="media-object" src="http://placekitten.com/96/96" alt="<?php echo $username; ?>">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $username; ?></h4>
				<p><?php echo $email; ?></p>
				<p><a href="/Profile" class="btn btn-primary" role="button">Public Profile</a></p>
			</div>
		</div>
	</div>
</div>
<?php include('Template_Part_Footer.php'); ?>