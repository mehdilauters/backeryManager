<div class="companies view">
<h2><?php  echo __('Company'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($company['Company']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Media'); ?></dt>
		<dd>
			<?php echo $this->Html->link($company['Media']['name'], array('controller' => 'media', 'action' => 'view', $company['Media']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($company['Company']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Phone'); ?></dt>
		<dd>
			<?php echo h($company['Company']['phone']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Capital'); ?></dt>
		<dd>
			<?php echo h($company['Company']['capital']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Siret'); ?></dt>
		<dd>
			<?php echo h($company['Company']['siret']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($company['Company']['name']); ?>
			&nbsp;
		</dd>
	<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($company['Company']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($company['Company']['email']); ?>
			&nbsp;
		</dd>
		</dd>
		<dt><?php echo __('Mentions legales factures'); ?></dt>
		<dd>
			<?php echo $company['Company']['legals_mentions']; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Imap server'); ?></dt>
                <dd>
                        <?php echo $company['Company']['imap_server']; ?>
                        &nbsp;
                </dd>
                <dt><?php echo __('imap username'); ?></dt>
                <dd>
                        <?php echo $company['Company']['imap_username']; ?>
                        &nbsp;
                </dd>

	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Company'), array('action' => 'edit', $company['Company']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Company'), array('action' => 'delete', $company['Company']['id']), null, __('Are you sure you want to delete # %s?', $company['Company']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Companies'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Company'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Media'), array('controller' => 'media', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('controller' => 'media', 'action' => 'add')); ?> </li>
	</ul>
</div>
