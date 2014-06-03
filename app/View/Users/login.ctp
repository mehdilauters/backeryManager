<div class="users form">

<?php if(Configure::read('demo.active')): ?>
  <div>
    Vous pouvez vous connecter avec le compte utilisateur
	"<?php echo Configure::read('demo.User.email') ?>"
    et le mot de passe "<?php echo Configure::read('demo.User.password') ?>"
  </div>
<?php endif; ?>

<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('remember', array('type'=>'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
