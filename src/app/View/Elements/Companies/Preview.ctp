<div class="Company preview" >
<?php if(!isset($company['Company']))
{
  debug('Company not set');
}
 ?>
<table>
<tr><td><?php echo $company['Company']['name'] ?></td></tr>
<tr><td><?php echo str_replace('\n',"<br/>",$company['Company']['address']) ?></td></tr>
<tr><td>telephone : <?php echo $company['Company']['phone'] ?></td></tr>
<tr><td>au capital de <?php echo $company['Company']['capital'] ?>€</td></tr>
<tr><td>n° de siret : <?php echo $company['Company']['siret'] ?></td></tr>
<tr><td>email : <a href="mailto:<?php echo $company['Company']['email'] ?>" > <?php echo $company['Company']['email'] ?> </a></td></tr>
</table>
</div>