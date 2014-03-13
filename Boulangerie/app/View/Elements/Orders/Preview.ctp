<div class="Order preview" >
	<table class="table" cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Magasin'); ?></th>
			<th><?php echo __('Client'); ?></th>
			<th><?php echo __('Création'); ?></th>
			<th><?php echo __('Statut'); ?></th>
			<th><?php echo __('Date de production'); ?></th>
			<th><?php echo __('Commentaire'); ?></th>
		</tr>
		<tr>
			<td><?php echo $this->MyHtml->link($order['Shop']['name'], array('controller' => 'shops', 'action' => 'view', 'full_base' => true, $order['Shop']['id'])); ?></td>
			<td><?php echo $this->MyHtml->link($order['User']['name'], array('controller' => 'users', 'action' => 'view', 'full_base' => true, $order['User']['id'])); ?></td>
			<td><?php 
				$date = new DateTime ($order['Order']['created']);
				echo h($date->format('d/m/Y H:i')); ?></td>
			<td><?php echo h($order['Order']['status']); ?></td>
			<td><?php 
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
			
		 ?></td>
			<td><?php echo h($order['Order']['comment']); ?></td>
		</tr>
	</table>
</div>