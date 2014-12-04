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
		<tr class="<?php if($accountEntry['value'] <0 ) echo 'negative' ?>" rel="<?php echo $accountEntry['id'] ?>" >
			<td><?php 
			  $date = new DateTime($accountEntry['date']);
			  echo h($date->format('d/m/Y')); ?></td>
			<td><?php echo $accountEntry['name']; ?></td>
			<td class="accountEntryValue" ><?php echo $accountEntry['value']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'account_entries', 'action' => 'edit', $accountEntry['id']), array("class"=>"edit")); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'account_entries', 'action' => 'delete', $accountEntry['id']), array('class'=>'delete'), __('Are you sure you want to delete # %s?', $accountEntry['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<tr class="" rel="" >
           <td><input type="text" class="datepicker" /></td>
           <td><input type="text" class=""/>
           <td><input type="text" class="spinner changeValue"/>
           <td><button type="button" class="saveButton" >Valider </button></td>
	</tr>
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
    $('#account_entries tr:not(.fltrow)').each(function(index){
        e = $(this).find('td:eq(2)');
      if( e.is(":visible") )
      {
        var val = parseFloat(e.text());
        if( !isNaN(val))
        {
          total +=  val;
        }
      }
    });
    
    $('#total').html(total);
  }
  
  function save(row)
  {
    //           vals
          dte = row.find('input:eq(0)').val();
          name = row.find('input:eq(1)').val();
          value = row.find('input:eq(2)').val();
          
          if(dte == "" || name == "" || value == "")
          {
            return false;
          }
          
          _data = {
            AccountEntry :{
                  date: dte,
                  name: name,
                  value: value
              }
            }
            controller = 'add/<?php echo $account['Account']['id'] ?>';
            var id = -1;
              if(row.attr('rel') != "")
              {
                _data.AccountEntry.id = row.attr('rel');
                id = row.attr('rel');
                controller='edit/'+id;
              }
          var ret = true;
          jQuery.ajax({
              type: 'POST',
              url: '<?php echo $this->webroot ?>account_management/account_entries/'+controller,
              async:false,
              accepts: 'application/json',
              data: _data,
              dataType: 'json',
              success: function (data) {
                  row.removeClass("alert alert-danger");
                  if(id==-1)
                  {
                    id = data.id;
                  }
                  else
                  {
                    if(!data.status)
                    {
                      row.addClass("alert alert-danger");
                    }
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
            newHtml = '<td>'+dte+'</td><td>'+name+'</td><td>'+value+'</td><td class="actions" ><a href="" class="edit" >Editer</a><a class="delete" href="" >Supprimer</a></td>';
            row.html(newHtml);
            row.attr('rel', id);
          }
          return ret;
  }
  
  function updateDom()
  {
    $( ".datepicker" ).datepicker();
    totals();
    $('#account_entries input').off('keypress').keypress(function(e){
          if(e.which == 13) {
          row = $(this).closest('tr');
          row.find('button').trigger('click');
      }
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
        
        $(".changeButton").off("click").click(function(){
          row = $(this).closest('tr');
          save(row);
          updateDom();
        });
        
        
        $(".saveButton").off("click").click(function(){
          row = $(this).closest('tr');
          html='<tr rel="" ><td><input type="text" class="datepicker" value="" /></td><td><input type="text" value="" class=""/><td><input value="" type="text" class="spinner changeValue"/><td><button type="button" class="saveButton" >Valider </button></td></tr>';
          if(save(row))
          {
            row.closest('table').append(html);
          }
          updateDom();
        });
        
        $("#account_entries a.edit").off("click").click(function(){
            row = $(this).closest('tr');
            dte = row.find('td:eq(0)').text();
            name = row.find('td:eq(1)').text();
            value = row.find('td:eq(2)').text();
            
            html='<td><input type="text" class="datepicker" value="'+dte+'" /></td><td><input type="text" value="'+name+'" class=""/><td><input value="'+value+'" type="text" class="spinner changeValue"/><td><button type="button" class="changeButton" >Valider </button></td>';
            row.html(html);
            updateDom();
          return false;
          });
          
          $("#account_entries a.delete").attr('onclick','').off("click").click(function(){
            row = $(this).closest('tr');
            
            var ret = true;
            jQuery.ajax({
                type: 'POST',
                url: '<?php echo $this->webroot ?>account_management/account_entries/delete/'+row.attr('rel'),
                async:false,
                accepts: 'application/json',
                dataType: 'json',
                success: function (data) {
                    if(!data.status)
                    {
                      row.addClass("alert alert-danger");
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
              row.fadeOut();
              updateDom();
            }
            return false;
          });
  }

  $(document).ready(function(){
      updateDom();
  
      var tfConfig1 = {
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              on_after_refresh_counter: function(o,i){ totals() }
              };
              tf = new TF('account_entries', tfConfig1); tf.AddGrid();
        
        
  });
        </script>