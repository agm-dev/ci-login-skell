<?php echo validation_errors(); ?>

<?php echo form_open('login/', array('class'=>'form-signin')); ?>
<label for="txt_username" class="sr-only">Username</label>
<input type="text" class="form-control" id="txt_username" name='txt_username' placeholder="Username" required autofocus/>
<label for="txt_password" class="sr-only">Password</label>
<input type="password" class="form-control" id="txt_password" name='txt_password' placeholder="Password" required />
<button type="submit" class="btn btn-lg btn-primary btn-block" name="btn_submit">Login</button>
</form>