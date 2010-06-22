<?php use_helper('jQuery') ?>
<?php 
  $user_is_creator = false ;
  if($sf_user->isAuthenticated()) 
    if($sf_user->getProfileVar(sfConfig::get('app_user_id')) == $meeting->getUid())
      $user_is_creator = true ;
?>

<?php if($user_is_creator): ?>
<div id="meet_disc_box">
<div id="url_meet_disc"><?php echo __('Copiez/collez ce lien pour partager ce sondage avec vos collaborateurs') ?> !</div>
<input type="text" class="search url_meet" readonly="readonly" value="http://<?php echo sfConfig::get('app_url').url_for('auth/mh?m='.$meeting->getHash()) ?>" /></div>
<?php endif; ?>
<!--<h2><img src="<?php echo image_path('/images/book_bookmarks_32.png') ?>" alt="Disponibilités" class="icon_32" /> <?php echo __('Quelles sont vos disponibilités') ?>?
</h2>-->
<div id="meeting_infos">
  <span id="meeting_title"><?php echo $meeting->getTitle() ?></span> <span id="meeting_author">(<?php $createur = Doctrine::getTable('user')->find($meeting->getUid()) ; echo $createur->getSurname().' '.$createur->getName() ?>)</span>
<?php if($sf_user->hasCredential('member')): ?>
  <?php echo jq_link_to_remote (
        $meeting->isFollowedBy($sf_user->getProfileVar(sfConfig::get('app_user_id'))) 
        ? image_tag('/images/rss_32.png', array('title' => __('Stopper le suivi'), 'class' => 'icon_32 followed')) 
        : image_tag('/images/rss_32_bw.png', array('title' => __('Suivre ce rendez-vous'), 'class' => 'icon_32 not_followed')), 
        array( 'url' => url_for('meeting/follow?h='.$meeting->getHash())), 
        array('id' => 'follow_link')) 
  ?>
<?php endif; ?>
  <div id="meeting_desc">
    <?php echo nl2br($meeting->getDescription()) ?>
  </div>
</div>      
<?php echo __('Indiquez votre sélection en cliquant sur les cases à cocher').'. '.__('Utilisez ensuite le bouton "Voter" pour valider votre vote') ?>.
<br />
<div class="contextMenu" id="poll_menu">
  <ul>
    <li id="comm"><img src="<?php echo image_path('/images/pencil_16.png') ?>" /> <?php echo __('Commenter') ?></li>
  </ul>
