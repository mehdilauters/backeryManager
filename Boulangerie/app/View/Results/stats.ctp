<div class="results index">
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />

<hr/>
<div id="histogramChart" ></div>
<hr/>

<table id="statValues">
  <tr class="legend" >
    <th class="date" >Date</th>
    <th class="day" >Jour</th>
    <th class="shop" >Magasin</th>
    <th class="rowTotal" >Total</th>
    <th class="cash" >Especes</th>
    <th class="check" >Cheques</th>
    <?php foreach($productTypes as $typeId => $typeName): ?>
      <th><?php echo $typeName; ?></th>
    <?php endforeach ?>
    <th class="comment" >Commentaire</th>
  </tr>
    <?php foreach($results as $result): 
     $date = new DateTime($result['Result']['date']);
     $total = $result['Result']['total'];
    ?>
    <tr>
      <td class="date" ><?php echo $date->format('d/m/Y'); ?></td>
      <td class="day" ><?php echo $this->Dates->getJourFr($date->format('w')); ?></td>
      <td class="shop" ><?php echo (strlen($result['Shop']['name']) > 13) ? substr($result['Shop']['name'],0,10).'...' : $result['Shop']['name']; ?></td>
      <td class="rowTotal"><?php echo $total ?></td>
      <td class="cash"><?php if($total != 0){ echo round($result['Result']['cash'] / $total *100, 2); } ?></td>
      <td class="check"><?php if($total != 0){ echo round($result['Result']['check'] / $total *100, 2); } ?></td>
      <?php foreach($productTypes as $typeId => $typeName): ?>
	<td>
	    <?php foreach($result['ResultsEntry'] as $resultEntry)
	      {
		if($resultEntry['product_types_id'] == $typeId)
		{
		  if($total != 0){ echo round($resultEntry['result']  / $total *100, 2); }
		  break;
		}
	      }
	      ?>
	</td>
      <?php endforeach ?>
      <td class="comment" ><?php echo $result['Result']['comment'] ?></td>
    </tr>
  <?php endforeach ?>
</table>
<script>
  var histogramPlot;
   function histogram()
  {
    data = [];
    var produced = [];
    var sold = [];
    var lost = [];
    var soldPrice = [];
    var lostPrice = [];
    
    row=0;
    $('#statValues tr').each(function(index, item){
      if(row > 0) //skip header
      {
        if($(item).css('display') !== 'none')
        {
          key = $(item).find('.date').text();
          dte = parseDate(key);
          
          if( isValidDate(dte) )
          {
            //key = dte.getFullYear() + '-' + dte.getMonth() + '-' + dte.getDate();
            var val = parseInt($(item).find('td.rowTotal').html());
            if(!isNaN(val))
            {
               if(produced[dte] == undefined )
               {
                  produced[dte] = 0;
               }
               produced[dte] += val;
             }
          }
        }
      }
      row++;
    });

    

    

    
    // object to array
    array = [];
    for (var x in produced ) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        array.push([key,produced[x]]);
      }
    }
    data.push(array);


    
  
    
    console.log(data);
    if(histogramPlot != undefined)
    {
      histogramPlot.destroy();
    }
    //console.log(data);
    histogramPlot= jQuery.jqplot ('histogramChart', data,
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
      series: [{'label':'Total'},{'label':'Pertes'},{'label':'Ventes'},{'label':'Revenus (€)'},{'label':'Pertes (€)'},],
      axes:{
        xaxis:{
          label:'Date',
          renderer:$.jqplot.DateAxisRenderer,
          numberTicks:5,
          tickOptions: {
               formatString: '%d/%m/%y'
          }
        },
        yaxis:{
          label:'Count',
          pad: 1.05,
        }
      },
       highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor:{
              show: true,
              followMouse: true,
              zoom:true,
              height: 200,
              width: 300,
              showTooltip:false,
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
      var tfConfig = { 
              base_path: '<?php echo $this->webroot ?>js/TableFilter/',
              rows_counter:true,
              //rows_counter_text: 'Selected files: ',
              on_after_refresh_counter: function(o,i){ histogram(); }
              };
              tf = new TF('statValues', tfConfig); tf.AddGrid();
              
              $('#histogramChart').bind("contextmenu",function(e){
                            histogramPlot.resetZoom();
                            return false;
            
                    });
    histogram();
  });
</script>
</div>