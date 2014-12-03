<div class="accountEntries view">
<h2><?php echo __('Entrée'); ?></h2>
	<dl>
		<dt><?php echo __('Compte'); ?></dt>
		<dd>
			<?php echo $this->Html->link($accountEntry['Account']['name'], array('controller' => 'accounts', 'action' => 'view', $accountEntry['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nom'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valeur'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['value']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Editer l\'entrée'), array('action' => 'edit', $accountEntry['AccountEntry']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $accountEntry['AccountEntry']['id']), null, __('Are you sure you want to delete # %s?', $accountEntry['AccountEntry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Nouvelle entrée'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('Liste des coomptes'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
	</ul>
</div>
