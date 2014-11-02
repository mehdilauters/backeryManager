<div class="accountEntries view">
<h2><?php echo __('Account Entry'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Account'); ?></dt>
		<dd>
			<?php echo $this->Html->link($accountEntry['Account']['name'], array('controller' => 'accounts', 'action' => 'view', $accountEntry['Account']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($accountEntry['AccountEntry']['value']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account Entry'), array('action' => 'edit', $accountEntry['AccountEntry']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Account Entry'), array('action' => 'delete', $accountEntry['AccountEntry']['id']), null, __('Are you sure you want to delete # %s?', $accountEntry['AccountEntry']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Entries'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Entry'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>
