<div class="orders index">
	<h2><?php echo __('Orders'); ?></h2>

<?php echo $this->Form->create('Order'); ?>
	<fieldset id="orderStatusSelect" class="alert alert-info">
		<legend><?php echo __('Filtrer par status'); ?></legend>
		<div>Séléctionnez ici les commandes dont le status vous intéresse</div>
	<?php echo $this->Form->input('statusSelect',array('options'=>array('all'=>'', 'reserved'=>'réservée','available'=>'disponible','waiting'=>'en attente', 'paid'=>'payée', 'emailed'=>'email envoyé'), 'label'=>'Status')); ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
<script>

  $(document).ready(function(){

      var tfConfig = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              };
              tf = new TF('ordersIndexTable', tfConfig); tf.AddGrid();
  });
</script>
	<table id="ordersIndexTable" class="table-striped" cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('shop_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('status'); ?></th>
			<th><?php echo $this->Paginator->sort('delivery_date'); ?></th>
			<?php if($tokens['isAdmin']) : ?>
			  <th><?php echo $this->Paginator->sort('discount'); ?></th>
			  <th><?php echo $this->Paginator->sort('comment'); ?></th>
			<?php endif; ?>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orders as $order): ?>
	<tr>
		<td>#<?php echo h($order['Order']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($order['Shop']['name'], array('controller' => 'shops', 'action' => 'view', $order['Shop']['id'])); ?>
		</td>
		<td><?php 
		      $date = new DateTime ($order['Order']['created']);
		      echo h($date->format('d/m/Y H:i'));
		    ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($order['User']['name'], array('controller' => 'users', 'action' => 'view', $order['User']['id'])); ?>
		</td>
		<td><?php echo h($order['Order']['status']); ?>&nbsp;</td>
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
		
		?>&nbsp;</td>
		<?php if($tokens['isAdmin']) : ?>
		  <td><?php if($order['User']['discount'] != 0 )
		  {
		    echo h($order['User']['discount'].'%'); 
		  }?>&nbsp;</td>
		  <td><?php echo h($order['Order']['comment']); ?>&nbsp;</td>
		<?php endif; ?>
		<td class="actions">
			<?php
			  $emailText = 'Voulez vous vraiment envoyer un email à '.$order['User']['email'].'?';
			if( Configure::read('Settings.demo.active') )
			{
			  $emailText = 'Voulez vous recevoir un email d\\\'exemple?';
			}

 ?>
			<?php echo $this->Html->link($this->Html->image('icons/application-pdf.png', array('id'=>'pdf_'.$order['Order']['id'],'class'=>'icon','alt' => __('imprimer'))), array('action' => 'view', $order['Order']['id'].'.pdf'),  array('escape' => false, 'title'=>'imprimer' )); ?>
			<?php if($tokens['isAdmin']) : ?>
			  <?php echo $this->Html->link($this->Html->image('icons/document-preview.png', array('id'=>'view_'.$order['Order']['id'],'class'=>'icon','alt' => __('voir'))), array('action' => 'view', $order['Order']['id']),  array('escape' => false, 'title'=>'Voir')); ?>
			  <?php echo $this->Html->link($this->Html->image('icons/mail-unread-new.png', array('id'=>'email_'.$order['Order']['id'],'class'=>'icon','alt' => __('Email'), 'onClick="return confirm(\''.$emailText.'\');"')), array('action' => 'email', $order['Order']['id']),  array('escape' => false, 'title'=>'Email')); ?>
			  <?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$order['Order']['id'],'class'=>'icon','alt' => __('Edition'))), array('action' => 'edit', $order['Order']['id']),  array('escape' => false, 'title'=>'editer')); ?>
			  <?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('id'=>'delete_'.$order['Order']['id'],'class'=>'icon','alt' => __('supprimer'))), array('action' => 'delete', $order['Order']['id']) , array('escape' => false, 'title'=>'supprimer'), __('Are you sure you want to delete # %s?', $order['Order']['id'])); ?>
			<?php endif; ?>
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
     <?php if($tokens['isAdmin']) : ?>
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Nouvelle facturation'), array('action' => 'add'), array('id'=>'newOrder')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
     <?php endif; ?>
</div>
<script>
  introSteps = [
              { 
                intro: 'Cette page permet de visualiser les commandes / factures de vos clients.'
              },
              {
                element: '#orderStatusSelect',
                intro: "Vous pouvez lister les commandes par leur statut.",
                position: 'right'
              },
              {
                element: '#ordersIndexTable',
                intro: "Filtrez les resultats en remplissant les filtres.",
                position: 'right'
              },
              {
                element: '#pdf_<?php echo $orders[0]['Order']['id'] ?>',
                intro: "Téléchargez le PDF pour impression,",
                position: 'top'
              },
	      <?php if($tokens['isAdmin']) : ?>
		{
		  element: '#view_<?php echo $orders[0]['Order']['id'] ?>',
		  intro: "Visualisez la,",
                  position: 'top'
		},
		{
		  element: '#edit_<?php echo $orders[0]['Order']['id'] ?>',
		  intro: "Editez la,",
                  position: 'top'
		},
			    {
		  element: '#email_<?php echo $orders[0]['Order']['id'] ?>',
		  intro: "Ou envoyez la directement par email au client concerné.",
				  position: 'top'
		},
			    {
		  element: '#newOrder',
		  intro: "Cliquez maintenant ici pour créer une nouvelle commande.",
				  position: 'top'
		},
	      <?php endif; ?>
            ];
</script>
