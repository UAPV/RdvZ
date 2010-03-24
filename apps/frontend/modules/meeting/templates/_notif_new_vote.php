<?php echo __('Vous recevez ce message car vous avez souhaité être informé par mail des évolutions des votes pour votre rendez-vous')." : ".$meeting->getTitle() ?>.
<?php echo $uname.' '.__('a entré ses votes pour ce sondage').?>

<?php echo __("Vous pouvez consulter les nouveaux votes en vous rendant à l'adresse suivante") ?> : http://<?php echo sfConfig::get('app_url').'/'.$meeting->getHash() ?> .

----
<?php echo __('Ceci est un message automatique, veuillez ne pas y répondre') ?>.
