/*var markers = [
	{}
	];
*/
function curveDisplay(chartId, curveId, status)
	{
		var chartVarName = chartId + '_chartPlot';
		var chart = window[chartVarName];
		if(status == undefined)
		{
			status = !chart.series[curveId].show;
		}
		chart.series[curveId].show = status;
		chart.replot();
	}
  
   function histogram(tableId, chartId)
  {
    data = {};
	data['plotData'] = {};
	data['labels'] = {};
  
   

    row=0;
    $('#'+tableId+' tr.plot').each(function(index, item){
      if(row > 0) //skip header
      {
		
        if($(item).css('display') !== 'none')
        {
          key = $(item).find('.date').text();
          dte = parseDate(key);
          
          if( isValidDate(dte) )
          {
            //key = dte.getFullYear() + '-' + dte.getMonth() + '-' + dte.getDate();
			// for each item with the curve classe
			$(item).find('td').each(function( index ) {
				var classNames = $(this).attr('class');
				if(classNames != undefined)
				{
					classes = classNames.split(' ');
					var pattern = /^curve_(\S*)$/
					// foreach classes
					var nbClasses = classes.length;
					for (var x=0; x < nbClasses; x++ ) {
						var res = classes[x].match(pattern);
						if(res != null )
						{
							var y = res[1];
							if(data['plotData'][y] == undefined)
							{
								data['plotData'][y] = {};
								htmlLabel = $(item).find('td.label_'+classes[x]).html();
								if( htmlLabel != undefined)
								{
									data['labels'][y] = htmlLabel;
								}
							}
							if(data['plotData'][y][dte] == undefined)
							{
								data['plotData'][y][dte] = 0;
							}
							data['plotData'][y][dte] += parseInt($(this).html());
						}
					} // foreach classes
				}
			}); // foreach find
          }
        }
      }
	  else
	  {
		$(item).find('th').each(function( index ) {
			var classNames = $(this).attr('class');
				if(classNames != undefined)
				{
					classes = classNames.split(' ');
					var pattern = /^label_curve_(\S*)$/
					// foreach classes
					var nbClasses = classes.length;
					for (var x=0; x < nbClasses; x++ ) {
						var res = classes[x].match(pattern);
						if(res != null )
						{
							var y = res[1];
							data['labels'][y] = $(this).html();
						}
					}
				}
		});
	  }
      row++;
    });

	

    plotData = [];
	var seriesLabel = [];
	for (var x in data['plotData'] ) {
		var tmpData = [];
		for(y in data['plotData'][x])
		{
			tmpData.push([y, data['plotData'][x][y]]);
		}
		seriesLabel.push({'label':data['labels'][x]});
		plotData.push(tmpData);
	}
	//console.log(seriesLabel);

  
  
    
	
    var chartVarName = chartId + '_chartPlot';
    if(window[chartVarName]  != undefined)
    {
      window[chartVarName].destroy();
    }
    //console.log(dataShop);
    window[chartVarName] = jQuery.jqplot (chartId, plotData,
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
      series: seriesLabel,
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
		  min:0,
          pad: 1.05,
        }
      },
       highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
      cursor:{
	      showVerticalLine:true,
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
	   canvasOverlay: {
                show: true,
                objects:

                        [
                            {
                                verticalLine:
                                {
									name:'test',
                                    x : new Date('2014-01-05').getTime(),
                                    //stop : [new Date('2014-01-06').getTime(),1],
                                    lineWidth: 5,
                                    color: 'rgba(255, 0, 0,0.45)',
                                    shadow: false,
                                    lineCap : 'butt'
                                }
                            },                                    
                        /*    {
                                line:
                                {
                                    start : [new Date('2014-01-12').getTime(),20],                                                
                                    stop : [new Date('2014-01-12').getTime(),20],                                                
                                    lineWidth: 1000,
                                    color: 'rgba(255, 0, 0,0.45)',
                                    shadow: false,
                                    lineCap : 'butt'
                                }
                            }*/
                        ]
            } 
    }
    
    );
	
	html = '<ul>';
	for(var x in window[chartVarName].series)
	{
		label = window[chartVarName].series[x]['label'];
		$('#'+chartId).parent().find('.control').html('');
		
		if( label != undefined )
		{
			
			html += '<li><label>'+label+'</label><input type="checkbox" checked="checked" onchange="curveDisplay(\''+chartId+'\', '+x+')" /></li>';
		}
	}
	html += '<ul>';
	$('#'+chartId).parent().find('.control').html(html)
	
    
    return false;
  }