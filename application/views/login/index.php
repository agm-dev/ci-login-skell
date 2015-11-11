<?php echo validation_errors(); ?>

<?php echo form_open('login/'); ?>
<input type="text" name='txt_username' placeholder="username" />
<input type="password" name='txt_password' />
<input type="submit" name="btn_submit" value="Login" />
</form>