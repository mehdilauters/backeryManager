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


$defaultConfig = array('name' => false,
		      'description'=>false,
		      'preview' => 1);



if(isset($config))
{
  $config = array_merge($defaultConfig, $config);
}
else
{
  $config = $defaultConfig;
}



?>
<div class="thumbnail">
 <?php if($config['name']) : ?>
    <span><?php echo $data['Photo']['name'] ?></span>
  <?php endif ?>
  <div>
     <a class="fancybox" rel="album" href="<?php echo $this->webroot.'photos/download/'.$data['Photo']['id'].'/0'.$this->MyHtml->getLinkTitle($data['Photo']['name']) ?>" title="<?php echo $data['Photo']['name'] ?>" >
       <img src="<?php echo $this->webroot.'photos/download/'.$data['Photo']['id'].'/'.$config['preview'].$this->MyHtml->getLinkTitle($data['Photo']['name']) ?>" alt="<?php echo $data['Photo']['name'] ?>" />
     </a>
      <?php if($config['description']) : ?>
     <p>
       <?php echo $data['Photo']['description'] ?>
     </p>
      <?php endif ?>
  </div>
</div>