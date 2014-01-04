<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<!--<script language="javascript" src="<?php echo $this->webroot ?>js/rainbow.js" type="text/javascript"></script>--!>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />

<div id="histogramChart" style="width=500px;height=600px;" ></div>

<table id="statValues">
<tr>
  <th>Date</th>
  <th>Produit</th>
  <th>Type de produit</th>
  <th>Magasin</th>
  <th>Production</th>
  <th>Perte</th>
  <th>Vente</th>
  <th>Prix (€)</th>
  <th>Perte (€)</th>
</tr>
<?php
  $i = 0;
   foreach($sales as $sale)
   {
      $rowClass = 'even';
      $class = 'lostProducts';
      if($sale['Sale']['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
      
      if($i % 2 != 0)
      {
          $rowClass = 'odd';
      }
   ?>
   <tr id="sale_row_<?php echo $sale['Sale']['id'] ?>" class="<?php echo $rowClass ?>" >
      <td class="date" ><?php echo $this->Time->format('d/m/Y',$sale['Sale']['date']); ?></td>
      <td class="productName"><?php echo $sale['Product']['name'] ?></td>
      <td class="productTypeName"><?php echo $sale['Product']['ProductType']['name'] ?></td>
      <td class="shopName"><?php echo (strlen($sale['Shop']['name']) > 13) ? substr($sale['Shop']['name'],0,10).'...' : $sale['Shop']['name'] ?></td>
      <td class="produced" ><?php echo $sale['Sale']['produced'] ?></td>
      <td class="<?php echo $class ?> lost" ><?php echo $sale['Sale']['lost'] ?></td>
      <td class="sold"><?php echo $sale['Sale']['sold'] ?></td>
      <td class="totalPrice"><?php echo round($sale['Sale']['totalPrice'],2) ?></td>
      <td class="totalLost"><?php echo round($sale['Sale']['lost']*$sale['Sale']['price'],2) ?></td>
   </tr>
   
   <?php
         $i++;
      }

?>
</table>
   
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
            var val = parseInt($(item).find('td.produced').html());
            if(!isNaN(val))
            {
               if(produced[dte] == undefined )
               {
                  produced[dte] = 0;
               }
               produced[dte] += val;
             }
             var val = parseInt($(item).find('td.lost').html());
            if(!isNaN(val))
            {
               if(lost[dte] == undefined )
               {
                  lost[dte] = 0;
               }
               lost[dte] += val;
             }
             var val = parseInt($(item).find('td.sold').html());
            if(!isNaN(val))
            {
               if(sold[dte] == undefined )
               {
                  sold[dte] = 0;
               }
               sold[dte] += val;
             }
             var val = parseInt($(item).find('td.totalLost').html());
            if(!isNaN(val))
            {
               if(lostPrice[dte] == undefined )
               {
                  lostPrice[dte] = 0;
               }
               lostPrice[dte] += val;
             }
            var val = parseInt($(item).find('td.totalPrice').html());
            if(!isNaN(val))
            {
               if(soldPrice[dte] == undefined )
               {
                  soldPrice[dte] = 0;
               }
               soldPrice[dte] += val;
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

        array = [];
    for (var x in sold) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        array.push([key,sold[x]]);
      }
    }
    data.push(array);

        array = [];
    for (var x in lost) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        array.push([key,lost[x]]);
      }
    }
    data.push(array);
    
        array = [];
    for (var x in soldPrice ) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        array.push([key,soldPrice[x]]);
      }
    }
    data.push(array);
    
        array = [];
    for (var x in lostPrice) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        array.push([key,lostPrice[x]]);
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
      series: [{'label':'Production'},{'label':'Pertes'},{'label':'Ventes'},{'label':'Revenus (€)'},{'label':'Pertes (€)'},],
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
      histogram();
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
  });
</script>

