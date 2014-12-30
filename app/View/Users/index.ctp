<div class="users index">
	<h2><?php echo __('Users'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
<!-- 			<th><?php echo $this->Paginator->sort('media_id'); ?></th> -->
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('rib_on_orders'); ?></th>
			<th><?php echo $this->Paginator->sort('discount'); ?></th>
			<th>Role</th>
			<th>Régulier</th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr id="user_<?php echo $user['User']['id'] ?>">
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
<!--		<td>
			<?php echo $this->Html->link($user['Media']['name'], array('controller' => 'media', 'action' => 'view', $user['Media']['id'])); ?>
		</td>-->
		<td><a href="mailto:<?php echo $user['User']['email']; ?>" ><?php echo $user['User']['email']; ?></a>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td id="userRib_<?php echo $user['User']['id'] ?>"><?php
		      if($user['User']['rib_on_orders'])
		      {
			echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'orderRibCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
		      }
		?>
		<td id="userDiscount_<?php echo $user['User']['id'] ?>"><?php
		if($user['User']['discount'] != 0 )
		{
		  echo h($user['User']['discount'].'%');
		}?>
		  &nbsp;</td>
		<td id="userRole_<?php echo $user['User']['id'] ?>"><?php

				if($user['User']['tokens']['isAdmin'])
				{
					echo $this->Html->image('icons/dialog-ok-apply.png', array('id'=>'rootCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
// 					echo $this->Form->postLink($this->Html->image('icons/list-remove-user.png', array('id'=>'rootRemove_'.$user['User']['id'],'class'=>'icon','alt' => __('Retirer'))), array('action' => 'setIsAdmin', $user['User']['id'],false) , array('escape' => false, 'title'=>'Retirer'), __('Voulez vous retirer les droits d\'administrateur à # %s?', $user['User']['id']));
				}
				else
				{
// 					echo $this->Form->postLink($this->Html->image('icons/list-add-user.png', array('id'=>'rootAdd_'.$user['User']['id'],'class'=>'icon','alt' => __('Ajouter'))), array('action' => 'setIsAdmin', $user['User']['id'],true) , array('escape' => false, 'title'=>'Ajouter'), __('Voulez vous que l\'utilisateur # %s soit administrateur?', $user['User']['id']));
				}
			?>
		</td>
		<td id="userRegular_<?php echo $user['User']['id'] ?>">
		<?php
                                if(!$user['User']['regular'])
                                {
                                        echo $this->Html->image('icons/edit-delete.png', array('id'=>'rootCheck_'.$user['User']['id'],'class'=>'icon','alt' => __('oui')));
//                                      echo $this->Form->postLink($this->Html->image('icons/list-remove-user.png', array('id'=>'rootRemove_'.$user['User']['id'],'class'=>'icon','alt' => __('Retirer'))), array('action' => 'setIsAdmin', $user['User']['id'],false) , array('escape' => false, 'title'=>'Retirer'), __('Voulez vous retirer les droits d\'administrateur à # %s?', $user['User']['id']));
                                }
		?>
		</td>
		<td><?php
		$date = new DateTime($user['User']['created']);
		echo $date->format('d/m/Y H:i'); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link($this->Html->image('icons/document-preview.png', array('id'=>'view_'.$user['User']['id'],'class'=>'icon','alt' => __('voir'))), array('action' => 'view', $user['User']['id']),  array('escape' => false, 'title'=>'Voir')); ?>
			<?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$user['User']['id'],'class'=>'icon','alt' => __('Edition'))), array('action' => 'edit', $user['User']['id']),  array('escape' => false, 'title'=>'editer')); ?>
			<?php
			  if($user['User']['id'] != AuthComponent::user('id') )
			  {
			    $rootText = '';
			    if($tokens['isAdmin'])
			    {
			      $rootText = 'Attention, cet utilisateur fait parti des administrateurs ';
			    }
			    echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('id'=>'delete_'.$user['User']['id'],'class'=>'icon','alt' => __('supprimer'))), array('action' => 'delete', $user['User']['id']) , array('escape' => false, 'title'=>'supprimer'), __($rootText.'Are you sure you want to delete # %s?', $user['User']['id']));
			  }
			?>
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
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>
<script>
  introSteps = [
              {
                intro: 'Cette page présente tous les utilisateurs inscrits sur le site, qu\'ils soient clients, ou internes. Vous ne pouvez créer des factures que pour les clients inscrits'
              },
              {
                element: '#user_<?php echo $users[0]['User']['id'] ?>',
                intro: "Pour chaque utilisateur, retrouvez ses différents paramètres",
		position: 'top'
              },
              {
                element: '#userRib_<?php echo $users[0]['User']['id'] ?>',
                intro: "Par exemple, si le rib de l'entreprise sera présent sur ses factures",
		position: 'bottom'
              },
              {
                element: '#userDiscount_<?php echo $users[0]['User']['id'] ?>',
                intro: "ou combien de pourcentage de réduction il à",
		position: 'bottom'
              },
              {
                element: '#userRoot_<?php echo $users[0]['User']['id'] ?>',
                intro: "Mais aussi son rôle sur le site: administrateur? simple client?",
		position: 'bottom'
              },
			];
</script>