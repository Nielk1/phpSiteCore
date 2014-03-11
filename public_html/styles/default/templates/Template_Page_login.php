<?php include('Template_Part_Header.php'); ?>
	<div id="page">
		<?php if($loggedIn): ?>
			<form action="Login/Logout" method="post" name="login_form">
				<button type="submit">Logout</button>
			</form>
		<?php else: ?>
			<form action="Login/Login" method="post" name="login_form">
				Email: <input type="text" name="email" /><br />
				Password: <input type="password" name="password" id="password"/><br />
				<button type="submit">Login</button>
			</form>
		<?php endif; ?>
			<!-- <?php echo $content; ?> -->
	</div>
<?php include('Template_Part_Footer.php'); ?>