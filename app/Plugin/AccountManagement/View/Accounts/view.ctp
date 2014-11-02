<div class="accounts view">
<h2><?php echo __('Account'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($account['Account']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($account['Account']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($account['Account']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Account'), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Account'), array('action' => 'delete', $account['Account']['id']), null, __('Are you sure you want to delete # %s?', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Accounts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Account Entries'), array('controller' => 'account_entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account Entry'), array('controller' => 'account_entries', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Account Entries'); ?></h3>
	<?php if (!empty($account['AccountEntry'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Date'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($account['AccountEntry'] as $accountEntry): ?>
		<tr>
			<td><?php echo $accountEntry['id']; ?></td>
			<td><?php 
			  $date = new DateTime($accountEntry['date']);
			  echo h($date->format('d/m/Y')); ?>&nbsp;</td>
			<td><?php echo $accountEntry['name']; ?></td>
			<td><?php echo $accountEntry['value']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'account_entries', 'action' => 'view', $accountEntry['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'account_entries', 'action' => 'edit', $accountEntry['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'account_entries', 'action' => 'delete', $accountEntry['id']), null, __('Are you sure you want to delete # %s?', $accountEntry['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Account Entry'), array('controller' => 'account_entries', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
