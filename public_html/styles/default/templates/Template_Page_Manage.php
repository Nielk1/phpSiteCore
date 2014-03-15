<?php include('Template_Part_Header.php'); ?>
<div class="panel panel-default">
	<ul class="nav nav-pills">
		<?php if($active_section == 1): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="/Manage/Notifications"><span class="badge pull-right">42</span>Notifications</a></li>
		<?php if($active_section == 2): ?><li class="active"><?php else: ?><li><?php endif; ?><a href="/Manage/Profile">Profile</a></li>
		<li><a href="#">Moderation</a></li>
		<li><a href="#">Admin</a></li>
	</ul>
</div>
<?php echo($content); ?>
<?php include('Template_Part_Footer.php'); ?>