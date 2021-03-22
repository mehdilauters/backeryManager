<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset id="demoEmailFieldset" class="alert alert-info">
	  Veuillez saisir ici l'adresse email sur laquelle vous voulez recevoir les emails d'examples
		<legend><?php echo __('Demo Email'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('referer', array('type'=>'hidden', 'value'=>$referer));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

<script>
  introSteps = [
              {
                element: '#demoEmailFieldset',
                intro: "Si vous voulez recevoir les emails qui sont normalement envoyés aux clients, veuillez saisir ici votre adresse email",
		position: 'right'
              },
			];
</script>