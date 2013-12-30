<?php 
 if( isset($photo['Photo'] ))
 {
  //debug($photo);
   $data = $photo;
 }
 else
 {
 //debug($photo);
 $data = $photo;
 $data['Photo'] = $photo['Media'];
 }
 //debug($photo);
?>
<div class="photoPreview">
 <?php if(isset($config) && $config['name']) : ?>
    <span><?php echo $data['Photo']['name'] ?></span>
  <?php endif ?>
  <div>
     <a class="fancybox" rel="album" href="<?php echo $this->webroot.'img/photos/normal/'.$data['Photo']['path'] ?>" title="<?php echo $data['Photo']['name'] ?>" >
       <img src="<?php echo $this->webroot.'img/photos/preview/'.$data['Photo']['path'] ?>" alt="<?php echo $data['Photo']['name'] ?>" />
     </a>
      <?php if(isset($config) && $config['description']) : ?>
     <p>
       <?php echo $data['Photo']['description'] ?>
     </p>
      <?php endif ?>
  </div>
</div>