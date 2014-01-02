<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/rainbow.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />

<div id="histogramChart" style="width=500px;height=600px;" ></div>

<table id="statValues">
<tr>
  <th>id</th>
  <th>date</th>
  <th>product</th>
  <th>product type</th>
  <th>shop</th>
  <th>produced</th>
  <th>lost</th>
  <th>sold</th>
</tr>
<?php
   foreach($sales as $sale)
   {
      $class = 'lostProducts';
      if($sale['Sale']['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
   ?>
   <tr>
      <td><?php echo $sale['Sale']['id'] ?></td>
      <td class="date" ><?php echo $this->Time->format('d/m/Y',$sale['Sale']['date']); ?></td>
      <td><?php echo $sale['Product']['name'] ?></td>
      <td><?php echo $sale['Product']['product_types_id'] ?></td>
      <td><?php echo $sale['Shop']['name'] ?></td>
      <td><?php echo $sale['Sale']['produced'] ?></td>
      <td class="<?php echo $class ?>" ><?php echo $sale['Sale']['lost'] ?></td>
      <td><?php echo $sale['Sale']['sold'] ?></td>
   </tr>
   
   <?php }

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
    var tmpData = [];
    var cumulativeData = {};
    var lastSum = 0;
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
            if(tmpData[dte] == undefined)
          {
              tmpData[dte] = 0;
            }
            columnId=7;
            tmpData[dte] += parseInt($(item).find('td:nth-child('+columnId+')').html());
          }
        }
      }
      row++;
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

    array = [];
    lastSum = 0;
    
    // object to array
    for (var x in tmpData) {
      dte = new Date(x);
      if(isValidDate(dte))
      {
        key = dte.getFullYear() + '-' + parseInt(dte.getMonth()+1) + '-' + dte.getDate();
        //console.log(x);
        array.push([key,tmpData[x]]);
        
        lastSum += tmpData[x];
        
        if(cumulativeData[key] == undefined)
        {
          cumulativeData[key] = lastSum;
        }
        else
        {
          cumulativeData[key] += tmpData[x];
        }
        
      }
    }
    
    data.push(array);

      arrayCum = [];
    
    
    // object to array
    for (var x in cumulativeData) {
      arrayCum.push([x,cumulativeData[x]]);
    }
  //  data.push(arrayCum);
    
    //console.log(data);
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

