<?php echo validation_errors(); ?>

<?php echo form_open('/users/create', array('role'=>'form', 'style' => 'max-width:400px;')); ?>
<div class="form-group">
	<label for="txt_username">Username</label>
	<input type="text" class="form-control" id="txt_username" name='txt_username' placeholder="Username" required autofocus/>
</div>
<div class="form-group">
	<label for="txt_password1">Password</label>
	<input type="password" class="form-control" id="txt_password1" name='txt_password1' placeholder="Password" required />
</div>
<div class="form-group">
	<label for="txt_password2">Repeat Password</label>
	<input type="password" class="form-control" id="txt_password2" name='txt_password2' placeholder="Repeat Password" required />
</div>
<div class="form-group">
	<label for="txt_email1">Email</label>
	<input type="email" class="form-control" id="txt_email1" name='txt_email1' placeholder="Email" required />
</div>
<div class="form-group">
	<label for="txt_email2">Repeat Email</label>
	<input type="email" class="form-control" id="txt_email2" name='txt_email2' placeholder="Repeat Email" required />
</div>
<?php if($admin == 1): ?>
	<div class="form-group">
		<label for="chk_activated">Activated</label>
		<input type="checkbox" value="1" id="chk_activated" name="chk_activated"/>
	</div>
	<div class="form-group">
		<label for="chk_email">Send activation email</label>
		<input type="checkbox" value="1" id="chk_email" name="chk_email"/>
	</div>
	<div class="form-group">
		<label for="chk_admin">Admin privileges</label>
		<input type="checkbox" value="1" id="chk_admin" name="chk_admin"/>
	</div>
<?php endif; ?>
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Create User</button>
<div style="margin-top: 10px;">
	<a class="btn btn-lg btn-primary btn-block" href="/users">Go Back</a>
</div>
</form>
<?php if($admin == 1): ?>
	<script type="text/javascript">
		window.onload = function()
		{
			document.getElementById("chk_activated").onclick = function()
			{
				if(document.getElementById("chk_activated").checked)
				{
					if(document.getElementById("chk_email").checked)
					{
						document.getElementById("chk_email").checked = false;						
					}
					document.getElementById("chk_email").disabled = true;
				}
				else
				{
					document.getElementById("chk_email").disabled = false;
				}
			}
		}
	</script>
<?php endif; ?>