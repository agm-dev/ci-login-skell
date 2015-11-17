<?php echo validation_errors(); ?>

<?php echo form_open('/recovery/'.$user['recovery'], array('role'=>'form', 'style' => 'max-width:400px;')); ?>
<div class="form-group">
	<label for="txt_password1">New password</label>
	<input type="password" class="form-control" id="txt_password1" name='txt_password1' placeholder="New password" required autofocus/>
</div>
<div class="form-group">
	<label for="txt_password2">New password (again)</label>
	<input type="password" class="form-control" id="txt_password2" name='txt_password2' placeholder="New password (again)" required/>
</div>
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Update Password</button>
<div style="margin-top: 10px;">
	<a class="btn btn-lg btn-primary btn-block" href="/">Go to login page</a>
</div>
</form>