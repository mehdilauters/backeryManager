<div class="sales form">
<div id="excelImport" class="alert alert-info">
  <p>
    Téléchargez le fichier suivant, <a href="<?php echo $this->webroot ?>sales/add.xls" >Ventes.xls</a> remplissez le, enregistrez le et enfin importez le directement sur le site. Il permet de gérer les données de ventes même sans connexion internet</p>
  <?php echo $this->Form->create('Sale',array('enctype' => 'multipart/form-data'));
  echo $this->Form->input('upload', array('label'=>'fichier', 'type'=>'file'));
  echo $this->Form->end(__('Submit'));
  ?>
</div>
<div id="selectDateInfo" class="alert alert-info">
  <p > selectionnez ici la date pour laquelle vous souhaitez saisir/modifier les données de production</p>
  <form id="salesDateSelect" method="POST" >
    <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
    <input type="submit" name="dateSelect" id="dateSelect" class="dateSearch" value="" />
  </form>
</div>
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
  <tr id="productRow_<?php echo $product['Product']['id'] ?>" >
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
    <td id="produced_<?php echo $product['Product']['id'] ?>" >
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
    <td id="lost_<?php echo $product['Product']['id'] ?>">
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
<td id="comments_<?php echo $product['Product']['id'] ?>" >
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
<input type="submit" value="" class="save" id="resultAddSubmit" />
</form>

</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>
    <li><?php echo $this->Html->link(__('stats Sales'), array('action' => 'stats')); ?></li>
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

  
  introSteps = [
              { 
                intro: 'Cette page permet de saisir les données journalières de production, par magasin et par produit.<br/>Ce sont ensuite ces données qui sont utilisés pour tracer certains graphes'
              },
              {
                element: '#selectDateInfo',
                intro: "Commencez par sélectionner la date pour laquelle vous voulez saisir les données,<br/> puis cliquez sur le calendrier",
				position: 'top'
              },
			  {
                element: '#productRow_<?php echo $products[0]['Product']['id'] ?>',
                intro: "Pour chaque produit, vous devrez ensuite saisir",
				position: 'top'
              },
			  {
                element: '#produced_<?php echo $products[0]['Product']['id'] ?>',
                intro: "Pour chaque magasin, le nombre de produit fabriqués",
				position: 'top'
              },
			  {
                element: '#lost_<?php echo $products[0]['Product']['id'] ?>',
                intro: "le nombre de produit perdus",
				position: 'top'
              },	  
			  {
                element: '#comments_<?php echo $products[0]['Product']['id'] ?>',
                intro: "un commentaire si necessaire<br/>(jour de marché, concurrent malade...)",
				position: 'top'
              },
	      {
                element: '#resultAddSubmit',
                intro: "Attention, n'oubliez pas de valider",
				position: 'top'
              },
	      {
                element: '#salesStats',
                intro: "Des statistiques sur ces données seront ensuite disponibles sur cette page",
				position: 'left'
              },
	      {
                element: '#excelImport',
                intro: "Afin de prévoir les coupures internet, veuillez télécharger le fichier Excel ci-contre. Lorsqu'une coupure d'internet survient, remplissez le simplement, et ce, même pendant plusieurs jours. Importez le ensuite directement sur le site lorsque l'accès à internet est rétabli. Toutes vos données seront ainsi automatiquement ajoutées",
		position: 'right'
              },
			  {
                element: '#dailyResultsLink',
                intro: "Ensuite rendez vous sur la page de saisie journalière comptable<br/>Si vous l'avez déjà visité, votre travail journalier est terminé",
				position: 'left'
              },
			  {
                element: '#ordersLink',
                intro: "Mais vous pouvez aussi gérer les commandes/factures de vos clients",
				position: 'left'
              },
            ];
  
</script>