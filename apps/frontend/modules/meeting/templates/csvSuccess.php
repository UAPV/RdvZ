Dates,Commentaires,Votes<?php
echo "\r\n" ;
foreach($counts as $res)
  echo strftime("%d/%m/%Y", strtotime($res['date'])).','.$res['comment'].','.($res['count'] < 0 ? '-' : $res['count'])."\r\n" ;
?>
