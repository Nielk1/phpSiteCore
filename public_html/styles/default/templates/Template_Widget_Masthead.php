		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Project name</a>
				</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>
					<div class="navbar-form form-inline pull-right">
						<?php if($loggedIn): ?>
							<a id="logout" style="display:inline-block;" href="/login">Logout [<?php echo $username; ?>]</a>
							<a id="login" style="display:none;" href="/login">Login</a>
						<?php else: ?>
							<a id="logout" style="display:none;" href="/login">Logout</a>
							<a id="login" style="display:inline-block;" href="/login">Login</a>
						<?php endif; ?>
					</div>
				</div><!--/.nav-collapse -->
			</div>
		</div>