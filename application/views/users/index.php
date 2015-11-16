<h2>Users</h2>
<?php if($admin != 0): ?>
	<div>
		<a class="btn btn-default btn-primary" href="/users/create">New User</a>
	</div>
<?php endif; ?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<td><strong>Nº</strong></td>
				<td><strong>Username</strong></td>
				<td><strong>Activated</strong></td>
				<td><strong>Admin</strong></td>
			</tr>
		</thead>
		<tbody>
			<?php $number=1; ?>
			<?php foreach($users as $user): ?>
				<tr class="clickable" onclick="location='/users/update/<?php echo $user['username']; ?>';">
					<td><?php echo $number++;?></td>
					<td><?php echo $user['username']; ?></td>
					<td><i class="fa fa-circle" style="color:<?php echo ($user['activated']==1)? '#5CB85C' : '#D9534F'; ?>;"></i></td>
					<td><i class="fa fa-circle" style="color:<?php echo ($user['admin']==1)? '#5CB85C' : '#D9534F'; ?>;"></i></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	
</div>