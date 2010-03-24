<?php echo __('Dates').",".__('Commentaires').",".__('Votes') ;
echo "\r\n" ;
foreach($counts as $res)
  echo strftime("%d/%m/%Y", strtotime($res['date'])).','.$res['comment'].','.($res['count'] < 0 ? '-' : $res['count'])."\r\n" ;
?>
