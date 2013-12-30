<div class="results view">
<h2><?php  echo __('Result'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($result['Result']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shop'); ?></dt>
		<dd>
			<?php echo $this->Html->link($result['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $result['Shop']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($result['Result']['date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cash'); ?></dt>
		<dd>
			<?php echo h($result['Result']['cash']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Check'); ?></dt>
		<dd>
			<?php echo h($result['Result']['check']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Result'), array('action' => 'edit', $result['Result']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Result'), array('action' => 'delete', $result['Result']['id']), null, __('Are you sure you want to delete # %s?', $result['Result']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Results'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Result'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
	</ul>
</div>
