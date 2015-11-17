<?php echo validation_errors(); ?>

<?php echo form_open('/recovery', array('role'=>'form', 'style' => 'max-width:400px;')); ?>
<div class="form-group">
	<label for="txt_username">Username or email</label>
	<input type="text" class="form-control" id="txt_username" name='txt_username' placeholder="Username or email" required autofocus/>
</div>
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Password Recovery Request</button>
<div style="margin-top: 10px;">
	<a class="btn btn-lg btn-primary btn-block" href="/">Go Back</a>
</div>
</form>