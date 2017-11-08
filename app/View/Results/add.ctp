<div class="results form">
<div class="alert alert-info">Saisissez au jour le jour le chiffre d'affaire récolté par vos différents magasins</div>

<div id="excelImport" class="alert alert-info">
<p >
    Téléchargez le fichier suivant, <a href="<?php echo $this->webroot ?>results/add.xls" >Comptabilité.xls</a> remplissez le, enregistrez le et enfin importez le directement sur le site. Il permet de gérer les données de comptabilité même sans connexion internet</p>
<?php echo $this->Form->create('Result',array('enctype' => 'multipart/form-data'));
echo $this->Form->input('upload', array('label'=>'fichier', 'type'=>'file'));
echo $this->Form->end(__('Submit'));
?>
</div>
<div id="selectResultDate" class="alert alert-info">
<p class=""> selectionnez ici la date pour laquelle vous souhaitez saisir/modifier les données de comptabilité</p>
<form id="resultsDateSelect" method="POST" >
  <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
  <input type="submit" name="dateSelect" id="dateSelect" value="" class="dateSearch" />
</form>
</div>
<form id="resultsAdd" method="POST" onSubmit="return checkForm()" >
<input type="hidden" id="date" name="date" value="<?php echo $date ?>" />
<h2>Le <?php echo $date ?> <span id="totalDay"></span></h2>
<ul id="resultShops" >
<?php foreach($shops as $shopId => $shopName){ ?>
<li id="resultsShop_<?php echo $shopId ?>" class="resultsShop" >
	<div class="alert">
	<h3><?php echo $shopName; ?></h3>
	<span class="message"></span>
	</div>
	    <fieldset id="paymentResults_<?php echo $shopId ?>" class="paymentResults" >
	      <?php
		  $resultId='';
		  $cash = '';
		  $check = '';
		  $card = '';
		  $account = '';
		  $comment = '';
		  if(isset($data['entries'][$shopId]['entries'][0]))
		  {
		    $resultId=$data['entries'][$shopId]['entries'][0]['resultId'];
		    $cash = $data['entries'][$shopId]['entries'][0]['cash'];
		    $check = $data['entries'][$shopId]['entries'][0]['check'];
			$card = $data['entries'][$shopId]['entries'][0]['card'];
			$account = $data['entries'][$shopId]['entries'][0]['account'];
		    $comment = $data['entries'][$shopId]['entries'][0]['comment'];
		  }
		?>
		    <legend>Totaux</legend>
			<table>
				<tr>
				   <td>Total</td>
				    <td id="total_resultsShop_<?php echo $shopId ?>"></td>
				</tr>
				<tr>
					<td>
		    <label>Especes</label>
				</td><td>
			<input id="resultShop_<?php echo $shopId ?>_cash" autocomplete="off"  type="number" step="0.01" name="Result[<?php echo $shopId; ?>][cash]" value="<?php echo $cash ?>" size="10" class="spinner totalShop resultShop_<?php echo $shopId ?>" />€
				</tr>
				<tr>
					<td>
		    <label>Cheques</label>
				</td><td>	
			<input id="resultShop_<?php echo $shopId ?>_check" autocomplete="off"  type="number" step="0.01" name="Result[<?php echo $shopId; ?>][check]" value="<?php echo $check ?>" size="10" class="totalShop resultShop_<?php echo $shopId ?>" />€
				</tr>
				<tr>
					<td>
			<label>Carte Bleue</label>
					</td><td>	
			<input id="resultShop_<?php echo $shopId ?>_card" autocomplete="off"  type="number" step="0.01" name="Result[<?php echo $shopId; ?>][card]" value="<?php echo $card ?>" size="10" class="totalShop resultShop_<?php echo $shopId ?>" />€
				</tr>
				<tr>
					<td>
			<label>Comptes clients</label>
					</td><td>	
			<input id="resultShop_<?php echo $shopId ?>_account" autocomplete="off"  type="number" step="0.01" name="Result[<?php echo $shopId; ?>][account]" value="<?php echo $account ?>" size="10" class="totalShop resultShop_<?php echo $shopId ?>" />€
				</tr>
				<tr>
					<td>
						<label>Commentaire</label>
					</td><td>	
						<textarea name="Result[<?php echo $shopId; ?>][comment]" ><?php echo $comment ?></textarea>
					</td>
				</tr>
			</table>
		    <input type="hidden" name="Result[<?php echo $shopId; ?>][resultId]" value="<?php echo $resultId; ?>" />
	    </fieldset>
	    <fieldset id="productTypesResults_<?php echo $shopId ?>" class="productTypesResults" >
		    <legend><?php echo __('Product types'); ?></legend>
    <table>
      <tr>
	<th>Type de produit</th>
	<th>Prix</th>
      </tr>
      <?php foreach($productTypes as $typeId => $typeName){ ?>
      <?php
	  $result = '';
	  $resultEntryId = '';
	  if(isset($data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]))
	  {
	    $result = $data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]['result'];
	    $resultEntryId = $data['entries'][$shopId]['entries'][0]['productTypes'][$typeId]['resultEntryId'];  
	  }
      ?>
	<tr>
	  <td><?php echo $typeName ?></td>
	  <td><input type="number"  step="0.01" autocomplete="off" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][result]"  value="<?php echo $result ?>" size="10" class="totalShopCategory" />€
	  <input type="hidden" name="Result[<?php echo $shopId ?>][productTypes][<?php echo $typeId ?>][resultEntryId]"  value="<?php echo $resultEntryId ?>" />
	</td>
	</tr>
      <?php } ?>
    </table>
	    <?php
	    ?>
	    </fieldset>
