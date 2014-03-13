<div class="Company preview" >
<?php if(!isset($company['Company']))
{
  debug('Company not set');
}
 ?>
<table>
<tr><td><?php echo $company['Company']['name'] ?></td></tr>
<tr><td><?php echo $company['Company']['address'] ?></td></tr>
<tr><td><?php echo $company['Company']['phone'] ?></td></tr>
<tr><td><?php echo $company['Company']['capital'] ?></td></tr>
<tr><td><?php echo $company['Company']['siret'] ?></td></tr>
<tr><td><?php echo $company['Company']['email'] ?></td></tr>
</table>
</div>