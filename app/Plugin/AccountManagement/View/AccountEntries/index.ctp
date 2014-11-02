<div class="accountEntries index">
	<h2><?php echo __('Account Entries'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('account_id'); ?></th>
			<th><?php echo $this->Paginator->sort('date'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('value'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($accountEntries as $accountEntry): ?>
	<tr>
		<td><?php echo h($accountEntry['AccountEntry']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($accountEntry['Account']['name'], array('controller' => 'accounts', 'action' => 'view', $accountEntry['Account']['id'])); ?>
		</td>
		<td><?php 
			  $date = new DateTime($accountEntry['AccountEntry']['date']);
			  echo h($date->format('d/m/Y')); ?>&nbsp;</td>
		<td><?php echo h($accountEntry['AccountEntry']['name']); ?>&nbsp;</td>
		<td><?php echo h($accountEntry['AccountEntry']['value']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $accountEntry['AccountEntry']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $accountEntry['AccountEntry']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $accountEntry['AccountEntry']['id']), null, __('Are you sure you want to delete # %s?', $accountEntry['AccountEntry']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Account Entry'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('controller' => 'accounts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('controller' => 'accounts', 'action' => 'add')); ?> </li>
	</ul>
</div>
