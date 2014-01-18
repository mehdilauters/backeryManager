<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.cursor.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/jqplot/plugins/jqplot.highlighter.min.js" type="text/javascript"></script>
<script language="javascript" src="<?php echo $this->webroot ?>js/rainbow.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->webroot ?>js/jqplot/jquery.jqplot.css" />

<div id="histogramChart" style="width=500px;height=600px;" ></div>
<?php
  $group = array('time' => '', 'product'=>'', 'shop'=>'');
  $fields = array('date'=>true, 'day'=>true, 'week'=> true, 'product' => true, 'productType'=>true, 'shop'=>true);
  if(isset($this->request->data['group']))
  {
    $group['time'] = $this->request->data['group']['time'];
    $group['product'] = $this->request->data['group']['product'];
    $group['shop'] = $this->request->data['group']['shop'];
  }
  if($group['product'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
      $fields['day'] = false;
      $fields['week'] = false;
    }
    if( $group['shop'] == '' )
    {
      $fields['shop'] = false;
    }
  }
  
  if($group['shop'] != '')
  {
    if( $group['time'] == '' )
    {
      $fields['date'] = false;
      $fields['day'] = false;
      $fields['week'] = false;
    }
    if( $group['product'] == '' )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
  }
  
  if($group['time'] != '')
  {
    if( $group['product'] == '' )
    {
      $fields['product'] = false;
      $fields['productType'] = false;
    }
    if( $group['shop'] == '' )
    {
      $fields['shop'] = false;
    }
  }
 ?>
<div>
  Grouper par
  <form method="POST" >
    <label>date</label>
    <select name="group[time]" >
      <option value="" ></option>
      <option value="day" <?php echo ($group['time'] == 'day') ? 'selected="selected"' : ''; ?>  >jour</option>
      <option value="week" <?php echo ($group['time'] == 'week') ? 'selected="selected"' : ''; ?>  >semaine</option>
      <option value="month" <?php echo ($group['time'] == 'month') ? 'selected="selected"' : ''; ?> >mois</option>
      <option value="year" <?php echo ($group['time'] == 'year') ? 'selected="selected"' : ''; ?> >année</option>
    </select>
    <label>Produit</label>
    <select name="group[product]" >
      <option value="" ></option>
      <option value="product" <?php echo ($group['product'] == 'product') ? 'selected="selected"' : ''; ?> >Produit</option>
    </select>
    <label>Magasin</label>
    <select name="group[shop]">
      <option value="" ></option>
      <option value="shop" <?php echo ($group['shop'] == 'shop') ? 'selected="selected"' : ''; ?> >magasin</option>
    </select>
    <input type="submit" />
  </form>
</div>
<table id="statValues">
<tr>
  <?php if($fields['date']) { ?><th>Date</th><?php } ?>
  <?php if($fields['day']) { ?><th class="day" >Jour</th><?php } ?>
  <?php if($fields['week']) { ?><th class="week" >Semaine</th><?php } ?>
  <?php if($fields['product']) { ?><th>Produit</th><?php } ?>
  <?php if($fields['productType']) { ?><th>Type de produit</th><?php } ?>
  <?php if($fields['shop']) { ?><th>Magasin</th><?php } ?>
  <th>Production</th>
  <th>Perte</th>
  <th>Vente</th>
  <th>Prix (€)</th>
  <th>Perte (€)</th>
  <th>Commentaires</th>
</tr>
<?php
  $i = 0;
   foreach($sales as $sale)
   {
      $rowClass = 'even';
      $class = 'lostProducts';
      if($sale[0]['lost'] <= 0 )
      {
        $class = 'notLostProducts';    
      }
      
      if($i % 2 != 0)
      {
          $rowClass = 'odd';
      }
   ?>
      <tr class="<?php echo $rowClass ?>" >
      <?php if($fields['date']) { ?><td class="date" ><?php echo $this->Time->format('d/m/Y',$sale['Sale']['date']); ?></td><?php } ?>
      <?php if($fields['day']) { ?><td class="day" ><?php echo $this->Dates->getJourFr(date('w',$this->Time->fromString($sale['Sale']['date']) )); ?></td><?php } ?>
      <?php if($fields['week']) { ?><td class="week" ><?php echo date('W',$this->Time->fromString($sale['Sale']['date']) ); ?></td><?php } ?>
      <?php if($fields['product']) { ?><td class="productName"><?php echo $products[$sale['Sale']['product_id']]['Product']['name'] ?></td><?php } ?>
      <?php if($fields['productType']) { ?><td class="productTypeName"><?php echo $products[$sale['Sale']['product_id']]['ProductType']['name'] ?></td><?php } ?>
      <?php if($fields['shop']) { ?><td class="shopName"><?php echo $shops[$sale['Sale']['shop_id']] ?></td><?php } ?>
      <td class="produced" ><?php echo $sale[0]['produced'] ?></td>
      <td class="<?php echo $class ?> lost" ><?php echo $sale[0]['lost'] ?></td>
      <td class="sold"><?php echo $sale[0]['sold'] ?></td>
      <td class="totalPrice"><?php echo round($sale[0]['totalPrice'],2) ?></td>
      <td class="totalLost"><?php echo round($sale[0]['totalLost'],2) ?></td>
      <td class="totalLost"><?php echo $sale['Sale']['comment'] ?></td>
   </tr>
   
   <?php
         $i++;
      }

?>
</table>
   
<script type="text/javascript">

   var histogramPlot;

   
   function shuffle(o){ //v1.0
    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
  };
            
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
    
  
    
    //console.log(data);
    
    var rainbow = new Rainbow();
    //rainbow.setSpectrum('#303030', '#B8B8B8');
    var nbColors = 5;
    rainbow.setNumberRange(1, nbColors);
    var seriesColors =  new Array();
     for(var i = 0; i < nbColors; i++) {
          seriesColors[i] = "#"+rainbow.colourAt(i);
     }
          
     seriesColors = shuffle(seriesColors);
    
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
      series: [{'label':'Production'},{'label':'Ventes'},{'label':'Pertes'},{'label':'Revenus (€)'},{'label':'Pertes (€)'},],
      seriesColors: seriesColors,
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

