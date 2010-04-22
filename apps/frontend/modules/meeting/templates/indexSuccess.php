<fieldset>
<form action="<?php echo url_for('meeting/search') ?>" method="post">
<input type="text" id="m_search" class="search" name="h" value="<?php echo __("Entrez le code d'un sondage pour le visionner...") ?>" />
</form>
</fieldset>
<div style="margin-top:-20px;"></div>
<?php $languages = sfConfig::get('app_languages') ; ?>
<?php if(count($meeting_list)): ?>
<h2 class='meeting_title'><?php echo image_tag('/images/book_bookmarks_32.png', array('class' => 'icon_32')).' '.__('Mes rendez-vous') ?></h2>
<ul class="my_meetings" style="margin-bottom:30px;">
  <?php setlocale(LC_TIME,$languages[$sf_user->getCulture()].'.utf8',$sf_user->getCulture()) ; // internationalization... ?>
  <?php $i = 0 ; ?>
  <?php foreach ($meeting_list as $meeting): ?>
  <li class="<?php echo $i++%2 ? 'even' : 'odd' ?>">
    <div class="meeting_delete_expires">

    <?php if(time() >= strtotime($meeting->getDateEnd()) || $meeting->getClosed()): ?>
    <div style="color : red; font-weight: bold;"><?php echo __('Votes clos') ?>.</div>
    <?php else: ?>
    <div><?php echo __('Fermeture automatique des votes le') ?> <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateEnd())) ?></strong>.</div>
    <?php endif ; ?>

    <div><?php echo __('Suppression automatique du sondage le') ?> <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateDel())) ?></strong>.</div>
    </div>
    <div class="meeting_name">
      <?php if(strlen($meeting->getTitle()) > 27): ?>
        <?php echo substr_replace($meeting->getTitle(), '...', 27, strlen($meeting->getTitle())) ; ?>
      <?php else: ?>
        <?php echo $meeting->getTitle() ; ?>
      <?php endif; ?>
    </div>

    <div class="meeting_code_div"><a href="<?php echo url_for('meeting/show?h='.$meeting->getHash()) ?>"><?php echo __('Code du sondage') ?> : <span class="meeting_code"><?php echo $meeting->getHash() ?></span></a></div>
    <ul class="actions">
      <li><a class="no_border" href="<?php echo url_for('meeting/edit?id='.$meeting->getId()) ?>"><img class="icon_16" src="<?php echo image_path('/images/book_16.png') ?>" alt="<?php echo __('Modifier') ?>" /> <?php echo __('Modifier') ?></a></li>
      <li><a href="<?php echo url_for('meeting/csv?h='.$meeting->getHash()) ?>"><img class="icon_16" src="<?php echo image_path('/images/page_table_chart_16.png') ?>" alt="Exporter csv" /> <?php echo __('Exporter au format csv') ?></a></li>
      <li><?php echo link_to('<img class="icon_16" src="'.image_path('/images/book_close_16.png').'" alt="Effacer" /> '.__('Effacer'), 'meeting_delete', $meeting, array('method' => 'delete', 'confirm' => __('Voulez-vous vraiment supprimer ce rendez-vous?'))) ?></li>
      <?php if(time() < strtotime($meeting->getDateEnd())): ?>
      <li><a href="<?php echo url_for('meeting/voteclose?h='.$meeting->getHash()) ?>"><?php echo $meeting->getClosed() ? '<img class="icon_16" src="'.image_path('/images/lock_16.png').'" alt="Rouvrir les votes" /> '.__('Rouvrir les votes') : '<img class="icon_16" src="'.image_path('/images/lock_open_16.png').'" alt="Clore les votes" /> '.__('Clore les votes') ?></a></li>
      <?php endif; ?>
    </ul>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if(count($followed_meeting_list)): ?>
<h2 class='meeting_title' style="clear:both;"><?php echo image_tag('/images/rss_32.png', array('class' => 'icon_32')).' '.__('Mes rendez-vous suivis') ?></h2>
<ul class='my_meetings'>
  <?php setlocale(LC_TIME,$languages[$sf_user->getCulture()].'.utf8',$sf_user->getCulture()) ; // internationalization... ?>
  <?php $i = 0 ; ?>
  <?php foreach ($followed_meeting_list as $meeting): ?>
  <li class="<?php echo $i++%2 ? 'even' : 'odd' ?>">
    <div class="meeting_delete_expires">

    <?php if(time() >= strtotime($meeting->getDateEnd()) || $meeting->getClosed()): ?>
    <div style="color : red; font-weight: bold;"><?php echo __('Votes clos') ?>.</div>
    <?php else: ?>
    <div><?php echo __('Fermeture automatique des votes le') ?> <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateEnd())) ?></strong>.</div>
    <?php endif ; ?>

    <div><?php echo __('Suppression automatique du sondage le') ?> <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateDel())) ?></strong>.</div>
    </div>
    <div class="meeting_name">
      <?php if(strlen($meeting->getTitle()) > 27): ?>
        <?php echo substr_replace($meeting->getTitle(), '...', 27, strlen($meeting->getTitle())) ; ?>
      <?php else: ?>
        <?php echo $meeting->getTitle() ; ?>
      <?php endif; ?>
    </div>

    <div class="meeting_code_div"><a href="<?php echo url_for('meeting/show?h='.$meeting->getHash()) ?>"><?php echo __('Code du sondage') ?> : <span class="meeting_code"><?php echo $meeting->getHash() ?></span></a></div>
    <ul class="actions">
      <li><a class="no_border" href="<?php echo url_for('meeting/csv?h='.$meeting->getHash()) ?>"><img class="icon_16" src="<?php echo image_path('/images/page_table_chart_16.png') ?>" alt="Exporter csv" /> <?php echo __('Exporter au format csv') ?></a></li>
      <li>
        <a href="<?php echo url_for('meeting/follow?h='.$meeting->getHash()) ?>"><img class="icon_16" src="<?php echo image_path('/images/rss_16.png') ?>" alt="Follow" /> <?php echo __('Stopper le suivi') ?></a>
      </li>
    </ul>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>

<div style="clear : both; height : 30px ;"></div>

  <a href="<?php echo url_for('meeting/new') ?>"><img class="icon_32" src="<?php echo image_path('/images/book_add_32.png') ?>" alt="<?php echo __('Nouveau rendez-vous') ?>"  /> <?php echo __('Nouveau rendez-vous') ?></a>
<script type="text/javascript">
  $DEFAULT_SEARCH = "<?php echo __("Entrez le code d'un sondage pour le visionner...") ?>" ;

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
</script>
