Un rendez-vous vous est proposé par <?php echo $user->getName().' '.$user->getSurname().' ('.$user->getMail().')' ; ?> : 
<?php echo $meeting->getTitle() ?>

Vous pouvez indiquer vos disponibilités en vous rendant à l'adresse suivante : http://<?php echo sfConfig::get('app_url').url_for('auth/mh?m='.$meeting->getHash()) ?> .

Le code de ce rendez-vous est : <?php echo $meeting->getHash() ?> ; notez le bien, il pourra vous servir à retrouver la page du sondage.

Votes disponibles jusqu'au <?php setlocale(LC_TIME,'fr_FR.utf8','fra') ; $end = $meeting->getDateEnd() ; echo strftime("%A %d %B %Y", strtotime($end)) ?>.

----
Ceci est un message automatique, veuillez ne pas y répondre.
