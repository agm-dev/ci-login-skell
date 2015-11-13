<?php if($admin == 1): ?>
	<p>The new user has been created.</p>
	<a class="btn btn-lg btn-primary" href="/users/create">Go Back</a>
<?php else: ?>
	<p>Your user account has been created.</p>
	<p>You will recibe an email in the next minutes with an acctivation link. As soon as you activate your account, you will be able to log in.</p>
	<a class="btn btn-lg btn-primary" href="/">Go Back</a>
<?php endif; ?>