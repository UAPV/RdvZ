jQuery.fn.exists = function(){return jQuery(this).length>0;}

$(document).ready(function()
{
  $('#tips a').click(function()
  {
    $('#tips').slideUp() ;
  }); 

  $DEFAULT_SEARCH = "Entrez le code d'un rendez-vous pour le visionner..." ;

  $("#m_search").bind("blur", function(event){
    if ($(event.target).val() == '') {
      $(event.target).val($DEFAULT_SEARCH);
    }
      
  });
  
  $("#m_search").bind("focus", function(event) {
    if ($(event.target).val() === $DEFAULT_SEARCH) {
      $(event.target).val('');
    }
  });

}) ;
