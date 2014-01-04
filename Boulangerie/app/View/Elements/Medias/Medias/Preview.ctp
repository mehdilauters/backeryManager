<?php 
$initConfig = array(
                    'name'       => true,
                    'description' => true
                    );
 if( isset($config) )
 {
    $config = array_merge($initConfig , $config);
 }
 else
 {
   $config = $initConfig;
 }

 if( isset($media['Media']['Photo'] ))
 {
   echo $this->element('Medias/Photos/Preview', array('photo'=>$media, 'config' => $config));
 }
 else
 {
   //debug('Preview not available');
   $key = 'Media';
 }
?>
