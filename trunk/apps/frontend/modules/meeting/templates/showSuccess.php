<?php use_javascript('tools.tooltip-1.1.3.js') ?>
<?php use_javascript('jquery-ui-1.7.2.custom.min.js') ?>
<?php use_javascript('jquery.contextmenu.r2.js') ?>
<?php use_javascript('poll_comment') ?>
<?php use_stylesheet('jquery-ui-1.7.2.custom2.css') ?>
<?php 
  $user_is_creator = false ;
  if($sf_user->isAuthenticated()) 
    if($sf_user->getProfileVar(sfConfig::get('app_user_id')) == $meeting->getUid())
      $user_is_creator = true ;
?>

<?php if($user_is_creator): ?>
<div id="meet_disc_box">
<div id="url_meet_disc">Copiez/collez ce lien pour partager ce sondage avec vos collaborateurs !</div>
<input type="text" class="search url_meet" readonly="readonly" value="http://<?php echo sfConfig::get('app_url').url_for('auth/mh?m='.$meeting->getHash()) ?>" /></div>
<?php endif; ?>
<h2><img src="/images/book_bookmarks_32.png" alt="Disponibilités" class="icon_32" /> Quelles sont vos disponibilités?</h2>
<table>
  <tbody>
    <tr>
      <th>Titre : </th>
      <td><?php echo $meeting->getTitle() ?></td>
    </tr>
    <tr>
      <th>Description : </th>
      <td><?php echo $meeting->getDescription() ?></td>
    </tr>
    <tr>
      <th>Createur : </th>
      <td><?php $createur = Doctrine::getTable('user')->find($meeting->getUid()) ; echo $createur->getSurname().' '.$createur->getName() ?></td>
    </tr>
  </tbody>
</table>
<div id="tips">
<a href="#">Masquer</a>
<strong>A savoir</strong><br />
Vous pouvez ajouter un commentaire pour chacun des votes que vous avez validés en effectuant un clic droit sur la case correspondante.
</div>
Indiquez votre sélection en cliquant sur les cases à cocher. Utilisez ensuite le bouton "Voter" pour valider votre vote.
<div class="contextMenu" id="poll_menu">
  <ul>
    <li id="comm"><img src="/images/pencil_16.png" /> Commenter</li>
  </ul>
