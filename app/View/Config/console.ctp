<?php echo $this->Form->create('Console'); ?>
  <fieldset>
    <legend><?php echo __('Commands'); ?></legend>
  <?php
    echo $this->Form->input('commands', array('type'=>'textarea'));
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
<pre>
<?php echo $output; ?>
</pre>