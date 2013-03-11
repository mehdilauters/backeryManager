<?php 
 if( isset($media['Media']['Photo'] ))
 {
   echo $this->element('Medias/Photos/Preview', array('photo'=>$media));
 }
 else
 {
   debug('Preview not available');
   $key = 'Media';
 }
?>
