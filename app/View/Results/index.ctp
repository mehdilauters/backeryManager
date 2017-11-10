<div class="results index">
<div>
  <form id="resultsDateSelect" method="POST" >
    <label>Début</label><input type="text" name="dateStart" id="dateStart" value="<?php echo $dateStart ?>" class="datepicker" />
    <label>Fin</label><input type="text" name="dateEnd" id="dateEnd" value="<?php echo $dateEnd ?>" class="datepicker" />
    <input type="submit" name="dateSelect" id="dateSelect" value="" class="dateSearch" />
    <input type="submit" name="excelDownload" id="dateSelectExcel" value="" class="spreadsheet" />
  </form>
</div>
<hr/>
  <h2>Période du <span class="red" ><?php echo $dateStart ?></span> au <span class="red"><?php echo $dateEnd ?></span></h2>
<div id="resultsHistoPlot" ></div>
<hr/>
<ul id="resultList">
  <?php 
  	$firstWeek = -1;
      if(isset($data['entries'])) foreach($data['entries'] as $shopId => $shopData): 
	$weekId = -1;
	$total = array();
	
  ?>
    <li class="shop" id="result_shop_<?php echo $shopId ?>" > <h3><?php echo $shops[$shopId] ?></h3>
      <table class="shop table table-striped" >
      <thead>
  <tr class="legend" >
    <th class="date" >Date</th>
    <th class="rowTotal" >Total</th>
    <th class="cash" >Especes</th>
    <th class="check" >Cheques</th>
	<th class="card" >Carte Bleue</th>
	<th class="account" >Compte clients</th>
    <?php foreach($productTypes as $typeId => $typeName): ?>
      <th><?php echo $typeName; ?></th>
    <?php endforeach ?>
    <th class="comment" >Commentaire</th>
  </tr>
  </thead>
  <tbody>
  <?php 
  
	$total['cash'] = 0;
	$total['check'] = 0;
	$total['card'] = 0;
	$total['account'] = 0;
	foreach($productTypes as $typeId => $typeName)
	{
		$total['productType'][$typeId] = 0;
	}
  
	foreach($shopData['entries'] as $results): 
	$date = new DateTime($results['date']);
	if($weekId == -1)
	{
		$weekId = $date->format('W');
	}
     
	 if ($weekId != $date->format('W'))
	 {
		if ($firstWeek == -1 )
		{
		  $firstWeek = $date->format('W');
		}
	 ?>
		<tr id="total_shop_<?php echo $shopId ?>_week_<?php echo $date->format('W'); ?>" class="total">
		  <td class="date" ><?php echo $date->format('W'); ?></td>
		  <td class="total" ><?php echo round(($total['cash'] + $total['check'] + $total['card']+$total['account']),2); ?></td>
		  <td class="cash" ><?php echo round($total['cash'],2); ?></td>
		  <td class="check" ><?php echo round($total['check'],2); ?></td>
		  <td class="card" ><?php echo round($total['card'],2); ?></td>
<!-- 		  <td class="account" ><?php echo round($total['account'],2); ?></td> -->
		  <?php 
			foreach($productTypes as $typeId => $typeName): ?>
			<td class="productTypeResult" ><?php if(isset($total['productType'][$typeId])) { echo round($total['productType'][$typeId],2); } ?></td>
		  <?php endforeach ?>
		  <td class="comment" ></td>
		</tr>
	  <?php
		$weekId = $date->format('W');
		$total['cash'] = 0;
		$total['check'] = 0;
		$total['card'] = 0;
		foreach($productTypes as $typeId => $typeName)
		{
			$total['productType'][$typeId] = 0;
		}
	 }
	 
     ?>
    <tr>
      <td class="date" ><?php echo $date->format('d/m/Y'); ?></td>
      <td class="total" ><?php echo round($results['cash'] + $results['check'] + $results['card'] + $results['account'], 2); ?></td>
      <td class="cash" ><?php echo round($results['cash'],2); $total['cash'] += $results['cash'];  ?></td>
      <td class="check" ><?php echo round($results['check'],2); $total['check'] += $results['check']; ?></td>
	  <td class="card" ><?php echo round($results['card'],2); $total['card'] += $results['card']; ?></td>
	  <td class="account" ><?php echo round($results['account'],2); $total['account'] += $results['account']; ?></td>
      <?php 
        foreach($productTypes as $typeId => $typeName): ?>
        <td class="productTypeResult" ><?php if(isset($results['productTypes'][$typeId])) { echo round($results['productTypes'][$typeId]['result'],2); $total['productType'][$typeId] += $results['productTypes'][$typeId]['result']; } ?></td>
      <?php endforeach ?>
      <td class="comment" ><?php echo $results['comment'] ?></td>
    </tr>
  <?php endforeach ?>
	<tr class="total">
	  <td class="date" ><?php echo $date->format('W'); ?></td>
	  <td class="total" ><?php echo ($total['cash'] + $total['check'] + $total['card'] + $total['account']); ?></td>
	  <td class="cash" ><?php echo $total['cash']; ?></td>
	  <td class="check" ><?php echo $total['check']; ?></td>
	  <td class="card" ><?php echo $total['card']; ?></td>
	  <td class="account" ><?php echo $total['account']; ?></td>
	  <?php 
		foreach($productTypes as $typeId => $typeName): ?>
		<td class="productTypeResult" ><?php if(isset($total['productType'][$typeId])) { echo round($total['productType'][$typeId],2); } ?></td>
	  <?php endforeach ?>
	  <td class="comment" ></td>
	</tr>
    <tr id="total_shop_<?php echo $shopId ?>" class="total" >
      <td class="total" >Totaux</td>
	<td class="total"><?php echo round($shopData['total']['cash'] + $shopData['total']['check'] + $shopData['total']['card'] +  $shopData['total']['account'],2) ?></td>
      <td class="cash"><?php echo round($shopData['total']['cash'],2) ?></td>
      <td class="check"><?php echo round($shopData['total']['check'],2) ?></td>
	  <td class="card"><?php echo round($shopData['total']['card'],2) ?></td>
	  <td class="account"><?php echo round($shopData['total']['account'],2) ?></td>
      <?php 
        foreach($productTypes as $typeId => $typeName): ?>
        <td class="productTypeResult"><?php if(isset($shopData['total'][$typeId])) { echo round($shopData['total'][$typeId],2); } ?></td>
      <?php endforeach ?>
      <td></td>
    </tr>
      </table>
    </li>
  <?php endforeach ?>
    <li>
      <h3>Totaux</h3>
      <table id="mainTotal" class="total" >
  <tr class="legend" >
    <th class="date" ></th>
    <th class="total" >Total</th>
    <th class="cash" >Especes</th>
    <th class="check" >Cheques</th>
	<th class="card" >Carte Bleue</th>
	<th class="account" >Comptes clients</th>
    <?php foreach($productTypes as $typeId => $typeName): ?>
      <th class="productTypeResult" ><?php echo $typeName; ?></th>
    <?php endforeach ?>
  </tr>
  <tr class="" >
      <td class="date" >Totaux</td>
      <td class="total" ><?php echo round($data['total']['cash'] + $data['total']['check'] + $data['total']['card'] + $data['total']['account'], 2) ?></td>
      <td class="cash" ><?php echo round($data['total']['cash'],2) ?></td>
      <td class="check" ><?php echo round($data['total']['check'],2) ?></td>
	  <td class="card" ><?php echo round($data['total']['card'],2) ?></td>
	  <td class="account" ><?php echo round($data['total']['account'],2) ?></td>
      <?php 
        foreach($productTypes as $typeId => $typeName): ?>
        <td class="productTypeResult" ><?php if(isset($data['total'][$typeId])) { echo round($data['total'][$typeId],2); } ?></td>
      <?php endforeach ?>
    </tr>
    </tbody>
      </table>
    </li>
