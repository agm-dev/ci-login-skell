<h2>Update user</h2>
<?php echo ($updated<2) ? ($updated==0) ? '<p>There has been an error on update.</p>' : '<p>User '.$user['username'].' has been updated.</p>' : ''; ?>
<?php echo validation_errors(); ?>
<?php echo form_open("/users/update/".$user['username'], array('role'=>'form', 'style' => 'max-width:400px;')); ?>
<div class="form-group">
	<label for="txt_username">Username</label>
	<input type="text" class="form-control" id="txt_username" name='txt_username' placeholder="Username" value="<?php echo $user['username']; ?>" required autofocus/>
</div>
<div class="form-group">
	<label for="txt_password1">Password</label>
	<input type="password" class="form-control" id="txt_password1" name='txt_password1' placeholder="Password"/>
</div>
<div class="form-group">
	<label for="txt_password2">Repeat Password</label>
	<input type="password" class="form-control" id="txt_password2" name='txt_password2' placeholder="Repeat Password"/>
</div>
<div class="form-group">
	<label for="txt_email1">Email</label>
	<input type="email" class="form-control" id="txt_email1" name='txt_email1' placeholder="Email" value="<?php echo $user['email']; ?>" required />
</div>
<div class="form-group">
	<label for="txt_email2">Repeat Email</label>
	<input type="email" class="form-control" id="txt_email2" name='txt_email2' placeholder="Repeat Email" value="<?php echo $user['email']; ?>" required />
</div>
<div class="form-group">
	<label for="chk_activated">Activated</label>
	<input type="checkbox" value="1" id="chk_activated" name="chk_activated" <?php echo  ($user['activated']==1) ? "checked='checked'": ''; ?>/>
</div>
<div class="form-group">
	<label for="chk_admin">Admin privileges</label>
	<input type="checkbox" value="1" id="chk_admin" name="chk_admin" <?php echo ($user['admin']==1) ? "checked='checked'": ''; ?> />
</div>
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Update User</button>
<div style="margin-top: 10px;">
	<a class="btn btn-lg btn-primary btn-block" href="/users/delete/<?php echo $user['username']; ?>">Delete User</a>
</div>
<div style="margin-top: 10px;">
	<a class="btn btn-lg btn-primary btn-block" href="/users">Go Back</a>
</div>
</form>