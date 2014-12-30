function areCookiesEnabled()
{
  if (navigator.cookieEnabled) return true;

  // set and read cookie
  document.cookie = "cookietest=1";
  var ret = document.cookie.indexOf("cookietest=") != -1;

  // delete cookie
  document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";

  return ret;
}

    function imageUpdate()
    {
	$('.formPhotoPreview').each(function (){
	    var inputId = $(this).attr('id');
	    $('#'+inputId+'_preview').attr('src', webroot+ 'photos/download/'+ $('#'+ inputId).val() );    
	  });
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
//   $('.spinner').spinner();
  
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
    $("#failFlash, #flashWarning").dialog({
	modal: true,
	/*	buttons: [
					{
						text: "OK",
						click: function() {
						$( this ).dialog( "close" );
							}
					}
				]*/
			});
    
    
    
    $('.formPhotoPreview').change(function() {
	  imageUpdate();
	});
	imageUpdate();
    
    if(!areCookiesEnabled())
    {
     $('#cookiesError').show();
    }
    
    
    
            $('.uniqueUserWatch').keyup(function(){
            name = $('#UserName').val();
            email = $('#UserEmail').val();
            id = $('#UserId').val();
            jQuery.ajax({
              type: 'POST',
              url: webroot + 'users/isUnique.json',
//               async:false,
              accepts: 'application/json',
              data: {User:{
                        name: name,
                        email: email,
                        id:id,
                      }},
              dataType: 'json',
              success: function (data) {

                  if(data.results.unique)
                  {
                    $('.uniqueUserWatch').removeClass("error");
                  }
                  else
                  {
                    $('.uniqueUserWatch').addClass("error");
                    ret =  false;
                  }
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  console.log(textStatus);
                  $('.uniqueUserWatch').addClass("error");
                  ret =  false;
              }
          });
        });
    
  }); 
