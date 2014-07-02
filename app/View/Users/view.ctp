<div class="users view">
<h2>#<?php  echo h($user['User']['id']).' '.h($user['User']['name']); ?></h2>
<div> inscrit depuis le
			<?php 
		      $date = new DateTime($user['User']['created']);
		      echo $date->format('d/m/Y H:i'); ?>
</div>
<div>
<a href="mailto:<?php echo h($user['User']['email']); ?>" ><?php echo h($user['User']['email']); ?></a>, <?php echo $this->MyHtml->getPhoneNumberText($user['User']['phone']); ?>
<br/>
<a href="http://maps.google.com/maps?q=<?php echo urlencode($user['User']['address']); ?>&z=17" target="_blank" ><?php echo $user['User']['address']; ?></a>
<br/>
Afficher le rib sur les factures:  <?php if($user['User']['rib_on_orders'])
		      {
			echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'orderRibCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
		      } ?>
<br/>
Remise: <?php 
		      if($user['User']['discount'] != 0 )
		      {
			echo h($user['User']['discount'].'%'); 
		      }?>

<br/>
mode presentation : <?php if($user['User']['autostart_help'])
		      {
			echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'orderRibCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
		      } ?>

</div>
<!--		<dt><?php echo __('Media'); ?></dt>
		<dd>
			<?php echo $this->Html->link($user['Media']['name'], array('controller' => 'media', 'action' => 'view', $user['Media']['id'])); ?>
			&nbsp;
		</dd>-->
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>