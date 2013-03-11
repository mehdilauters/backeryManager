<?php 
 if( isset($photo['Photo'] ))
 {
   $data = $photo;
 }
 else
 {
 $data = $photo;
 $data['Photo'] = $photo['Media']['Photo'];
 }
 //debug($photo);

?>
<div>
  <span><?php echo $data['Media']['name'] ?></span>
  <img src="<?php echo $this->webroot.'img/photos/preview/'.$data['Media']['path'] ?>" alt="<?php echo $data['Media']['name'] ?>" />
  <p>
    <?php echo $photo['Media']['description'] ?>
  </p>
</div>