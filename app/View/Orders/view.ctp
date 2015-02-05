<div class="orders view">
<table class="header table" >
  <tr>
    <td>
      <?php echo $this->element('Companies/Preview', array('company'=>$company)); ?>
    </td>
<td></td>
    <td>
      <?php echo $this->element('Users/Preview', array('user'=>$order)); ?>
	  Commande #<?php echo $order['Order']['id'] ?>
    </td>
</table>
<center><h2><?php echo __('Commande'); ?> du <?php
$date = new DateTime($order['Order']['delivery_date']);
echo $date->format('d/m/Y'); ?></h2></center>
	<?php if($order['Order']['discount'] != 0 ): ?>
		<p>Remise de <?php echo $order['Order']['discount'] ?>%</p>
	<?php endif; ?>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
			<?php
			  $emailText = 'Voulez vous vraiment envoyer un email à '.$order['User']['email'].'?';
			if( Configure::read('Settings.demo.active') )
			{
			  $emailText = 'Voulez vous recevoir un email d\\\'exemple?';
			}

 ?>
	<ul>
		<li class="edit" ><?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('alt' => '')).' '.__('Modifier infos commande'), array('action' => 'edit', $order['Order']['id']),array('escape' => false)); ?> </li>
		<li class="addItem" ><?php echo $this->Html->link($this->Html->image('icons/list-add.png', array('alt' => '')).' '.__('Ajouter un item a la commande'), array('controller' => 'ordered_items', 'action' => 'add' ,
$order['Order']['id']),array('escape' => false)); ?> </li>
<li class="email" ><?php echo $this->Html->link($this->Html->image('icons/mail-unread-new.png', array('id'=>'email_'.$order['Order']['id'],'class'=>'icon','alt' => __('Email'), 'onClick="return confirm(\''.$emailText.'\');"')), array('action' => 'email', $order['Order']['id']),  array('escape' => false, 'title'=>'Email')); ?></li>
	      <li class="pdf" ><?php echo $this->Html->link($this->Html->image('icons/document-print-frame.png', array('alt' => '')).' '.__('Imprimer'), array('action' => 'view', $order['Order']['id'].'.pdf'),array('escape' => false)); ?></li>
		<li class="delete" ><?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('alt' => '')).' '.__('Supprimer commande'), array('action' => 'delete', $order['Order']['id']), array('escape' => false), __('Are you sure you want to delete # %s?', $order['Order']['id'])); ?> </li>
		<li class="index" ><?php echo $this->Html->link($this->Html->image('icons/view-list-details.png', array('alt' => '')).' '.__('Lister les commandes'), array('action' => 'index'),array('escape' => false)); ?> </li>
	</ul>
</div>
  <?php if($order['Order']['comment'] != ''): ?>
    <?php echo $order['Order']['comment'] ?>
    <br/>
  <?php endif; ?>
<h3><?php echo __('Totaux'); ?></h3>
<table id="orderSummary" cellpadding = "0" cellspacing = "0" class="table" >

  <tr>
    <th>HT</th>
    <th>TVA %</th>
    <th>TTC</th>
    <th>TVA €</th>
  </tr>
<?php foreach ($total['tva'] as $tva => $data):  ?>
<tr>
  <td><?php echo round($data['HT'],2) ?></td>
  <td><?php echo $tva ?>%</td>
  <td><?php echo round($data['TTC']) ?></td>
  <td><?php echo round($data['tva_total'],2) ?></td>
</tr>
<?php endforeach; ?>
</table>



	<div>
		<p>Arrêtée à la somme de <b class="orderTotal" ><?php echo round($total['total']['HT'],2); ?>€</b></p>
	</div>
	<?php if($order['User']['rib_on_orders']): ?>
	<h3>Rib</h3>
	<img class="rib" src="<?php echo APP.'webroot/img/photos/normal/'.$company['Media']['path']; ?>" />
	<?php endif; ?>

	<p id="legalMentions">
		<?php echo $company['Company']['legals_mentions']; ?>
	</p>

