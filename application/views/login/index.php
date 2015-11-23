<?php if(isset($login_failed)): ?>
	<div class="row" style="text-align:center;">
		<p>Login has failed.</p>
	</div>
<?php endif; ?>
<?php if(isset($blocked)): ?>
	<div class="row" style="text-align:center;">
		<p>Your user account is now locked. You can unlock it changing your password <a href="/recovery">here</a>.</p>
	</div>
<?php endif; ?>
<?php echo validation_errors(); ?>
<?php echo form_open('login/', array('class'=>'form-signin')); ?>
<img src="/assets/img/logo.png" class="form-signin" style="margin-left:-20px;">
<label for="txt_username" class="sr-only">Username</label>
<input type="text" class="form-control" id="txt_username" name='txt_username' placeholder="Username" required autofocus/>
<label for="txt_password" class="sr-only">Password</label>
<input type="password" class="form-control" id="txt_password" name='txt_password' placeholder="Password" required />
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Login</button>
</form>
<div class="row" style="text-align:center;">
	<p><a href="/register">Not registered yet? </a></p>
</div>
<div class="row" style="text-align:center;">
	<p><a href="/recovery">Forgot your password? </a></p>
</div>
<script type="text/javascript">
  window.onload = function(){
    var body = document.getElementsByTagName('body');
    body[0].setAttribute("class", "frontpageBackground");
  }
</script>	