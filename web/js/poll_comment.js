$(document).ready(function()
{
  $('.ok[title], .not_ok[title]').tooltip(
  {
    tip:'#comment',
    position : 'top right',
    offset : [-5,-50],
    lazy : true,
    delay : 0
  }
    );

   $('.ok, .not_ok').contextMenu('poll_menu', {
      menuStyle: {
        width: '120px'
      },
      bindings: {
        'comm': function(t) {
          $('#comment_form').dialog() ;
        }
      }
    });
});
