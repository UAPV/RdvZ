Vous recevez ce message car vous avez souhaité être informé par mail des évolutions des votes pour votre rendez-vous : <?php echo $meeting->getTitle() ?>.
<?php echo $uname ?> a entré ses votes pour ce sondage.

Vous pouvez consulter les nouveaux votes en vous rendant à l'adresse suivante : http://<?php echo sfConfig::get('app_url').url_for('meeting/show?h='.$meeting->getHash()) ?> .

----
Ceci est un message automatique, veuillez ne pas y répondre.
