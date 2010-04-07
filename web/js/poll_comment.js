
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
          }
      },
      onShowMenu: function(el,menu) {
      $('#comm_poll_id').val($(el.target).attr('id')) ;
      return menu ;
      }
    });

});
