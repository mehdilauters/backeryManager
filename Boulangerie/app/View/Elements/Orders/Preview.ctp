<div>
<dl>
		<dt><?php echo __('Magasin'); ?></dt>
		<dd>
			<?php echo $this->Html->link($order['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $order['Shop']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php 
			$date = new DateTime ($order['Order']['created']);
			echo h($date->format('d/m/Y H:i')); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($order['Order']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Date de production'); ?></dt>
		<dd>
			<?php 
			$date = new DateTime ($order['Order']['delivery_date']);
			if($date != false)
			{
				if($date->format('H:i') == '00:00')
				{
					echo h($date->format('d/m/Y'));
				}
				else
				{
					echo h($date->format('d/m/Y H:i'));
				}
			}
			
		 ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($order['User']['name'], array('controller' => 'users', 'action' => 'view', $order['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comment'); ?></dt>
		<dd>
			<?php echo h($order['Order']['comment']); ?>
			&nbsp;
		</dd>
	</dl>

</div>