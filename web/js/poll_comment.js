
$(document).ready(function()
{
  $('#comment_form').dialog({
    autoOpen: false,
    width : 650,
    modal : true
    }) ;
   $(/*'.ok, .not_ok'*/'.to_comment').contextMenu('poll_menu', {
      menuStyle: {
        width: '120px'
      },
      bindings: {
        'comm': function(t) {
          $('#comm_input').val(jQuery.trim($(t).children('.tooltip').children('.middle').html())) ;
          $('#comment_form').dialog('open') ;
          $('#comm_input').focus() ;
          $('#comm_input').select() ;
          }
      },
      onShowMenu: function(el,menu) {
        var td ; 
        if (el.target.tagName == 'td')
          td = $(el.target) ;
        else  
          td = $(el.target).closest('td') ;

        $('#comm_poll_id').val(td.attr('id')) ;
        return menu ;
      }
    });

});
