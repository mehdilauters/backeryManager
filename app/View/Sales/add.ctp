<div class="sales form">
<?php echo $this->Form->create('Sale',array('enctype' => 'multipart/form-data'));
echo $this->Form->input('upload', array('label'=>'fichier', 'type'=>'file'));
echo $this->Form->end(__('Submit'));
?>

<form id="salesDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" class="dateSearch" value="" />
</form>
<form id="salesAdd" method="POST" onSubmit="return checkInputs(true);" >
<h2>Le <?php echo $date ?></h2>
<input type="hidden" name="date" id="date" value="<?php echo $date ?>" />
<table class="table-striped">
  <tr>
    <th>Produit</th>
    <th>Magasin</th>
    <th>fabriqués</th>
    <th>Perdus</th>
    <th>Commentaires</th>
  </tr>
<?php
foreach($products as $product)
{
  ?>
  <tr>
    <td id="product_<?php echo $product['Product']['id'] ?>" ><a href="<?php  echo $this->webroot.'produits/details/'.$product['Product']['id']; ?>" ><?php  echo $product['Product']['name']; ?></a>
      <?php echo $this->element('Medias/Medias/Preview', array('media'=>$product, 'config'=>array('name'=>false, 'description'=>false))); ?>
    </td>
    <td>
      <ul>
        <?php foreach($shops as $shop){ ?>
          <li class="shop_<?php echo $shop ['Shop']['id'] ?> product_<?php echo $product['Product']['id']?>" >
			<a href="<?php  echo $this->webroot.'magasins/details/'.$shop['Shop']['id']; ?>" >
				<?php echo (strlen($shop ['Shop']['name']) > 13) ? substr($shop ['Shop']['name'],0,10).'...' : $shop ['Shop']['name'] ?>
			</a>
          </li>
        <?php } ?>
      </ul>
    </td>
    <td>
      <ul>
        <?php foreach($shops as $shop){ ?>
          <li class="produced shop_<?php echo $shop ['Shop']['id'] ?> product_<?php echo $product['Product']['id']?>" >
            <?php
                  $saleId = '';
                  $produced = '';
		  $comment = '';
                  foreach($product['Sale'] as $sale)
                  {
                    if($sale['shop_id'] == $shop['Shop']['id'])
                    {
                    $saleId = $sale['id'];
                    $produced = $sale['produced'];
		    $comment = $sale['comment'];
                    }
                  }
            ?>
            <input id="saleId_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" type="hidden" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][saleId]" size="10" value="<?php echo $saleId;  ?>"  />
            <input id="produced_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="watch spinner" type="text" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][produced]" size="10" value="<?php echo $produced; ?>" />
          </li>
        <?php } ?>
      </ul>
    </td>
    <td>
      <ul>
        <?php foreach($shops as $shop){ ?>
          <li class="lost shop_<?php echo $shop ['Shop']['id'] ?> product_<?php echo $product['Product']['id']?>" >
            <?php
                  $lost = '';
                  foreach($product['Sale'] as $sale)
                  {
                    if($sale['shop_id'] == $shop['Shop']['id'])
                    {
                    $lost = $sale['lost'];
                    }
                  }
            ?>
            <input id="lost_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" class="watch spinner" type="text" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][lost]" size="10" value="<?php echo $lost; ?>"  />
 </li>
        <?php } ?>
      </ul>
    </td>
<td>
      <ul>
        <?php foreach($shops as $shop){ ?>
          <li class="lost" >
            <?php
                  $comment = '';
                  foreach($product['Sale'] as $sale)
                  {
                    if($sale['shop_id'] == $shop['Shop']['id'])
                    {
                    $comment = $sale['comment'];
                    }
                  }
            ?>
            <textarea id="produced_<?php echo $shop['Shop']['id'].'_'.$product['Product']['id'] ?>" type="text" name="Sale[<?php echo $shop['Shop']['id']?>][<?php echo $product['Product']['id']?>][comment]" ><?php echo $comment; ?></textarea>
 </li>
        <?php } ?>
      </ul>
    </td>

  </tr>
  <?php
}
  
?>
</table>
<input type="submit" value="" class="save" />
</form>

</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('List Sales'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Products'), array('controller' => 'products', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Product'), array('controller' => 'products', 'action' => 'add')); ?> </li>
    <li><?php echo $this->Html->link(__('List Shops'), array('controller' => 'shops', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Shop'), array('controller' => 'shops', 'action' => 'add')); ?> </li>
  </ul>
</div>
<script type="text/javascript">

function processRow(id)
{
  reg = /(\d+)_(\d+)/;
  var ids = reg.exec(id);
  var shopId = ids[1];
  var productId = ids[2];
  var nbProduced = parseInt($("#produced_"+shopId+"_"+productId).val());
  var nbLost = parseInt($("#lost_"+shopId+"_"+productId).val());
  if(isNaN(nbProduced) || isNaN(nbLost))
  {
    $(".product_" + productId + ".shop_" + shopId).find('input').addClass('notSet');
    return true;
  }
  if(nbLost > nbProduced )
  {
    $(".product_" + productId + ".shop_" + shopId).find('input').removeClass('notSet');
    $(".product_" + productId + ".shop_" + shopId).find('input').addClass('invalid');
    return false;
  }
  else
  {
    $(".product_" + productId + ".shop_" + shopId).find('input').removeClass('invalid');
    $(".product_" + productId + ".shop_" + shopId).find('input').removeClass('notSet');
    $(".product_" + productId + ".shop_" + shopId).find('input').addClass('valid');
    return true;
  }
  return false;
}


function inputChange(inputObject)
{
  processRow(inputObject.attr('id')); 
}

function checkInputs(interactive)
{
  var text = "avez vous vraiment vendu plus de produits que fabriqués?\n";
  var ok = true;
  $("li.produced input[type=text]").each(function (index, value) {
	var id = $(this).attr('id');
    var res = processRow(id);
    if(!res)
    {
		reg = /(\d+)_(\d+)/;
		var ids = reg.exec(id);
		var shopId = ids[1];
		var productId = ids[2];
		var productName = $("td#product_"+productId).text();
		text += "- " + productName + "\n";
    }
    ok = ok && res;
  });
  if(typeof interactive !== 'undefined' && interactive)
  {
	  if(!ok)
	  {
		ok = confirm("Certaines valeurs ne sont peut-être pas valides\n" + text+"\n Ok => enregistrer quand même");
	  }
  }
  return ok;
}

  $(document).ready(function(){



    $('input.watch').change(function(){
    inputChange($(this))
    }
  );

      $("#dateSelectValue").change(function(){
    if( $(this).val() != $('#date').val() )
    {
      $('#salesAdd').hide();
    }
    else
    {
      $('#salesAdd').show();
    }
  });

    checkInputs(false);
  });

</script>