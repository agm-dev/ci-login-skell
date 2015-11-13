<h2>Users</h2>
<?php if($admin != 0): ?>
	<div>
		<input type="button" class="btn btn-default btn-primary" value="New User"/>
	</div>
<?php endif; ?>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Nº</td><td>Username</td>
			</tr>
		</thead>
		<tbody>
			<?php $number=1; ?>
			<?php foreach($users as $user): ?>
				<tr>
					<td><?php echo $number++;?></td>
					<td><?php echo $user['username']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>	
</div>