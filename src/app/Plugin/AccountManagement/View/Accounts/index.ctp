<div class="accounts index">
	<h2><?php echo __('Accounts'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table" >
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
                        <th><?php echo $this->Paginator->sort('company'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('Total'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($accounts as $account):
	
	$signClass='negative';
        if($account['Account']['total'] >=0 )
        {
          $signClass = 'positive'; 
        }
	
	?>
	<tr class="<?php echo $signClass ?>" id="account_<?php echo $account['Account']['id'] ?>" >
		<td><?php echo h($account['Account']['id']); ?>&nbsp;</td>
		<td><?php echo h($account['Account']['created']); ?>&nbsp;</td>
		<td><a href="<?php echo $this->webroot ?>companies/view/<?php echo $account['Company']['id'] ?>" ><?php echo h($account['Company']['name']); ?></a>&nbsp;</td>
		<td><?php echo h($account['Account']['name']); ?>&nbsp;</td>
		<td><?php echo h(round($account['Account']['total'],2)); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Afficher'), array('action' => 'view', $account['Account']['id']), array('class'=>'view','id'=>'view_'.$account['Account']['id'])); ?>
			<?php echo $this->Html->link(__('Editer'), array('action' => 'edit', $account['Account']['id'])); ?>
			<?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $account['Account']['id']), null, __('Are you sure you want to delete # %s?', $account['Account']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('Créer compte'), array('action' => 'add')); ?></li>
	</ul>
</div>
<script>
  introSteps = [
              {
                intro: 'Vous pouvez suivre ici, l\'intégralité de vos comptes, si vous désirez connaître à tout moment votre trésorerie et anticiper vos charges ou achats exceptionnels.'
              },
              {
                element: '#account_<?php echo $accounts[0]['Account']['id'] ?>',
                intro: "Un compte est affiché en vert si le solde total est positif, sinon il est rouge.",
                position: 'top'
              },
              {
                element: '#view_<?php echo $accounts[0]['Account']['id'] ?>',
                intro: "Pour visualiser, ou saisir de nouvelles opérations, c'est ici.",
                position: 'left'
              },
                        ];
</script>
