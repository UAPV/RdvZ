jQuery.fn.exists = function(){return jQuery(this).length>0;}

$(document).ready(function()
{
  $('#tips a').click(function()
  {
    $('#tips').slideUp() ;
  }); 


  $("#form").find('input:checkbox').change(function()
  {
    $(this).parent().toggleClass('ok') ;
    $(this).parent().toggleClass('poll_td') ;
  }) ;

  $(".my_meetings").children('li').mouseenter(function()
  {
    $('.meeting_name', this).hide() ;
    $('.meeting_code_div', this).show() ;
    $('.actions', this).slideDown('fast') ;
  }) ;

  $(".my_meetings").children('li').mouseleave(function()
  {
    $('.actions', this).slideUp('fast') ;
    $('.meeting_name', this).show() ;
    $('.meeting_code_div', this).hide() ;
  }) ;

// A modifier, ça rame pour le moment.
//  $('form').nextAll('td').click(function () { $('input:checkbox', this).click(); });

  $("#follow_link").click(function()
  {
    img = $(this).children('img') ;
    if(img.hasClass('followed'))
      $(this).html(not_followed_image) ;
    else if(img.hasClass('not_followed'))
      $(this).html(followed_image) ;
  });
}) ;
