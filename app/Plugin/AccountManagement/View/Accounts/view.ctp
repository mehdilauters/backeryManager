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
	  <form id="AccountEntriesDateSelect" method="POST" >
            <label>Début</label><input type="text" name="dateStart" id="dateStart" value="<?php echo $dateStart ?>" class="datepicker" />
            <label>Fin</label><input type="text" name="dateEnd" id="dateEnd" value="<?php echo $dateEnd ?>" class="datepicker" />
            <input type="submit" name="dateSelect" id="dateSelect" value="" class="dateSearch" />
          </form>
	<?php if (!empty($account['AccountEntry'])): ?>
	<table id="account_entries" cellpadding = "0" cellspacing = "0" class="table table-striped" >
	<tr>
		<th><?php echo __('Date'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Comment'); ?></th>
		<th><?php echo __('Value'); ?></th>
		<th><?php echo __('Checked'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($account['AccountEntry'] as $accountEntry): ?>
		<tr class="<?php if($accountEntry['value'] <0 ) echo 'negative' ?>" rel="<?php echo $accountEntry['id'] ?>" >
			<td><?php 
			  $date = new DateTime($accountEntry['date']);
			  echo h($date->format('d/m/Y')); ?></td>
			<td><?php echo $accountEntry['name']; ?></td>
			<td><?php echo $accountEntry['comment']; ?></td>
			<td class="accountEntryValue" ><?php echo $accountEntry['value']; ?></td>
			<td class="AccountEntryChecked" ><?php if($accountEntry['checked']) echo 'x'; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('Editer'), array('controller' => 'account_entries', 'action' => 'edit', $accountEntry['id']), array("class"=>"edit")); ?>
				<?php echo $this->Form->postLink(__('Supprimer'), array('controller' => 'account_entries', 'action' => 'delete', $accountEntry['id']), array('class'=>'delete'), __('Are you sure you want to delete # %s?', $accountEntry['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="" rel="" >
           <td class="AccountEntryDate" ><input type="text" class="datepicker" /></td>
           <td class="AccountEntryName" ><input type="text" class=""/>
           <td class="AccountEntryComment" ><input type="text" class=""/>
           <td class="AccountEntryValue" ><input type="text" class="spinner changeValue"/>
           <td class="AccountEntryChecked" ><input type="checkbox" />
           <td class="actions" ><button type="button" class="saveButton" >Valider </button></td>
	</tr>
	<tr>
          <td>Total</td>
          <td></td>
          <td></td>
          <td id="total" ><?php echo $total ?></td>
          <td></td>
          <td></td>
	</tr>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('Nouvelle entrée'), array('controller' => 'account_entries', 'action' => 'add', $account['Account']['id']),array('id'=>'addEntry')); ?> </li>
		</ul>
	</div>
</div>
<script>
  
  function rowDataValid(_data)
  {
    return !(_data.AccountEntry.date == "" || _data.AccountEntry.name == "" || _data.AccountEntry.value == "");
  }
  
  function getRowData(row)
  {
      _data = false;
      var id = -1;
      if(row.attr('rel') != "")
      {
        id = row.attr('rel');
      }
      
      if(row.find('input[type="text"]').length == 0)
      {
        _data = {
        AccountEntry :{
              id: id,
              date: row.find('td:eq(0)').text(),
              name: row.find('td:eq(1)').text(),
              comment: row.find('td:eq(2)').text(),
              value: row.find('td:eq(3)').text(),
              checked: row.find('td:eq(4)').text() == 'x',
          }
        }
      }
      else
      {
        if(row.find('td').length != 0)
        {
          _data = {
            AccountEntry :{
                  id: id,
                  date: row.find('input:eq(0)').val(),
                  name: row.find('input:eq(1)').val(),
                  comment: row.find('input:eq(2)').val(),
                  value: row.find('input:eq(3)').val(),
                  checked: row.find('input:eq(4)').prop('checked')
              }
            }
          }
      }
      return _data;
  }
  
  function save(row)
  {
    
      controller = 'add/<?php echo $account['Account']['id'] ?>';
      var _data = getRowData(row);
      
      if(!rowDataValid(_data))
      {
        return false;
      }
      
      if(_data.AccountEntry.id != -1)
      {
        controller='edit/'+_data.AccountEntry.id;
      }
      else
      {
        delete _data.AccountEntry.id;
      }
          var ret = true;
          jQuery.ajax({
              type: 'POST',
              url: '<?php echo $this->webroot ?>account_management/account_entries/'+controller+'.json',
              async:false,
              accepts: 'application/json',
              data: _data,
              dataType: 'json',
              success: function (data) {
              
                  if(data.results.status)
                  {
                    row.removeClass("alert alert-danger");
                    _data.AccountEntry.id = data.results.id;
                    $('#total').html(data.results.total);
                  }
                  else
                  {
                    row.addClass("alert alert-danger");
                    ret =  false;
                  }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  console.log(textStatus);
                  row.addClass("alert alert-danger");
                  ret =  false;
              }
          });
          
          
          if(ret)
          {
            inputToTd(row);
            row.attr('rel', _data.AccountEntry.id);
          }
          return ret;
  }
  
  function inputToTd(row)
  {
  
    _data = getRowData(row);
    if(!rowDataValid(_data))
    {
      if(_data == false)
      {
        console.log('invalid');
      }
      else
      {
        return false;
      }
    }
    checked='';
    if(_data.AccountEntry.checked)
    {
      checked='x';
    }
    newHtml = '<td class="AccountEntryDate" >'+_data.AccountEntry.date+'</td><td class="AccountEntryName" >'+_data.AccountEntry.name+'</td><td class="AccountEntryComment" >'+_data.AccountEntry.comment+'</td><td class="AccountEntryValue" >'+_data.AccountEntry.value+'</td><td class="AccountEntryChecked" >'+checked+'</td><td class="actions" ><a href="" class="edit" >Editer</a><a class="delete" href="" >Supprimer</a></td>';
    row.html(newHtml);
    updateDom();
  }
  
  function tdToInput(row)
  {
    _data = getRowData(row);
    checked = '';
    if(_data.AccountEntry.checked)
    {
      checked = 'checked="checked"'
    }
    html='<td class="AccountEntryDate" ><input type="text" class="datepicker" value="'+_data.AccountEntry.date+'" /></td><td class="AccountEntryName"><input type="text" value="'+_data.AccountEntry.name+'" class=""/></td><td class="AccountEntryComment"><input type="text" value="'+_data.AccountEntry.comment+'" class=""/></td><td class="AccountEntryValue" ><input value="'+_data.AccountEntry.value+'" type="text" class="spinner changeValue"/></td><td class="AccountEntryChecked"><input value="true" '+checked+' type="checkbox" class=""/><td class="actions" ><button type="button" class="saveButton" >Valider </button></td>';
    row.html(html);
    updateDom();
  }
  
  function updateDom()
  {
    $( ".datepicker" ).datepicker();
    $('#account_entries input').off('keypress').keypress(function(e){;
          if(e.which == 13) {
          row = $(this).closest('tr');
          row.find('button').trigger('click');
      }
      
//       if(e.which == 0) {
//           row = $(this).closest('tr');
//           inputToTd(row);
//       }
    });
    $(".changeValue").off("click").keyup(function(){
          if($(this).val() < 0)
          {
            $(this).closest('tr').addClass("negative");
          }
          else
          {
            $(this).closest('tr').removeClass("negative");
          }
        });
        
        $(".saveButton").off("click").click(function(){
          row = $(this).closest('tr');
          
          ok = save(row);
          if(row.closest('table').find('tr').length -2 == row.index()) // before last line (save)
          {
            if(ok)
            {
              
              newRow = $('<tr rel="">');
              row.after(newRow);
              tdToInput(newRow);
              newRow.find('input[type="text"]').val('');
            }    
          }
          else
          {
            
          }
          
          if(ok)
          {
            inputToTd(row);
          }
        });
        
        $("#account_entries a.edit").off("click").click(function(){
            row = $(this).closest('tr');
            tdToInput(row);
          return false;
          });
          
          $("#account_entries a.delete").attr('onclick','').off("click").click(function(){
            row = $(this).closest('tr');
            
            var ret = true;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo $this->webroot ?>account_management/account_entries/delete/'+row.attr('rel')+'.json',
                async:false,
                accepts: 'application/json',
                dataType: 'json',
                success: function (data) {
                    if(!data.results.status)
                    {
                      row.addClass("alert alert-danger");
                    }
                    $('#total').html(data.results.total);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    row.addClass("alert alert-danger");
                    ret =  false;
                }
            });
          
            
            if(ret)
            {
              row.fadeOut();
//               row.delete();
            }
            return false;
          });
  }

  $(document).ready(function(){
      updateDom();
  
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){  }
              };
              tf = new TF('account_entries', tfConfig1); tf.AddGrid();
        
        
  });
        </script>