<div class="breakages view">
<h2><?php  echo __('Breakage'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($breakage['Breakage']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date'); ?></dt>
		<dd>
			<?php echo h($breakage['Breakage']['date']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Shop'); ?></dt>
		<dd>
			<?php echo $this->Html->link($breakage['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $breakage['Shop']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Product Types'); ?></dt>
		<dd>
			<?php echo $this->Html->link($breakage['ProductTypes']['name'], array('controller' => 'product_types', 'action' => 'view', $breakage['ProductTypes']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Breakage'); ?></dt>
		<dd>
			<?php echo h($breakage['Breakage']['breakage']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Breakage'), array('action' => 'edit', $breakage['Breakage']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Breakage'), array('action' => 'delete', $breakage['Breakage']['id']), null, __('Are you sure you want to delete # %s?', $breakage['Breakage']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Breakages'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Breakage'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
