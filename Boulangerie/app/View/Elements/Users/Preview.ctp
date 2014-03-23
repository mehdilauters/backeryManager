<div class="User preview">
<table>
<tr><td><?php echo $user['User']['name']; ?></td></tr>
<tr><td><?php echo $user['User']['address']; ?></td></tr>
<tr><td><?php if($user['User']['phone'] != 0) echo $user['User']['phone']; ?></td></tr>
</table>
</div> 