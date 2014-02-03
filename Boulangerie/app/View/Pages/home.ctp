<script>


		
$(document).ready(function() {
		$('.slateShopDescription').click(function() {
			var divId = $( this ).attr('id');
			var id= divId.replace('shopDescription_','');
			console.log(id);
			$("#timeTable_" + id).toggle('slow');
		});    
});


</script>
<?php
if($tokens['isAdmin'])
{
	echo $this->element('Results/stats/results', array('results'=>$results, 'config'=>array('interactive'=>false)));
}

 ?>

<?php $slideshowInserted = false; ?>
  <ul id="shopPreviewList">
    <?php foreach($shops as $shop) { ?>
    <li class="shop">
     <?php   echo $this->element('Shops/Preview', array('shop'=>$shop, 'isCalendarAvailable' => $isCalendarAvailable)); ?>
	 <br/>
	 <center>
		 <div id="timeTable_<?php  echo $shop['Shop']['id'] ?>" class="hideJs" >
			<?php echo $this->element('Shops/Timetable', array('eventType'=>$shop['EventType']));  ?>
		 </div>
	 </center>
    </li>
    <?php
if(!$slideshowInserted)
{ 
  $slideshowInserted = true;
  ?>
   <li>
      <center>
      <div class="slideshow" >
	<ul class="slides" id="homeSlideshow">
	  <?php foreach($products as $product) {?>

	    <li class="slide"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" ><img src="<?php echo $this->webroot.'img/photos/normal/'.$product['Media']['path'] ?>" alt="<?php echo $product['Product']['name'] ?>" width="400" height="300" /></a>
	    <div class="slide-title slate"><a href="<?php echo $this->webroot.'products/view/'.$product['Product']['id'] ?>" ><?php echo $product['Product']['name'] ?></a></div>
	    </li>
	  <?php } ?>
	</ul>
    </div>
    </center>
   </li>
<?php
}

 } ?>
  </ul>