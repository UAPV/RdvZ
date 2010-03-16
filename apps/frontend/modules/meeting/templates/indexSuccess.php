<fieldset>
<form action="<?php echo url_for('meeting/search') ?>" method="post">
<input type="text" id="m_search" class="search" name="h" value="Entrez le code d'un sondage pour le visionner..." />
</form>
</fieldset>

<?php if(count($meeting_list)): ?>
<ul class="my_meetings">
  <?php setlocale(LC_TIME,'fr_FR.utf8','fra') ; ?>
  <?php $i = 0 ; ?>
  <?php foreach ($meeting_list as $meeting): ?>
  <li class="<?php echo $i++%2 ? 'even' : 'odd' ?>">
    <div class="meeting_delete_expires">
    <div>Fermeture automatique des votes le <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateEnd())) ?></strong>.</div>
    <div>Suppression automatique du sondage le <strong><?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateDel())) ?></strong>.</div>
    </div>
    <div class="meeting_name"><?php echo $meeting->getTitle() ?></div>
    <div class="meeting_code_div"><a href="<?php echo url_for('meeting/show?h='.$meeting->getHash()) ?>">Code du sondage : <span class="meeting_code"><?php echo $meeting->getHash() ?></span></a></div>
    <ul class="actions">
      <li><a class="no_border" href="<?php echo url_for('meeting/edit?id='.$meeting->getId()) ?>"><img class="icon_16" src="/images/book_16.png" alt="Modifier" /> Modifier</a></li>
      <li><a href="<?php echo url_for('meeting/csv?h='.$meeting->getHash()) ?>"><img class="icon_16" src="/images/page_table_chart_16.png" alt="Exporter csv" /> Exporter au format csv</a></li>
      <li><?php echo link_to('<img class="icon_16" src="/images/book_close_16.png" alt="Effacer" /> Effacer', 'meeting_delete', $meeting, array('method' => 'delete', 'confirm' => 'Voulez-vous vraiment supprimer ce rendez-vous?')) ?></li>
      <li><a href="<?php echo url_for('meeting/voteclose?h='.$meeting->getHash()) ?>"><?php echo $meeting->getClosed() ? '<img class="icon_16" src="/images/lock_16.png" alt="Rouvrir les votes" /> Rouvrir les votes' : '<img class="icon_16" src="/images/lock_open_16.png" alt="Clore les votes" /> Clore les votes' ?></a></li>
      </ul>
  </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
<div style="clear : both; height : 30px ;"></div>

  <a href="<?php echo url_for('meeting/new') ?>"><img class="icon_32" src="/images/book_add_32.png" alt="Nouveau rendez-vous"  /> Nouveau rendez-vous</a>