</div>
<br />
<br />
<!--<div id="comment" style="display:none">&nbsp;</div>-->
<div id="comment_form" style="display:none" title="<?php echo __('Nouveau commentaire') ?>">
<form>
<?php echo __('Commentaire') ?> : <input type="text" id="comm_input" size="50" name="comm" />
<input type="hidden" id="comm_poll_id" name="poll_id" />
<input type="submit" class="awesome blue large" value="<?php echo __('Envoyer') ?>" />
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
      <td class="poll_td"><img class="user_icon" src="<?php echo image_path('/images/user_20.png') ?>" alt="Utilisateur authentifié" title="<?php echo __('Utilisateur authentifié') ?>" /><?php echo $usr->getSurname().' '.$usr->getName() ?></td>
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
        <?php echo __("Le créateur du sondage a ajouté une nouvelle date et vous n'avez pas encore voté, cliquez maintenant sur") ?> <u><em><?php echo __("Modifier mes votes") ?></em></u> <?php echo __('pour le faire') ?> ! 
      <?php else: ?>
        <?php echo (is_null($poll->getComment()) ? __("Aucun commentaire n'a été entré pour ce vote.") : $poll->getComment()) ?> 
      <?php endif; ?>
      </span><span class="bottom"></span></span>


      <?php if($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <input type="checkbox" name="<?php echo $poll->getId() ?>" <?php echo ($poll->getPoll() ? 'checked' : '') ?> />
      <?php endif; ?>
      </td>
    <?php endforeach; ?>

    <?php if($sf_user->hasCredential('member') || $sf_user->hasCredential('invite')): ?>
      <?php if(is_null($sf_user->getAttribute('edit')) && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <td><a class="awesome blue large" href="<?php echo url_for('meeting/editvote?h='.$meeting->getHash()) ?>"><?php echo __('Modifier mes votes') ?></a></td>
      <?php elseif ($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id'))|| $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
        <td><a class="awesome blue large" href="#" onclick="document['edit_form'].submit()"><?php echo __('Validez mes nouveaux votes') ?></a></td>
      <?php endif; ?>
    <?php endif; ?>

    <?php if($sf_user->getAttribute('edit') && ($user == $sf_user->getProfileVar(sfConfig::get('app_user_id')) || $user == $sf_user->getAttribute('participant_name')) && !$meeting->getClosed()): ?>
      </form>
    <?php endif; ?>

    <?php if($user_is_creator): ?>
      <td> <form action="<?php echo url_for('meeting/razvote?h='.$meeting->getHash().'&u='.$user) ?>" method="post" name="raz_form<?php echo $i ?>"><a href="#" onclick="if(confirm('<?php echo __('ATTENTION') ?> ! <?php echo __('Vous allez supprimer tous les votes de cette personne') ?>.\n <?php echo __('Etes-vous bien sûr de vouloir réaliser cette action?') ?>')) document['raz_form<?php echo $i++ ?>'].submit();"><img src="<?php echo image_path('/images/close_16.png') ?>" alt="Remise à zéro des votes de cette personne" title="<?php echo __('Remise à zéro des votes de cette personne') ?>" /></a></form></td>
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
    <td><input type="submit" class="awesome blue large" value="<?php echo __('Voter') ?>" /></td>
</form>    
  </tr>
<?php endif; ?>
<tr>
<td class="poll_empty"></td>
<?php foreach($md as $d): ?>
  <?php if(isset($counts[$d->getId()])): ?>     
    <td class="poll_count 
    <?php $prct = $counts[$d->getId()]*100/$counts['max'] ?>
    <?php if($prct <= 25): ?>
      low
    <?php elseif($prct <= 50): ?>
      mid_low
    <?php elseif($prct <= 75): ?>
      mid_high
    <?php else: ?>
      high
    <?php endif ?>"><?php echo $counts[$d->getId()] ?>
    <?php if($counts[$d->getId()] == $counts['max']): ?>
  <img class="best" src="<?php echo image_path('/images/star_16.png') ?>" alt="Starred meeting" title="<?php echo __('Meilleur choix') ?>" />
    <?php endif ?>
    </td>
  <?php endif ?>
<?php endforeach; ?>
</tr>
<!--
<tr>
<td class="poll_empty"></td>
<?php foreach($md as $d): ?>
  <?php if(in_array($d->getId(),$bests->getRawValue())): ?>
    <td colspan="1"><img src="<?php echo image_path('/images/star_16.png') ?>" alt="Starred meeting" title="<?php echo __('Meilleur choix') ?>" /></td>  
  <?php else: ?>
    <td class="poll_empty"></td>
  <?php endif; ?>
<?php endforeach; ?>
</tr>
-->
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
<tr><td class="ok legend" colspan="1"></td><td><?php echo __('Disponible') ?></td></tr>
<!--<tr><td class="if_needed" colspan="1"></td><td>Disponible en cas de besoin</td></tr>-->
<tr><td class="not_ok legend" colspan="1"></td><td><?php echo __('Non disponible') ?></td></tr>
</table>

<script type="text/javascript">
var followed_image = '<?php echo image_tag('/images/rss_32.png', array('title' => __('Stopper le suivi'), 'class' => 'icon_32 followed')) ?>' ;
var not_followed_image = '<?php echo image_tag('/images/rss_32_bw.png', array('title' => __('Suivre ce rendez-vous'), 'class' => 'icon_32 not_followed')) ?>' ;
</script>
