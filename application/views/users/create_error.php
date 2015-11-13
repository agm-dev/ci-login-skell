<?php if($admin == 1): ?>
	<p>Error!</p>
	<p>The username or email is already in use!</p>
	<a class="btn btn-lg btn-primary" href="/users/create">Go Back</a>
<?php else: ?>
	<p>Error!</p>
	<p>The username or email is already in use!</p>
	<a class="btn btn-lg btn-primary" href="/">Go Back</a>
<?php endif; ?>