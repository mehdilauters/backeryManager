<div class="accounts view">
<h2><?php echo h($account['Account']['name']); ?></h2>
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
		<li><?php echo $this->Html->link(__('Editer'), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Supprimer'), array('action' => 'delete', $account['Account']['id']), null, __('Are you sure you want to delete # %s?', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Liste des comptes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('Nouvelle entrée'), array('controller' => 'account_entries', 'action' => 'add', $account['Account']['id'])); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Entrées'); ?></h3>
	<?php if (!empty($account['AccountEntry'])): ?>
	<table id="account_entries" cellpadding = "0" cellspacing = "0" class="table table-striped" >
	<tr>
		<th><?php echo __('Date'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($account['AccountEntry'] as $accountEntry): ?>
		<tr class="<?php if($accountEntry['value'] <0 ) echo 'negative' ?>" >
			<td><?php 
			  $date = new DateTime($accountEntry['date']);
			  echo h($date->format('d/m/Y')); ?>&nbsp;</td>
			<td><?php echo $accountEntry['name']; ?></td>
			<td class="accountEntryValue" ><?php echo $accountEntry['value']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'account_entries', 'action' => 'view', $accountEntry['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'account_entries', 'action' => 'edit', $accountEntry['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'account_entries', 'action' => 'delete', $accountEntry['id']), null, __('Are you sure you want to delete # %s?', $accountEntry['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<span id="total" ></span>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('Nouvelle entrée'), array('controller' => 'account_entries', 'action' => 'add', $account['Account']['id']),array('id'=>'addEntry')); ?> </li>
		</ul>
	</div>
</div>
<script>

  function totals()
  {
    var total = 0;
    $('.accountEntryValue').each(function(index){
      if( $(this).is(":visible") )
      {
        var val = parseFloat($(this).text());
        if( !isNaN(val))
        {
          total +=  val;
        }
      }
    });
    
    $('#total').html(total);
  }

  $(document).ready(function(){
  
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ totals() }
              };
              tf = new TF('account_entries', tfConfig1); tf.AddGrid();
        
        
  });
        </script>