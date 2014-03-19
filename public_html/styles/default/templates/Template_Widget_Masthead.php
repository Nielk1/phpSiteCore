		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		
			<div class="container">
				<div class="navbar-header pull-left">
					<a class="navbar-brand site-title" href="/">Project name</a>
				</div>

				<!-- I don't want it apart of the collapsible portion -->
				<div class="navbar-header pull-right">
						<div id="btnProfileToggleWrapper" class="profile_button_wrapper">
								<?php if($loggedIn): ?>
									<a id="btnProfileToggle" href="/Login" role="button" data-container="#btnProfileToggleWrapper" data-toggle="popover" data-placement="bottom">
										<div class="glyphicon glyphicon-user profile_button">
											<span class="sr-only">Toggle profile</span>
										</div>
										<span class="badge pull-right profile_button_badge">42</span>
									</a>
								<?php else: ?>
									<a id="btnLogin" class="btn btn-sm btn-default login_button" href="/Login" role="button"><span class="glyphicon glyphicon-log-in"></span> Login</a>
								<?php endif; ?>
						</div>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="collapse navbar-collapse navbar-left">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>
				</div>
				<!--<p class="navbar-text navbar-right"><a href="#" class="navbar-link glyphicon glyphicon-user"><?php if($loggedIn): ?><?php echo $username; ?><?php else: ?>Login<?php endif; ?></a></p>-->
			</div>
		</div>
<div id="MastheadProfile" style="display:none;">
	<div class="panel-body profile_panel">
		<div class="media">
			<a class="pull-left" href="#">
				<img class="media-object" src="http://placekitten.com/96/96" alt="<?php echo $username; ?>">
			</a>
			<div class="media-body">
				<h4 class="media-heading"><?php echo $username; ?></h4>
				<p><?php echo $email; ?></p>
				<p><a href="/Manage" class="btn btn-default" role="button">Manage</a></p>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<a href="/Profile" class="btn btn-primary" role="button">Public Profile</a>
		<form action="/Login/Logout" method="post" name="login_form" class="pull-right">
			<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-log-out"></span> Logout</button>
		</form>
	</div>
</div>