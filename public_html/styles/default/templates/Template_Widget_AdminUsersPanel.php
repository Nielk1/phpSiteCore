<div id="AdminUsersPanel">
	<div id="AdminUsersPanel_Tools">
		<a href="users/add">Add new user</a>
	</div>
	<table class="table1">
		<thead>
			<tr>
				<th>ID</th>
				<th>User Name</th>
				<th>Email</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($userdata as $userdatarow): ?>
				<tr>
					<td><?php echo $userdatarow[0]; ?></td>
					<td><?php echo $userdatarow[1]; ?></td>
					<td><?php echo $userdatarow[2]; ?></td>
					<td>[Edit][Reset Password][Other Quick Access Buttons]</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>