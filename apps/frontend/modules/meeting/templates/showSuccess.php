<h2><img src="/images/book_bookmarks_32.png" alt="Disponibilités" class="icon_32" /> Quelles sont vos disponibilités?</h2>
<table>
  <tbody>
    <tr>
      <th>Titre : </th>
      <td><?php echo $meeting->getTitle() ?></td>
    </tr>
    <tr>
      <th>Description : </th>
      <td><?php echo $meeting->getdescription() ?></td>
    </tr>
    <tr>
      <th>Createur : </th>
      <td><?php $createur = Doctrine::getTable('user')->find($meeting->getUid()) ; echo $createur->getSurname().' '.$createur->getName() ?></td>
    </tr>
  </tbody>
</table>
<br />
Indiquez votre sélection en cliquant sur les cases à cocher. Utilisez ensuite le bouton "Participer" pour valider votre vote.
<br />
<br />
<?php
  $dates = array() ;
  $months = array() ;
  $comments = array() ;
  foreach($meeting_dates as $d)
  {
    $f = strtotime($d->getDate()) ;
    $months[] = strftime("%B %Y",$f) ;
    $dates[strftime("%B %Y", $f)][] = strftime("%a %d", $f) ;
    if ($d->getComment() != '')
      $comments[] = $d->getComment() ;
  }
  $months = array_unique($months) ;
?>
<table id="poll">
  <tr class="poll_months">
  <!-- La ligne qui affiche les mois... -->
    <td class="poll_empty"></td>
    <?php foreach($months as $m): ?>
      <td class="poll_month" colspan="<?php echo count($dates[$m]) ?>"><?php echo $m ?></td>
    <?php endforeach; ?>
  </tr>
  <tr>
  <!-- La ligne qui affiche les jours... -->
    <td class="poll_empty"></td>
    <?php foreach($dates as $m => $days): ?>
      <?php foreach($days as $d): ?>
        <td colspan="1" class="poll_td">
        <?php echo $d ?>
        </td>
      <?php endforeach; ?>
    <?php endforeach; ?>
  </tr>
  <tr class="poll_comments">
  <!-- La ligne qui affiche les commentaires... -->
    <td class="poll_empty"></td>
    <?php foreach($comments as $comment): ?>
      <td colspan="1" class="poll_td">
        <?php echo $comment ?>
      </td>
    <?php endforeach; ?>
  </tr>
  <tr>
  <!-- La ligne qui affiche les votes déjà effectués... -->
  </tr>
  <?php echo $form ?>
</table>

<table id="legende">
<tr><td class="ok" colspan="1"></td><td>Disponible</td></tr>
<tr><td class="if_needed" colspan="1"></td><td>Disponible en cas de besoin</td></tr>
<tr><td class="not_ok" colspan="1"></td><td>Non disponible</td></tr>
</table>