</ul>
<a href="<?php echo $this->webroot ?>results/add" >Saisie</a>
</div>
<script type="text/javascript">

  introSteps = [];
  introSteps.push(
              { 
                intro: 'Cette page affiche le chiffre d\'affaire, magasin par magasin, ainsi que les sous-totaux semaines par semaines, et le total complet.'
              },
              {
                element: '#resultsDateSelect',
                intro: "Sélectionnez ici les dates qui vous interessent.",
		position: 'right'
              },
              {
                element: '#dateSelect',
                intro: "Et affichez les données correspondantes.",
		position: 'right'
              },
              {
                element: '#dateSelectExcel',
                intro: "Ou téléchargez le document excel récapitulatif (pour envoyer au comptable par exemple)",
		position: 'right'
              });
	    <?php if(isset($data['entries'])): ?>
	      introSteps.push(
              {
                element: '#result_shop_<?php /* php3 compatibility */ $entries = array_keys($data['entries']); echo $entries[0] ?>',
                intro: "Pour chaque magasin",
		position: 'right'
              },
              {
                element: '#total_shop_<?php echo $entries[0] ?>_week_<?php echo $firstWeek ?>',
                intro: "Retrouvez les sous-totaux semaines par semaines",
		position: 'right'
              },
              {
                element: '#total_shop_<?php echo $entries[0] ?>',
                intro: "Le sous-total pour le magasin complet",
		position: 'right'
              },
              {
                element: '#mainTotal',
                intro: "Et en bas de la page se trouve le total global, tous magasins confondus.",
		position: 'right'
              });
	      <?php else: ?>
	      introSteps.push(
		{
		  element: '#resultsDateSelect',
		  intro: "Selectionnez une plage de date pour lesquelles il y a des resultats",
		  position: 'right'
		});
	      <?php endif; ?>

</script>