</li>
<?php } ?>
</ul>
<input type="submit" value="" class="save" id="saveResult" />
</div>
<script>

function checkTotals()
{
	var data = {};
	$('#resultShops li').each(function( index ) {
		var idShop = $( this ).attr('id');
	
		data[idShop] = {};
		data[idShop]['totalPrice'] = 0 ;
		data[idShop]['totalCategories'] = 0 ;
		$( this ).find('input.totalShop').each(function( index ) {
				var val = parseFloat($(this).val());
				if( !isNaN(val))
				{
					data[idShop]['totalPrice'] += Math.round(val  * 100) / 100 ;
					$(this).closest('tr').removeClass('invalid');
					$(this).closest('tr').removeClass('valid');
					$(this).closest('tr').removeClass('notSet');
				}
				else
				{
					$(this).closest('tr').addClass('notSet');
				}
			});
		$( this ).find('input.totalShopCategory').each(function( index ) {
				var val = parseFloat($(this).val());
				if( !isNaN(val))
				{
					data[idShop]['totalCategories'] += val;
					$(this).closest('tr').removeClass('invalid');
					$(this).closest('tr').removeClass('valid');
				}
				else
				{
					$(this).closest('tr').addClass('notSet');
				}
			});
	      	data[idShop]['totalCategories'] = Math.round(data[idShop]['totalCategories'] * 100) / 100;
		data[idShop]['totalShopCategory'] = Math.round(data[idShop]['totalShopCategory'] * 100) / 100;
                data[idShop]['totalPrice'] = Math.round(data[idShop]['totalPrice'] * 100) / 100;

	});
	

	var ok = true;
	var totalDay = 0;
	for(var shop in data)
	{
		$('#total_'+shop).html(data[shop]['totalPrice'] + ' €');
		totalDay += data[shop]['totalPrice'];
		if(data[shop]['totalPrice'] != data[shop]['totalCategories'])
		{
			ok = false;
			$('li#'+shop+' div.alert').addClass('alert-danger');
			$('li#'+shop+' div.alert').removeClass('alert-success');
			$('li#'+shop+' div.alert > span').html('Certaines valeurs ne sont peut-être pas valides');
		}
		else
		{
			$('li#'+shop+' div.alert').addClass('alert-success');
			$('li#'+shop+' div.alert').removeClass('alert-danger');
			$('li#'+shop+' div.alert > span').html('');
		}
	}
	$('#totalDay').html('('+(Math.round(totalDay  * 100) / 100 )+ ' €)');
	return ok;
}

function checkForm()
{
	var ok = true;
	ok = checkTotals();
	if(!ok)
	{
		ok = confirm("Certaines valeurs ne sont peut-être pas valides\n Ok => enregistrer quand même");
	}
	return ok;
}

 $(document).ready(function(){



      $("#dateSelectValue").change(function(){
	  if( $(this).val() != $('#date').val() )
	  {
	    $('#resultsAdd').hide();
	  }
	  else
	  {
	    $('#resultsAdd').show();
	  }
	});
	
	
	$('#resultShops input').change( function () { checkTotals(); });
	checkTotals();
	
	
  });






      introSteps = [
              { 
                intro: 'Cette page permet de saisir les résultats comptables au jour le jour.<br/>Ces données servent également à afficher des graphs'
              },
              {
                element: '#selectResultDate',
                intro: "Commencez par saisir la date pour laquelle vous voulez saisir les informations.<br/>Cliquez sur le calendrier pour valider",
		position: 'top'
              },
              {
                element: '#resultsShop_<?php /* php3 compatibility */ echo $shopId; ?>',
                intro: "Ensuite, remplissez par magasin...",
		position: 'right'
              },
              {
                element: '#paymentResults_<?php echo $shopId; ?>',
                intro: "Les différents moyens de paiement",
		position: 'right'
              },
              {
                element: '#productTypesResults_<?php echo $shopId; ?>',
                intro: "les sommes encaissées pour chaque type de produit",
		position: 'right'
              },
              {
                element: '#saveResult',
                intro: "et n'oubliez pas de valider",
		position: 'right'
              },
	      {
                element: '#excelImport',
                intro: "Afin de prévoir les coupures internet, veuillez télécharger le fichier Excel ci-contre. Lorsqu'une coupure d'internet survient, remplissez le simplement, et ce, même pendant plusieurs jours. Importez le ensuite directement sur le site lorsque l'accès à internet est rétabli. Toutes vos données seront ainsi automatiquement ajoutées",
		position: 'right'
              },
              {
                element: '#dailyProductionLink',
                intro: "Si vous avez déjà saisis les données de productions,<br/>C'est tout ce dont vous avez besoin de saisir au jour le jour.",
		position: 'left'
              },
			  {
                element: '#ordersLink',
                intro: "Mais vous pouvez aussi gérer les commandes/factures de vos clients",
				position: 'left'
              },
			  
            ];

</script>
