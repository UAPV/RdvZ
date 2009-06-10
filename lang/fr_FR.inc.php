<?php
/********************************************
Fonction: Fichier Langue Francais
Version: 1.0 
Date: Decembre 2007
Auteurs: Liang HONG, Min WANG
IUP GMI d'Avignon
http://www.iup.univ-avignon.fr
********************************************/

/*define('PageTitle', 'Meeting');
 */

  $translations = array(
  
  	'Install Welcome'=>"Bienvenue dans l'installation de RDVZ 1.1",
  	'Please choose your authentication way'=>"Veuillez choisir le mode d'authentification des utilisateurs",
  	 'Please check installation rights'=>"Pour mener &agrave; bien l'installation, assurez-vous des points suivants :" .
  	 		"<ul>" .
  	 		"<li> Le r&eacute;pertoire \"includes\" doit &ecirc;tre accessible en &eacute;criture. </li>" .
  	 		"<li> Vous devez disposer des privil&egrave;ges n&eacute;cessaires sur vos bases de donn&eacute;es. (cr&eacute;ation, modification, insertion)</li>" .
  	 		"</ul>",
  	
  
  
    'Code' => "Code",
    'Title' => "Titre",
    'Action' => "Action",
    'Edit' => "Modifier",
    'Delete' => "Effacer",
    'Cancel' => "Annuler",
    'Next' => "Suivant",
    'Finish' => "Terminer",
    'Thanks' => "Merci",
    'Comment' => "Commentaire",
    'Participate' => "Participer",
    'Export'=>"Exporter au format csv",
    'Close' =>"Clore les votes",
    'Reopen'=>"Rouvrir les votes",
    'Automatic closure'=>"Fermeture automatique des votes le  ",
    'Automatic deletion'=> "Suppression automatique du sondage le  ",
    'You can reopen until'=>"Les votes sont clos. Vous pouvez les rouvrir jusqu'au  ",
    'Poll not available anymore'=>"Ce sondage n'existe plus.",
    'Enter email'=>"Entrez une liste d'adresses &agrave; qui vous souhaitez adresser ce sondage ",
    'Several adresses'=> "(s&eacute;par&eacute;es par des points-virgules)",
    'Alert email'=>"Souhaitez-vous recevoir une notification par e-mail lorsque quelqu'un vote ?",

    'Mon' => "Lun.",
    'Tue' => "Mar.",
    'Wed' => "Mer.",
    'Thu' => "Jeu.",
    'Fri' => "Ven.",
    'Sat' => "Sam.",
    'Sun' => "Dim.",

    'January' => "Janvier",
    'February' => "F&eacute;vrier",
    'March' => "Mars",
    'April' => "Avril",
    'May' => "Mai",
    'June' => "Juin",
    'July' => "Juillet",
    'August' => "Août",
    'September' => "Septembre",
    'October' => "Octobre",
    'November' => "Novembre",
    'December' => "D&eacute;cembre",

    'New Meeting' => "Nouveau Rendez-vous",
    'My Meetings' => "Mes rendez-vous",
    'No Meeting' => "Vous n'avez aucun rendez-vous",
    'Do you really want to delete this meeting?' => "Voulez-vous vraiment supprimer ce rendez-vous ?",
    'Informations' => "Informations g&eacute;n&eacute;rales (&eacute;tape 1 sur 2)",
    'Description' => "Description (facultatif)",
    'Date selection' => "S&eacute;lection de dates (&eacute;tape 2 sur 2)",
    'Date selection hint' => "Cliquez sur un jour pour le s&eacute;lectionner. Cliquez << et >> pour changer de mois.",
    'Dates selected' => "Dates s&eacute;lectionn&eacute;es :",
    'You must choose a title for the meeting.' => "Vous devez indiquer un titre pour ce rendez-vous.",
    'No date selected.' => "Aucune date n'a encore &eacute;t&eacute; s&eacute;lectionn&eacute;e.",
    'Please choose at least one date.' => "Veuillez indiquer au moins une date.",
           
        
     'RDV notification' => "Un rendez-vous vous est proposé par ",
    'Now you can choose'=>"Vous pouvez indiquer vos disponibilités en vous rendant à l'adresse suivante :",
    'Available until'=>"Votes possibles jusqu'au : ",

    'Information saved' => "Vos informations ont &eacute;t&eacute; enregistr&eacute;es, le code de r&eacute;union est <b>%s</b>. Vous pouvez maintenant inviter des personnes à faire leurs choix. Le plus simple est d'envoyer le lien suivant par courriel à tous les participants potentiels :",
    'Information saved notice' => "Dans cette page, ils pourront faire leur choix. V&eacute;rifiez de bien copier l'adresse URL compl&eacute;tement.",
    'Meeting not found' => "Ce rendez-vous n'existe pas ! V&eacute;rifiez de bien copier l'adresse URL complètement.",
    'Poll not available anymore'=>"Ce rendez-vous n'est plus disponible.",

    'What are your preferences' => "Quelles sont vos disponibilit&eacute;s ?",
    'Title:' => "Titre :",
    'Poll tip' => "Entrez votre nom dans le champ de texte ci-dessous et indiquez votre s&eacute;lection en cliquant sur les cases à cocher. Utilisez ensuite le bouton \"Participer\" pour valider votre vote. Si vous disposez d'un compte CAS, vous pouvez aussi <a href=\"%s\">vous connecter</a>.",
    'Authentified poll tip' => "Indiquez votre s&eacute;lection en cliquant sur les cases &agrave; cocher. Utilisez ensuite le bouton \"Participer\" pour valider votre vote.",
    'Readonly poll tip' => "Ce sondage est d&eacute;sormais ferm&eacute;. Voici les r&eacute;sultats :",
	'OK' => "D'accord",
    'Not OK' => "Pas d'accord",
    'Results:' => "R&eacute;sultats :",
    'No description' => "Aucune description.",
    'Your name' => "Votre nom",
    'Please enter your name.' => "Veuillez indiquer votre nom.",
    'Edit warning' => "Attention : La modification de cette r&eacute;union entrainera la suppression des votes d&eacute;jà enregistr&eacute;s.",
    'Comment information' => "Vous pouvez utiliser ces champs pour pr&eacute;ciser n'importe quelle information comme une heure, un intervalle de temps ou un lieu: \"de 8h à 9h\" , \"salle PC4, 15h IUP.\"",
    'Available if needed allowed' => "Souhaitez-vous autoriser les votes \"Disponible en cas de besoin \" ?", 
    'Available'=> 'Disponible',
    'Available if needed' => 'Disponible en cas de besoin',
    'Yes'=>"Oui",
    'No'=>"Non",
    'Notification message'=>"Un vote a été effectué pour votre sondage :",
    'Voted by'=>'Votant :',
    'Not authenticated'=>" (non authentifié) ",
    'CAS authenticated'=>" (authentifié)",
    'Do not reply'=>"\n-----\n NE REPONDEZ PAS A CE MESSAGE ! \n Il a été généré automatiquement, votre réponse n'aboutirait pas !"
    
  );
?>
