function isValidDate(d) {
	if ( Object.prototype.toString.call(d) !== "[object Date]" )
	  return false;
	return !isNaN(d.getTime());
      }
      
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

$(document).ready(function(){
    $('.hideJs').each(function(index, item){
	$(item).hide();
      });
      
    $('.showJs').each(function(index, item){
	$(item).show();
      });
    
    
      $.datepicker.setDefaults($.datepicker.regional[ 'fr' ]);
      $( ".datepicker" ).datepicker();
    
    
  setInterval(function(){  
         $(".slideshow ul").animate({marginLeft:-350},800,function(){  
            $(this).css({marginLeft:0}).find("li:last").after($(this).find("li:first"));  
         })  
      }, 3500);  
    
    $(".datetimepicker").datetimepicker();
  $('.spinner').spinner();
  
    $(".fancybox").fancybox(
        {
            type: "image",
            //autoDimensions  : true,
            //width             : 600,
            //height            : 'auto',
            showNavArrows: true,
	    beforeLoad: function() {
	      this.title = '<span class="fancybox-title">' + $(this.element).find('img').attr('alt') + '<span class="fancybox-title-count"> ( ' + (this.index + 1) + ' / ' + this.group.length + ' )</span>';
	    },
        }
          );
    
    
    
    
    
  }); 
