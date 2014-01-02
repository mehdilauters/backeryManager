<div class="results index">
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/rainbow.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />
<div>
  <form id="resultsDateSelect" method="POST" >
    <label>Début</label><input type="text" name="dateStart" id="dateStart" value="<?php echo $dateStart ?>" class="datepicker" />
    <label>Fin</label><input type="text" name="dateEnd" id="dateEnd" value="<?php echo $dateEnd ?>" class="datepicker" />
    <input type="submit" name="dateSelect" id="dateSelect" value="" class="dateSearch" />
    <input type="submit" name="excelDownload" id="dateSelect" value="" class="spreadsheet" />
  </form>
</div>
<hr/>
	<h2>Période du <span class="red" ><?php echo $dateStart ?></span> au <span class="red"><?php echo $dateEnd ?></span></h2>
<div id="resultsHistoPlot" ></div>
<hr/>
<ul id="resultList">
  <?php foreach($data['entries'] as $shopId => $shopData): ?>
    <li class="shop" id="result_shop_<?php echo $shopId ?>" > <h3><?php echo $shops[$shopId] ?></h3>
      <table class="shop" >
	<tr class="legend" >
	  <th class="date" >Date</th>
	  <th class="rowTotal" >Total</th>
	  <th class="cash" >Especes</th>
	  <th class="check" >Cheques</th>
	  <?php foreach($productTypes as $typeId => $typeName): ?>
	    <th><?php echo $typeName; ?></th>
	  <?php endforeach ?>
	</tr>
	<?php foreach($shopData['entries'] as $results): 
	   $date = new DateTime($results['date']);
	   ?>
	  <tr>
	    <td class="date" ><?php echo $date->format('d/m/Y'); ?></td>
	    <td class="total" ><?php echo ($results['cash'] + $results['check']); ?></td>
	    <td class="cash" ><?php echo $results['cash']; ?></td>
	    <td class="check" ><?php echo $results['check']; ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult" ><?php if(isset($results['productTypes'][$typeId])) { echo $results['productTypes'][$typeId]['result']; } ?></td>
	    <?php endforeach ?>
	  </tr>
	<?php endforeach ?>
	  <tr class="total" >
	    <td class="total" >Totaux</td>
	    <td class="total"><?php echo ($shopData['total']['cash'] + $shopData['total']['check']) ?></td>
	    <td class="cash"><?php echo $shopData['total']['cash'] ?></td>
	    <td class="check"><?php echo $shopData['total']['check'] ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult"><?php if(isset($shopData['total'][$typeId])) { echo $shopData['total'][$typeId]; } ?></td>
	    <?php endforeach ?>
	  </tr>
      </table>
    </li>
  <?php endforeach ?>
    <li>
      <h3>Totaux</h3>
      <table class="total" >
	<tr class="legend" >
	  <th class="date" ></th>
	  <th class="total" >Total</th>
	  <th class="cash" >Especes</th>
	  <th class="check" >Cheques</th>
	  <?php foreach($productTypes as $typeId => $typeName): ?>
	    <th class="productTypeResult" ><?php echo $typeName; ?></th>
	  <?php endforeach ?>
	</tr>
	<tr class="" >
	    <td class="date" >Totaux</td>
	    <td class="total" ><?php echo ($data['total']['cash'] + $data['total']['check']) ?></td>
	    <td class="cash" ><?php echo $data['total']['cash'] ?></td>
	    <td class="check" ><?php echo $data['total']['check'] ?></td>
	    <?php 
	      foreach($productTypes as $typeId => $typeName): ?>
	      <td class="productTypeResult" ><?php if(isset($data['total'][$typeId])) { echo $data['total'][$typeId]; } ?></td>
	    <?php endforeach ?>
	  </tr>
      </table>
    </li>
</ul>
<a href="<?php echo $this->webroot ?>results/add" >Saisie</a>
</div>
<script type="text/javascript">

function parseDate(input) {
  var parts = input.split('/');
  // new Date(year, month [, date [, hours[, minutes[, seconds[, ms]]]]])
  month = parts[1];
  if( month == 0 )
{
  month = 11;
}
else
{
  month--;
}
  return new Date(parts[2], month, parts[0]); // months are 0-based
}

   var histogramPlot;
     function isValidDate(d) {
              if ( Object.prototype.toString.call(d) !== "[object Date]" )
                return false;
              return !isNaN(d.getTime());
            }
            
   function histogram()
  {
    data = [];
    var tmpData = {};
    var cumulativeData = {};
    var lastSum = 0;
    $('li.shop').each(function(index, shopLi){
      row=0;
      var shopId = $($(shopLi)).attr('id').replace('result_shop_', '');
	  tmpData[shopId] = new Array();
	  $($(shopLi)).find('table tr').each(function(index, item){
	  if(row > 0) //skip header
	  {
	    if($(item).css('display') !== 'none')
	    {
	      key = $(item).find('.date').text();
	      dte = parseDate(key);
	      
	      if( isValidDate(dte) )
	      {
		//key = dte.getFullYear() + '-' + dte.getMonth() + '-' + dte.getDate();
		if(tmpData[shopId][dte] == undefined)
		{
		  tmpData[shopId][dte] = 0;
		}
		tmpData[shopId][dte] += parseInt($(item).find('.total').html());
	      }
	    }
	  }
	  row++;
	});
    });

    /*tmpData.sort(function(a,b){
    
      /*var c;
      var d;
      console.log("a");
      /*for(c in a) { break; }
      for(d in b) { break; }
      console.log(c);
      return 2-1;//c-d;
      });
    */
  
    for(shopId in tmpData) { 
      //console.log(tmpData[shopId]);
      /*var t = [];
      for(obj in tmpData[shopId]) { 
	t.push(obj)
	t.push(tmpData[shopId][obj])
      }
      console.log(t);*/
      data.push(tmpData[shopId]);
    }

    console.log(data);
    if(histogramPlot != undefined)
    {
      histogramPlot.destroy();
    }
    //console.log(data);
    histogramPlot= jQuery.jqplot ('resultsHistoPlot', data,
    {
      title: 'Histogram',
      seriesDefaults: {
        //renderer: $.jqplot.BarRenderer,
        rendererOptions: {
          fillToZero: true,
          barWidth:5,
        },
        pointLabels: { show: true }
      },
      height: 500,
      width: 600,
      axes:{
        xaxis:{
          label:'Date',
          renderer:$.jqplot.DateAxisRenderer,
          numberTicks:5,
          tickOptions: {
              formatString: '%b %y'
          }
        },
        yaxis:{
          label:'Count',
          pad: 1.05,
        }
      },
      cursor:{
              show: true,
              followMouse: true,
              zoom:true,
              height: 200,
              width: 300,
              showTooltip:true,
      }, 
      legend: {
              show: true,
              placement:'e'
      },
    }
    
    );
    
    return false;
  }
   
  $(document).ready(function(){
      histogram();
  });
</script>
