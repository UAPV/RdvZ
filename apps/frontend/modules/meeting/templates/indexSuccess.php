<h4>Mes rendez-vous</h4>

<table class="my_meetings">
  <thead>
    <tr>
      <th class="first">Code</th>
      <th>Titre</th>
      <th class="last">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php setlocale(LC_TIME,'fr_FR.utf8','fra') ; ?>
    <?php $i = 0 ; ?>
    <?php foreach ($meeting_list as $meeting): ?>
    <tr class="<?php echo $i++%2 ? 'even' : 'odd' ?>">
      <td><?php echo $meeting->getHash() ?></td>
      <td><div><a href="<?php echo url_for('meeting/show?h='.$meeting->getHash()) ?>"><?php echo $meeting->getTitle() ?></a></div>
      <div>Fermeture automatique des votes le <?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateEnd())) ?>.</div>
      <div>Suppression automatique du sondage le <?php echo strftime("%a %d %b %Y", strtotime($meeting->getDateDel())) ?>.</div>
      </td>
      <td>
      <div><a href="<?php echo url_for('meeting/edit?id='.$meeting->getId()) ?>"><img class="icon_16" src="/images/book_16.png" alt="Modifier" /> Modifier</a></div>
      <div><a href="#"><img class="icon_16" src="/images/page_table_chart_16.png" alt="Exporter csv" /> Exporter au format csv</a></div>
      <div><?php echo link_to('<img class="icon_16" src="/images/book_close_16.png" alt="Effacer" /> Effacer', 'meeting_delete', $meeting, array('method' => 'delete', 'confirm' => 'Voulez-vous vraiment supprimer ce rendez-vous?')) ?></div>
      <div><a href="<?php echo url_for('meeting/voteclose?id='.$meeting->getId()) ?>"><?php echo $meeting->getClosed() ? '<img class="icon_16" src="/images/lock_16.png" alt="Rouvrir les votes" /> Rouvrir les votes' : '<img class="icon_16" src="/images/lock_open_16.png" alt="Clore les votes" /> Clore les votes' ?></a></div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('meeting/new') ?>"><img class="icon_32" src="/images/book_add_32.png" alt="Nouveau rendez-vous"  /> Nouveau rendez-vous</a>
