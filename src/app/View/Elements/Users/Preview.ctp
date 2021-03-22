<div class="User preview">
<table>
<tr><td><?php echo $this->Html->link($user['User']['name'], array('controller' => 'users', 'action' => 'view', $user['User']['id']));?></td></tr>
<tr><td><a href="http://maps.google.com/maps?q=<?php echo urlencode($user['User']['address']); ?>&z=17" target="_blank" ><?php echo $user['User']['address']; ?></a></td></tr>
<tr><td><?php if($user['User']['phone'] != 0) echo $user['User']['phone']; ?></td></tr>
</table>
</div> 