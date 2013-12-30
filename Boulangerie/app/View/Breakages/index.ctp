<div class="breakages index">
	<h2><?php echo __('Breakages'); ?></h2>
<ul>
  <li>
	<?php 
	      $shopId = 0;
	      foreach ($breakages as $breakage): 
		  if($shopId != $breakage['Breakage']['shop_id']) {
		      $shopId = $breakage['Breakage']['shop_id'];
		      ?>
		    </li>
		    <li>
		    <h3><?php echo $breakage['Shop']['name'] ?></h3>
		    <table cellpadding="0" cellspacing="0">
		      <tr>
			<th>CA total</th>
			<th>date</th>
			<?php foreach($productTypes as $productType) { ?>
			  <th><?php echo $productType ?></th>
			<?php } ?>
		    </tr>
	      <?php } ?>
	<tr>
		<td><?php echo h($breakage['Breakage']['id']); ?>&nbsp;</td>
		<td><?php echo h($breakage['Breakage']['date']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($breakage['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $breakage['Shop']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($breakage['ProductTypes']['name'], array('controller' => 'product_types', 'action' => 'view', $breakage['ProductTypes']['id'])); ?>
		</td>
		<td><?php echo h($breakage['Breakage']['breakage']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $breakage['Breakage']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $breakage['Breakage']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $breakage['Breakage']['id']), null, __('Are you sure you want to delete # %s?', $breakage['Breakage']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
		</p>
</ul>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Breakage'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Product Types'), array('controller' => 'product_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Product Types'), array('controller' => 'product_types', 'action' => 'add')); ?> </li>
	</ul>
</div>
