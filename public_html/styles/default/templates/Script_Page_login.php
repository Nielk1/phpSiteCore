	<script type="text/javascript">
		if(typeof pageElements == 'undefined') { pageElements = {}; }
		
		$(document).ready(function(){
			if(pageElements.btnLogin == null) pageElements.btnLogin = $('#btnLogin');
			
			pageElements.btnLogin.bind('click', function(e) {
				e.preventDefault() // prevents the form from being submitted
				
				formhash(this.form, this.form.password);
			});
		});
	</script>