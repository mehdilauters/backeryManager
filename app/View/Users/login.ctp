<div class="users form">

<?php if(Configure::read('Settings.demo.active')): ?>
  <div class="alert alert-info">
    Vous pouvez vous connecter avec le compte utilisateur
	"<b><?php echo Configure::read('Settings.demo.User.email') ?></b>"
    et le mot de passe "<b><?php echo Configure::read('Settings.demo.User.password') ?></b>"
  </div>
<?php endif; ?>

<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Identifiants'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('remember', array('type'=>'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Connexion')); ?>
</div>
<script>
      introSteps = [];

	introSteps.push(
              { 
                intro: 'Pour obtenir les informations relatives au fonctionnement de l\'entrepise, ou pour ajouter/editer des produits un <b>identifiant</b> et un <b>mot de passe</b> sont necessaires'
              }
	  );
	  <?php if(Configure::read('Settings.demo.active')) { ?>
	  introSteps.push(
              {
                element: '#UserEmail',
                intro: "Un compte de démonstration est disponible via l'email \"<b><?php echo Configure::read('Settings.demo.User.email') ?></b>\"",
		position: 'top'
              },
              {
                element: '#UserPassword',
                intro: "Et le mot de passe \"<b><?php echo Configure::read('Settings.demo.User.password') ?></b>\"",
		position: 'right'
              }
	    );
	  <?php } else { ?>
	    introSteps.push(
              {
                element: '#UserEmail',
                intro: "Saisissez ici l'email lié a votre compte",
		position: 'top'
              },
              {
                element: '#UserPassword',
                intro: "Et le mot de passe correspondant",
		position: 'right'
              }
	    );
	  <?php }?>
</script>