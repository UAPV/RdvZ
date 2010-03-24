<fieldset>
<form action="<?php echo url_for('meeting/search') ?>" method="post">
<input type="text" id="m_search" class="search" name="h" value="<?php echo __("Entrez le code d'un sondage pour le visionner...") ?>" />
</form>
</fieldset>

<?php if(count($meeting_list)): ?>
<ul class="my_meetings">
  <?php setlocale(LC_TIME,'fr_FR.utf8','fra') ; // internationalization... ?>
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
      <li><a class="no_border" href="<?php echo url_for('meeting/edit?id='.$meeting->getId()) ?>"><img class="icon_16" src="/images/book_16.png" alt="<?php echo __('Modifier') ?>" /> <?php echo __('Modifier') ?></a></li>
      <li><a href="<?php echo url_for('meeting/csv?h='.$meeting->getHash()) ?>"><img class="icon_16" src="/images/page_table_chart_16.png" alt="Exporter csv" /> <?php echo __('Exporter au format csv') ?></a></li>
      <li><?php echo link_to('<img class="icon_16" src="/images/book_close_16.png" alt="Effacer" /> '.__('Effacer'), 'meeting_delete', $meeting, array('method' => 'delete', 'confirm' => __('Voulez-vous vraiment supprimer ce rendez-vous?'))) ?></li>
      <li><a href="<?php echo url_for('meeting/voteclose?h='.$meeting->getHash()) ?>"><?php echo $meeting->getClosed() ? '<img class="icon_16" src="/images/lock_16.png" alt="Rouvrir les votes" /> '.__('Rouvrir les votes') : '<img class="icon_16" src="/images/lock_open_16.png" alt="Clore les votes" /> '.__('Clore les votes') ?></a></li>
      </ul>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<div style="clear : both; height : 30px ;"></div>

  <a href="<?php echo url_for('meeting/new') ?>"><img class="icon_32" src="/images/book_add_32.png" alt="<?php echo __('Nouveau rendez-vous') ?>"  /> <?php echo __('Nouveau rendez-vous') ?></a>
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
