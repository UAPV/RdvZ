jQuery.fn.exists = function(){return jQuery(this).length>0;}

$(document).ready(function()
{
  $('#tips a').click(function()
  {
    $('#tips').slideUp() ;
  }); 

  $DEFAULT_SEARCH = "Entrez le code d'un sondage pour le visionner..." ;

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

  $("#form").find('input:checkbox').change(function()
  {
    $(this).parent().toggleClass('ok') ;
    $(this).parent().toggleClass('poll_td') ;
  }) ;

  $(".my_meetings").children('li').mouseenter(function()
  {
    $(this).children('.meeting_name').hide() ;
    $(this).children('.meeting_code_div').show() ;
    $(this).children('.actions').slideDown('fast') ;
  }) ;

  $(".my_meetings").children('li').mouseleave(function()
  {
    $(this).children('.actions').slideUp('fast') ;
    $(this).children('.meeting_name').show() ;
    $(this).children('.meeting_code_div').hide() ;
  }) ;
}) ;
