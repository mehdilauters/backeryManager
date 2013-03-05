<?php 
 if( isset($photo['Photo'] ))
 {
   $key = 'Photo';
 }
 else
 {
   $key = 'Media';
 }
?>
<div>
  <span><?php echo $photo[$key]['name'] ?></span>
  <img src="<?php echo $this->webroot.'img/photos/preview/'.$photo[$key]['path'] ?>" alt="<?php echo $photo[$key]['name'] ?>" />
  <p>
    <?php echo $photo[$key]['description'] ?>
  </p>
</div>