<div class="related">
	<h3><?php echo __('Items commandés'); ?></h3>
	<?php if (!empty($order['OrderedItem'])): ?>
	<table id="orderedItemsTable" cellpadding = "0" cellspacing = "0" class="table table-striped" >
	<tr>
		<th><?php echo __('Produit'); ?></th>
		<th><?php echo __('Le'); ?></th>
		<th><?php echo __('Tva'); ?></th>
		<th><?php echo __('Prix initial'); ?></th>
		<th><?php echo __('Prix HT'); ?></th>
		<th><?php echo __('Après remise'); ?></th>
		<th><?php echo __('Quantité'); ?></th>
		<th><?php echo __('Prix total HT'); ?></th>
		<th><?php echo __('Commentaire'); ?></th>
		<th>Actions</th>
	</tr>
	<?php
		$i = 0;
		foreach ($order['OrderedItem'] as $orderedItem): ?>
		<tr rel="<?php echo $orderedItem['id'] ?>" >
			<td><a href="<?php echo $this->webroot.'produits/details/'.$orderedItem['Product']['id'] ?>" ><?php echo $orderedItem['Product']['name']; ?></a></td>
			<td class="OrderedItemCreated" ><?php $date = new DateTime ($orderedItem['created']);
			echo $date->format('d/m/Y'); ?></td>
			<td><?php echo $orderedItem['tva']; ?>%</td>
			<td><?php echo $orderedItem['price']; ?></td>
			<td><?php echo round($orderedItem['without_taxes'],3); ?></td>
			<td><?php echo round($orderedItem['discount_HT'],3); ?></td>
			<td class="OrderedItemQuantity" ><?php echo $orderedItem['quantity']; ?></td>

			<td><?php echo round(
				$orderedItem['discount_HT'] * $orderedItem['quantity']
				,2); ?></td>
			<td class="OrderedItemComment" ><?php echo $orderedItem['comment']; ?></td>
			<td class="actions">
				<?php // echo $this->Html->link(__('Details'), array('controller' => 'ordered_items', 'action' => 'view', $orderedItem['id'])); ?>
				<?php echo $this->Html->link($this->Html->image('icons/document-edit.png', array('alt' => __('Edition'))), array('controller' => 'ordered_items', 'action' => 'edit', $orderedItem['id']), array('escape' => false, 'class'=>'edit')); ?>
				<?php echo $this->Form->postLink($this->Html->image('icons/edit-delete.png', array('alt' => __('supprimer'))), array('controller' => 'ordered_items', 'action' => 'delete', $orderedItem['id']), array('escape' => false, 'class'=>'delete'), __('Are you sure you want to delete # %s?', $orderedItem['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
<script>

  function rowDataValid(_data)
  {
    return !( _data.OrderedItem.product_id == '' || _data.OrderedItem.created == '' || _data.OrderedItem.quantity == '');
  }

  function getRowData(row)
  {
    _data = {OrderedItem: {
          id:-1,
          product_id:'',
          date:'',
          quantity:'',
          comment:''
        }};
    if(row.attr('rel') != "")
    {
      _data.OrderedItem.id = row.attr('rel');
    }
    if(row.find('input[type="text"]').length == 0)
    {

          _data.OrderedItem.product_id=row.find('td.OrderedItemProductId').attr('rel');
          _data.OrderedItem.productName=row.find('td.OrderedItemProductId').text();
          _data.OrderedItem.created=row.find('td.OrderedItemCreated').text();
          if(_data.OrderedItem.created == "")
          {
            <?php
            $date = new DateTime();
            ?>
            _data.OrderedItem.created = '<?php echo $date->format('d/m/Y') ?>';
          }
          _data.OrderedItem.quantity=row.find('td.OrderedItemQuantity').text();
          _data.OrderedItem.comment=row.find('td.OrderedItemComment').text();
    }
    else
    {
      if(row.find('td').length != 0)
      {
          _data.OrderedItem.product_id=row.find('select[name="OrderedItemProductId"] option:selected').val();
          _data.OrderedItem.productName=row.find('select[name="OrderedItemProductId"] option:selected').text();
          _data.OrderedItem.created=row.find('input[name="OrderedItemCreated"]').val();
          _data.OrderedItem.quantity=row.find('input[name="OrderedItemQuantity"]').val();
          _data.OrderedItem.comment=row.find('textarea[name="OrderedItemComment"]').val();
      }
    }
     return _data;
  }

  function tdToInput(row)
  {

    _data = getRowData(row);
    var options = '<?php
    foreach($products as $productId=>$product)
    {
      echo '<option value="'.$productId.'" >'.str_replace(array('\''), array('\\\''), $product).'</options>';
    }
    ?>'
    html='<td class="OrderedItemProductId" ><select name="OrderedItemProductId" type="text" value="'+_data.OrderedItem.productId+'" >'+options+'</select></td><td class="OrderedItemCreated" ><input name="OrderedItemCreated" type="text" value="'+_data.OrderedItem.created+'" class="datepicker" name="" /></td><td>-</td><td>-</td><td>-</td><td>-</td><td class="OrderedItemQuantity"><input name="OrderedItemQuantity" type="number" value="'+_data.OrderedItem.quantity+'" class=""/></td><td>-</td><td class="OrderedItemComment" ><textarea name="OrderedItemComment">'+_data.OrderedItem.comment+' </textarea></td><td class="actions" ><button type="button" class="saveButton" >Valider </button></td>';
    row.html(html);
    updateDom();
  }

  function inputToTd(row)
  {
    _data = getRowData(row);
    html = html='<td class="OrderedItemProductId" >'+_data.OrderedItem.productName+'</td><td class="OrderedItemCreated" >'+_data.OrderedItem.created+'</td><td>-</td><td>-</td><td>-</td><td>-</td><td class="OrderedItemQuantity">'+_data.OrderedItem.quantity+'</td><td>-</td><td class="OrderedItemComment" >'+_data.OrderedItem.comment+' </td><td class="actions" ><a class="edit" href="/bakeryManager/ordered_items/edit/"><img alt="Edition" src="/bakeryManager/img/icons/document-edit.png"></a><a class="delete" href="" ><img alt="supprimer" src="/bakeryManager/img/icons/edit-delete.png"></a></td>';
    row.html(html);
    updateDom();
  }

  function save(row)
  {
      controller = 'add/<?php echo $order['Order']['id'] ?>';
      var _data = getRowData(row);
      if(!rowDataValid(_data))
      {
        return false;
      }

      if(_data.OrderedItem.id != -1)
      {
        controller='edit/'+_data.OrderedItem.id;
      }
      else
      {
        delete _data.OrderedItem.id;
      }

      var ret = true;
          jQuery.ajax({
              type: 'POST',
              url: '<?php echo $this->webroot ?>ordered_items/'+controller+'.json',
              async:false,
              accepts: 'application/json',
              data: _data,
              dataType: 'json',
              success: function (data) {

                  if(data.results.status)
                  {
                    row.removeClass("alert alert-danger");
                    _data.OrderedItem.id = data.results.id;
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
            row.attr('rel', _data.OrderedItem.id);
            disablePrices();
          }
          return ret;
  }

  function updateDom()
  {
    $( ".datepicker" ).datepicker();
    $(".saveButton").off("click").click(function(){
          row = $(this).closest('tr');

          ok = save(row);
          if(row.closest('table').find('tr').length -1 == row.index()) // before last line (save)
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

    $("a.edit").off("click").click(function(){
            row = $(this).closest('tr');
            tdToInput(row);
          return false;
          });

    $("a.delete").attr('onclick','').off("click").click(function(){
      if(!confirm("Voulez vous vraiment supprimer cet enregistrement?"))
      {
        return false;
      }
      row = $(this).closest('tr');
      var ret = true;
      jQuery.ajax({
          type: 'POST',
          url: '<?php echo $this->webroot ?>ordered_items/delete/'+row.attr('rel')+'.json',
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
        disablePrices();
      }
      return false;
      }
      );
  }

  function disablePrices()
  {
    $('.orderTotal').text('-');
    $('#orderSummary td').each(function() {$(this).text('-')});
    if( $('#disabledInfo').text() == "" )
    {
      html = '<div id="disabledInfo" class="alert alert-info">Veuillez recharger la page pour mettre a jour les informations de prix</div>'
      $('#orderSummary').after(html);
    }
  }

  $(document).ready(function(){
      row = $('<tr rel="">');
      tdToInput(row);
      $("#orderedItemsTable").append(row);
      updateDom();
      });
</script>
</div>