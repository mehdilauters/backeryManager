<div class="sales view">
<h2>Visualisation</h2>
<div id="selectDateInfo" class="alert alert-info">
  <p > selectionnez ici la date pour laquelle vous souhaitez visualiser les données de production</p>
  <form id="salesDateSelect" method="POST" >
    <input type="text" name="date" id="dateSelectValue" value="<?php echo $date ?>" class="datepicker" />
    <input type="submit" name="dateSelect" id="dateSelect" class="dateSearch" value="" />
  </form>
</div>

<h2><?php  echo $date; ?></h2>
<?php if(count($sales) == 0 ): ?>
  <div id="" class="alert alert-warning">
    Pas de production à cette date
  </div>
<?php else: ?>
  <div id="sales" >
    <table id="" class="table-striped table" >
      <tr>
	<th>Produit</th>
	<th>Magasin</th>
	<th>Fabriqués</th>
	<th>Vendus</th>
	<th>Perdus</th>
	<th>Prix</th>
	<th>Commentaire</th>
      </tr>
      <?php foreach($sales as $sale): ?>
	<tr>
	    <td>
	      <?php echo $sale['Product']['name'] ?>
	    </td>
	    <td>
	      <?php echo $sale['Shop']['name'] ?>
	    </td>
	    <td>
	      <?php echo $sale['Sale']['produced'] ?>
	    </td>
	    <td>
	      <?php echo $sale['Sale']['sold'] ?>
	    </td>
	    <td>
	      <?php echo $sale['Sale']['lost'] ?>
	    </td>
	    <td>
	      <?php echo round($sale['Sale']['totalPrice'],2) ?>
	    </td>
	    <td>
	      <?php echo round($sale['Sale']['comment'],2) ?>
	    </td>
	</tr>
      <?php endforeach; ?>
    </table>


    <div id="deleteSale" class="alert alert-warning">
      <p > Supprimer les données de production pour le <?php echo $date ?>?<br/>
	  Attention, c'est irreversible.
      </p>
      <form id="salesDelete" method="POST" action="<?php echo $this->webroot ?>sales/delete" >
	<input type="hidden" name="date" id="dateDelete" value="<?php echo $date ?>" />
	<input type="submit" name="delete" value="Supprimer" />
      </form>
  </div>
</div>

<?php endif; ?>
</div>
<script>
$(document).ready(function(){
  $("#dateSelectValue").change(function(){
    if( $(this).val() != $('#date').val() )
    {
      $('#sales').hide();
    }
    else
    {
      $('#sales').show();
    }
  });
});
</script>