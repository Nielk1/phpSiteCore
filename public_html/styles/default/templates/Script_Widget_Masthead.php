		<script type="text/javascript">
			if(typeof pageElements == 'undefined') { pageElements = {}; }
			if(typeof loggedIn == 'undefined') { <?php if($loggedIn): ?>loggedIn = true;<?php else: ?>loggedIn = false;<?php endif; ?> }
			if(typeof username == 'undefined') { <?php if($username != null): ?>username = '<?php echo $username; ?>';<?php else: ?>username = null;<? endif; ?> }
			
			$(document).on('sessionStateChange',function(){
				if(loggedIn)
				{
					pageElements.loginLink.hide();
					pageElements.logoutLink.text('Logout [' + username + ']').show();
				}else{
					pageElements.loginLink.show();
					pageElements.logoutLink.hide();
				}
			});
			
			/*$(document).ready(function(){
				pageElements.loginLink = $('#login');
				pageElements.logoutLink = $('#logout');
				pageElements.loginSection = $('#login_box');
				pageElements.loginForm = $('#login_form');
				
				$('body').click(function(event) {
					var $target = $(event.target);

					if ($target.parents(pageElements.loginSection).length == 0) {
						pageElements.loginSection.hide();
					}
				});
				
				pageElements.loginLink.click(function(event){
					event.preventDefault();
					pageElements.loginSection.toggle();
				});
				
				pageElements.logoutLink.click(function(event){
					event.preventDefault();
					$.ajax
					({
						type: "POST",
						url: "/login/logout/ajax",
						dataType: "json",
						success: function()
						{
							loggedIn = false;
							$(document).trigger('sessionStateChange');
						}
					});
				});
				
				pageElements.loginForm.on('click','input:button',function(event){
					event.preventDefault();
									
					if(this.form.email.value.length > 0 && this.form.password.value.length > 0)
					{
						var user = this.form.email.value;
						var pass = hex_sha512(this.form.password.value);
						this.form.password.value = '';
						
						$.ajax
						({
							type: "POST",
							url: "/login/login/ajax",
							dataType: "json",
							contentType: "application/x-www-form-urlencoded",
							data: {email: user, p: pass},
							success: function(data)
							{
								if(data.status == 'success')
								{
									pageElements.loginSection.hide();
									loggedIn = true;
									username = data.username;
									$(document).trigger('sessionStateChange');
								}
							}
						});
					}
				});
			});*/
		</script>