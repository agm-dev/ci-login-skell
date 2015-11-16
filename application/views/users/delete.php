<h2>Delete user</h2>

<?php echo validation_errors(); ?>

<p>You are about deleting user <?php echo $user['username']; ?>.</p>

<?php echo form_open('/users/delete/'. $user['username'], array('role'=>'form', 'style' => 'max-width:400px;')); ?>  
  <div class="form-group">
        <div class="checkbox">
          <label>
            <input  type="checkbox"            
              id="chk_delete" 
              name='chk_delete' 
              value="1" /> Confirm.
          </label>
        </div>
  </div>  
  <button type="submit" class="btn btn-default btn-lg btn-block btn-primary" name='btn_submit' id='btn_submit'>Delete user</button>
  <div style="margin-top: 10px;">
    <a class="btn btn-lg btn-primary btn-block" href="/users">Go Back</a>
  </div>
</form>