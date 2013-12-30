<div class="results index">
<div>
  <form id="resultsDateSelect" method="POST" >
    <label>Début</label><input type="text" name="dateStart" id="dateStart" value="<?php echo $dateStart ?>" class="datepicker" />
    <label>Fin</label><input type="text" name="dateEnd" id="dateEnd" value="<?php echo $dateEnd ?>" class="datepicker" />
    <input type="submit" name="dateSelect" id="dateSelect" value="Afficher" />
  </form>
</div>
<hr/>
	<h2>Période du <span class="red" ><?php echo $dateStart ?></span> au <span class="red"><?php echo $dateEnd ?></span></h2>
<hr/>
<ul id="resultList">
  <?php foreach($data['entries'] as $shopId => $shopData): ?>
    <li> <h3><?php echo $shops[$shopId] ?></h3>
      <table>
	<tr class="legend" >
	  <th class="date" >Date</th>
	  <th class="rowTotal" >Total</th>
	  <th class="cash" >Especes</th>
	  <th class="check" >Cheques</th>
	  <?php foreach($productTypes as $typeId => $typeName): ?>
	    <th><?php echo $typeName; ?></th>
	  <?php endforeach ?>
	</tr>
	<?php foreach($shopData['entries'] as $results): 
	   $date = new DateTime($results['date']);
	   ?>
	  <tr>
	    <td class="date" ><?php echo $date->format('d/m/Y'); ?></td>
	    <td class="total" ><?php echo ($results['cash'] + $results['check']); ?></td>
	    <td class="cash" ><?php echo $results['cash']; ?></td>
	    <td class="check" ><?php echo $results['check']; ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult" ><?php if(isset($results['productTypes'][$typeId])) { echo $results['productTypes'][$typeId]['result']; } ?></td>
	    <?php endforeach ?>
	  </tr>
	<?php endforeach ?>
	  <tr class="total" >
	    <td class="total" >Totaux</td>
	    <td class="total"><?php echo ($shopData['total']['cash'] + $shopData['total']['check']) ?></td>
	    <td class="cash"><?php echo $shopData['total']['cash'] ?></td>
	    <td class="check"><?php echo $shopData['total']['check'] ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult"><?php if(isset($shopData['total'][$typeId])) { echo $shopData['total'][$typeId]; } ?></td>
	    <?php endforeach ?>
	  </tr>
      </table>
    </li>
  <?php endforeach ?>
    <li>
      <h3>Totaux</h3>
      <table class="total" >
	<tr class="legend" >
	  <th class="date" ></th>
	  <th class="total" >Total</th>
	  <th class="cash" >Especes</th>
	  <th class="check" >Cheques</th>
	  <?php foreach($productTypes as $typeId => $typeName): ?>
	    <th class="productTypeResult" ><?php echo $typeName; ?></th>
	  <?php endforeach ?>
	</tr>
	<tr class="" >
	    <td class="date" >Totaux</td>
	    <td class="total" ><?php echo ($data['total']['cash'] + $data['total']['check']) ?></td>
	    <td class="cash" ><?php echo $data['total']['cash'] ?></td>
	    <td class="check" ><?php echo $data['total']['check'] ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult" ><?php if(isset($data['total'][$typeId])) { echo $data['total'][$typeId]; } ?></td>
	    <?php endforeach ?>
	  </tr>
      </table>
    </li>
</ul>
<a href="<?php echo $this->webroot ?>results/add" >Saisie</a>
</div>
