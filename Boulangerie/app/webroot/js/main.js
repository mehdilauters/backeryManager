

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
