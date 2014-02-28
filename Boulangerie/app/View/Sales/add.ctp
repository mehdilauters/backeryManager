<div class="sales form">
<form id="salesDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" class="dateSearch" value="" />
</form>
<form id="salesAdd" method="POST" >
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
          <li>
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
          <li>
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
          <li class="lost" >
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
  function total()
  {
    var totalProduced = 0;
    var totalSold = 0;
    var totalLost = 0;
    var totalAmount = 0;

    $('#shopList li').each(function(index, item){
  shopPattern=/\w+_(\d+)/
  res = shopPattern.exec($(item).attr('id'));
  shopId = res[1];
  totalShop(shopId);
  totalProduced += parseFloat($(item).find('#totalProduced_' + shopId ).text());
  totalSold += parseFloat($(item).find('#totalSold_' + shopId ).text());
  totalLost += parseFloat($(item).find('#totalLost_' + shopId ).text());
  totalAmount += parseFloat($(item).find('#totalAmount_' + shopId ).text());
    });
    $('#totalProduced' ).text(totalProduced);
    $('#totalSold' ).text(totalSold);
    $('#totalLost' ).text(totalLost);
    $('#totalAmount' ).text(totalAmount);
  }

  function totalShop(shopId)
  {
    var totalShopProduced = 0;
    var totalShopSold = 0;
    var totalShopLost = 0;
    var totalShopAmount = 0;

    $('#shop_' + shopId + ' tr.subtotal').each(function(index, item){
  var subtotalProduced = 0;
  var subtotalSold = 0;
  var subtotalLost = 0;
  var subtotalAmount = 0;
    productPattern=/\w+_(\d+)_(\d+)/
    res = productPattern.exec($(item).attr('id'));
    productTypeId = res[2];
    $('#shop_' + shopId + ' tr.product.productType_' + productTypeId).each(function(index, item){
        var produced = $(item).find('.produced input').val();
        if( produced == '' )
        {
    produced = 0;
        }
        else
        {
    produced = parseFloat(produced);
        }

        var sold = $(item).find('.sold input').val();
        if( sold == '' )
        {
    sold = 0;
        }
        else
        {
    //console.log(sold);
    sold = parseFloat(sold);
        }
        
        var lost = $(item).find('.lost').text();
        if( lost == '' )
        {
    lost = 0;
        }
        else
        {
    lost = parseFloat(lost);
        }
        var amount = $(item).find('.amount').text();
        if( amount == '' )
        {
    amount = 0;
        }
        else
        {
    amount = parseFloat(amount);
        }
        subtotalProduced += produced;
        subtotalSold += sold;
        subtotalLost += lost;
        subtotalAmount += amount;
      });
    totalShopProduced += subtotalProduced;
    totalShopSold += subtotalSold;
    totalShopLost += subtotalLost;
    totalShopAmount += subtotalAmount;
    $('#totalProduced_' + shopId + '_' + productTypeId ).text(subtotalProduced);
    $('#totalSold_' + shopId + '_' + productTypeId ).text(subtotalSold);
    $('#totalLost_' + shopId + '_' + productTypeId ).text(subtotalLost);
    $('#totalAmount_' + shopId + '_' + productTypeId ).text(subtotalAmount.toFixed(2));
      });
    $('#totalProduced_' + shopId ).text(totalShopProduced);
    $('#totalSold_' + shopId ).text(totalShopSold);
    $('#totalLost_' + shopId ).text(totalShopLost);
    $('#totalAmount_' + shopId ).text(totalShopAmount.toFixed(2));

  }

function inputChange(inputObject)
{
  var producedPrefix = 'produced_';
  var soldPrefix = 'sold_';
  var lostPrefix = 'lost_';
  var amountPrefix = 'amount_';
  var pricePrefix = 'price_';
  var productPrefix = 'product_';
  var rowPrefix = 'row_';
  //console.log(inputObject);
  var id = inputObject.attr('id').replace(producedPrefix, '').replace(soldPrefix, '');
  var producedId = producedPrefix + id;
  var soldId = soldPrefix + id;
  var lostId = lostPrefix + id;
  var priceId = pricePrefix + id;
  var amountId = amountPrefix + id;
  var rowId = rowPrefix + id;
  
  var nbProduced = $("#"+producedId).val();
  var nbSold = $("#" + soldId).val();
  if(nbProduced == '' || nbSold == '')
  {
    $("#" + rowId).addClass('notSet');
    return;
  }
  var lost = nbProduced - nbSold;

  if(lost < 0 )
  {
    console.log("Wrong values");
    $("#" + rowId).removeClass('notSet');
    $("#" + rowId).addClass('invalid');
  }
  else
  {
    $("#" + rowId).removeClass('invalid');
    $("#" + rowId).removeClass('notSet');
    $("#" + rowId).addClass('valid');
  }

  var amount = $("#"+soldId).val() * $("#" + priceId).html()
  
  $("#" + lostId).text(lost);
  $("#" + amountId).text(amount.toFixed(2));

  shopPattern=/\w+_(\d+)_(\d+)/
  res = shopPattern.exec(inputObject.attr('id'));
  var shopId = res[1];
  var productId = res[2];
  total();
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
    total();
  });

</script>