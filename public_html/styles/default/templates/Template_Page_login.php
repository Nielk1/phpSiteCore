<?php include('Template_Part_Header.php'); ?>
	<div id="page">
		<?php if($loggedIn): ?>
			<form action="login/logout" method="post" name="login_form">
				<input type="button" value="Logout" />
			</form>
		<?php else: ?>
			<form action="login/login" method="post" name="login_form">
				Email: <input type="text" name="email" /><br />
				Password: <input type="password" name="password" id="password"/><br />
				<input id="btnLogin" type="button" value="Login"/>
			</form>
		<?php endif; ?>
			<!-- <?php echo $content; ?> -->
	</div>
<?php include('Template_Part_Footer.php'); ?>