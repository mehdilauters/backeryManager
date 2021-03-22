<div id="news_<?php $news['id']?>" class="news" >
<h4><?php echo $news['title'] ?></h4>
<p><?php echo $news['details'] ?></p>
<?php if($tokens['isAdmin']) : ?>
  <a href="<?php echo $this->webroot.'full_calendar/events/edit/'.$news['id'] ?>" ><?php echo $this->Html->image('icons/document-edit.png', array('id'=>'edit_'.$news['id'],'class'=>'icon','alt' => __('Edition'))) ?></a>
  <?php echo $this->Html->link(__('Delete', true), array('plugin' => 'full_calendar', 'controller' => 'events', 'action' => 'delete', $news['id']), null, sprintf(__('Are you sure you want to delete this %s news?', true), $news['title'])); ?>
<?php endif; ?>
</div> 