</div>
<br />
<br />
<!--<div id="comment" style="display:none">&nbsp;</div>-->
<div id="comment_form" style="display:none" title="Nouveau commentaire">
<form>
Commentaire : <input type="text" id="comm_input" size="50" name="comm" />
<input type="hidden" id="comm_poll_id" name="poll_id" />
<input type="submit" class="awesome blue large" value="Envoyer" />
</form>
</div>
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
        <td colspan="1" 
        <?php if($comment != ''): ?>
          class="poll_td"
        <?php endif; ?> >
          <?php echo $comment ?>
        </td>
    <?php endforeach; ?>
  </tr>


  <?php $i = 0 ; ?>
  <!-- Les lignes qui affichent les votes effectués... -->
  <?php foreach($votes as $user => $dts): ?>
  <tr>
    <?php $usr = Doctrine::getTable('user')->find($user) ; ?>
    <?php if($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
      <form action="<?php echo url_for('meeting/validvote?h='.$meeting->getHash()) ?>" method="post" name="edit_form"> 
    <?php endif; ?>
      
    <?php if(empty($usr)): ?>
      <td class="poll_td"><?php echo $user ?></td>
    <?php else: ?>
      <td class="poll_td"><img class="user_icon" src="/images/user_20.png" alt="Utilisateur authentifié" title="Utilisateur authentifié" /><?php echo $usr->getSurname().' '.$usr->getName() ?></td>
    <?php endif; ?>

    <?php foreach($dts as $did => $poll): ?>
      <td id="<?php echo $poll->getId() ?>" colspan="1" 
      <?php if($poll->getPoll() == 1000): ?>
        <?php echo "class='poll_td no_vote " ?>
      <?php else: ?>
        class=<?php echo "'".($poll->getPoll() ? 'ok' : 'not_ok' ) ?>
      <?php endif; ?>
      <?php if(($sf_user->hasCredential('member') || $sf_user->hasCredential('invite')) && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <?php echo ' to_comment ' ?>
      <?php endif; ?>
      <?php if($poll->getComment()): ?>
        <?php echo ' comment_present ' ?>
      <?php endif; ?>
      <?php echo " tt'" ?>
      >

      <span class="tooltip"><span class="top"></span><span class="middle">
      <?php if($poll->getPoll() == 1000): ?>
        Le créateur du sondage a ajouté une nouvelle date et vous n'avez pas encore voté, cliquez maintenant sur <u><em>Modifier mes votes</em></u> pour le faire ! 
      <?php else: ?>
        <?php echo (is_null($poll->getComment()) ? 'Aucun commentaire n\'a été entré pour ce vote.' : $poll->getComment()) ?> 
      <?php endif; ?>
      </span><span class="bottom"></span></span>


      <?php if($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <input type="checkbox" name="<?php echo $poll->getId() ?>" <?php echo ($poll->getPoll() ? 'checked' : '') ?> />
      <?php endif; ?>
      </td>
    <?php endforeach; ?>

    <?php if($sf_user->hasCredential('member') || $sf_user->hasCredential('invite')): ?>
      <?php if(is_null($sf_user->getAttribute('edit')) && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <td><a href="<?php echo url_for('meeting/editvote?h='.$meeting->getHash()) ?>">Modifier mes votes</a></td>
      <?php elseif ($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id'))|| $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <td><a href="#" onclick="document['edit_form'].submit()">Validez mes nouveaux votes</a></td>
      <?php endif; ?>
    <?php endif; ?>

    <?php if($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
      </form>
    <?php endif; ?>

    <?php if($user_is_creator): ?>
      <td> <form action="<?php echo url_for('meeting/razvote?h='.$meeting->getHash().'&u='.$user) ?>" method="post" name="raz_form<?php echo $i ?>"><a href="#" onclick="if(confirm('ATTENTION ! Vous allez supprimer tous les votes de cette personne.\nEtes-vous bien sûr de vouloir réaliser cette action?')) document['raz_form<?php echo $i++ ?>'].submit();"><img src="/images/close_16.png" alt="Remise à zéro des votes de cette personne" title="Remise à zéro des votes de cette personne" /></a></form></td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>

<?php if(!($sf_user->isAuthenticated() && ($votes->offsetExists($sf_user->getProfileVar(sfConfig::get('app_user_id'))) || $votes->offsetExists($sf_user->getAttribute('participant_name')))) && !$meeting->getClosed()): ?>
  <tr id="form">
<form action="<?php echo url_for('meeting/vote?h='.$meeting->getHash()) ?>" method="post" > 
    <?php if($sf_user->hasCredential('member')): ?>
      <?php $user = Doctrine::getTable('user')->find($sf_user->getProfileVar(sfConfig::get('app_user_id'))) ; ?>
      <td class="poll_td"><?php echo $user->getSurname().' '.$user->getName()  ?></td>
    <?php else: ?>
        <td class="poll_td"><input 
          <?php if ($sf_user->hasFlash('error')): ?>
           class="error_name" 
          <?php endif; ?>
          type="text" name="name" size="10" /></td>
    <?php endif; ?>
    <?php foreach($dates as $m => $days): ?>
      <?php foreach($days as $id => $d): ?>
        <td colspan="1" class="poll_td"><input type="checkbox" name="<?php echo $id ?>" size="10" /></td>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <td><input type="submit" class="awesome blue large" value="Voter" /></td>
</form>    
  </tr>
<?php endif; ?>
<tr>
<td class="poll_empty"></td>
<?php foreach($md as $d): ?>
  <?php if(in_array($d->getId(),$bests->getRawValue())): ?>
    <td colspan="1"><img src="/images/star_16.png" alt="Starred meeting" title="Meilleur choix" /></td>  
  <?php else: ?>
    <td class="poll_empty"></td>
  <?php endif; ?>
<?php endforeach; ?>
</tr>

<!--  <tr>
    <td class="poll_td">Commentaire (<em>optionnel</em>) : </td>
    <?php //foreach($dates as $m => $days): ?>
      <?php //foreach($days as $d): ?>
        <td colspan="1" class="poll_td"><input type="text" name="comment_<?php //echo $m.'_'.$d ?>" size="10" /></td>
      <?php //endforeach; ?>
    <?php //endforeach; ?>
  </tr> -->
  <?php //echo $form ?>
</table>

<table id="legende">
<tr><td class="ok legend" colspan="1"></td><td>Disponible</td></tr>
<!--<tr><td class="if_needed" colspan="1"></td><td>Disponible en cas de besoin</td></tr>-->
<tr><td class="not_ok legend" colspan="1"></td><td>Non disponible</td></tr>
</table>
