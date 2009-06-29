<?php
/********************************************
Fonction: French language file.
Version: 1.1
Date: 2008, October
Auteurs: Liang HONG, Min WANG
Université d'Avignon
http://gpl.univ-avignon.fr/
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
  	 'DB'=>"Base de donn&eacute;es",
  	 'Before processing'=>"Avant de proc&eacute;der &agrave l'installation, v&eacute;rifiez bien que : ",
  	 'Directory writable'=>"Le r&eacute;pertoire \"includes\" est accessible en &eacute;criture.",
  	 'Rights OK'=>"Vous poss&eacute;dez les droits n&eacute;cessaires sur vos bases de donn&eacute;es.",
  	 'If OK you can complete'=>"Si c'est bien le cas, vous pouvez maintenant remplir les champs suivants :",
  	 'Application database parameters'=>"Param&egrave;tres de la base de donn&eacute;es de l'application",
  	 'Database host'=>"H&ocirc;te de la base de donn&eacute;es :",
  	 'Database name'=>"Nom de la base :",
  	 'User name'=>"Nom d'utilisateur : ",
  	 'Password'=> "Mot de passe : ",
  	 'Create DB ?'=>"Cr&eacute;er la base ?",
  	 'Yes'=>"Oui",
  	 'No'=>"Non",
  	 'Create table'=>"Cr&eacute;er les tables ?",
     'Update from 1.1'=>"Mise &agrave; jour depuis la version 1.1 ?",
  	 'CAS parameters'=>"Param&egrave;tres du serveur CAS",
  	 'CAS server name'=>"Nom du serveur CAS :",
  	 'CAS folder'=>"URI du serveur CAS",
     'CAS port'=>"Port du serveur CAS",
  	 'LDAP parameters'=>"Param&egrave;tres des serveurs LDAP",
  	 'LDAP servers'=>"Serveur(s) LDAP :",
  	 'If several LDAP servers'=>"(S'il y a plusieurs serveurs, les s&eacute;parer par des points-virgules)<br>",
  	 'User for read only'=>"Utilisateur pour lecture seule :",
  	 'Password for read only'=>"Mot de passe pour lecture seule : ",
  	 'User branch root'=>"Racine de la branche utilisateur :",
  	 'Mail parameters'=>"Param&egrave;tres pour l'envoi de mail ",
  	 'e-mail'=>"Adresse e-mail de l'utilisateur :",
  	 'smtp server'=>"Serveur smtp : ",
  	 'smtp port'=>" Port du serveur smtp :", 
  	 'Miscellaneous parameters'=>"Param&egrave;tres divers",
  	 'Site name'=>"Nom du site : ",
  	 
  	 'Users database parameters'=>"Param&egrave;tres de la base de donn&eacute;es contenant les utilisateurs",
  	 'Users table'=>"Table contenant les utilisateurs : ",
  	 'Login field'=>"Nom du champ contenant les logins des utilisateurs : ",
  	 'Password field'=> "Nom du champ contenant les mots de passe des utilisateurs :",
  	 'email field'=>"Nom du champ contenant les e-mails des utilisateurs :",
  	 'Password encryption'=>"Encryptage des mots de passe : ",
  	 'Clear'=>"En clair",
  	 'Congratulations'=>"F&eacute;licitations !",
  	 'RDVZ installed'=>"RDVZ est maintenant install&eacute;",
  	 'Click on the following link to use RDVZ'=>"Cliquez sur le lien suivant pour commencer &agrave; utiliser l'application : ",
  	 'Default user was created'=>"Un utilisateur par d&eacute;faut a &eacute;t&eacute; cr&eacute;&eacute;. Vous pouvez l'utiliser pour vous connecter &agrave; l'application : ",
  	 'Login'=>"Login",
  	
  	 
  	  
  	  
  	


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
    'Now you can choose'=> "Vous pouvez indiquer vos disponibilités en vous rendant à l'adresse suivante :",
    'Available until'=> "Votes possibles jusqu'au : ",

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
    'Comment information' => "Vous pouvez utiliser ces champs pour pr&eacute;ciser n'importe quelle information comme une heure, un intervalle de temps ou un lieu : \"de 8h à 9h\" , \"salle PC4, 15h IUP.\"",
    'Available if needed allowed' => "Souhaitez-vous autoriser les votes \"Disponible en cas de besoin\" ?",
    'Available'=> 'Disponible',
    'Available if needed' => 'Disponible en cas de besoin',
    'Not Available' => 'Non disponible',
    'Yes'=>"Oui",
    'No'=>"Non",
    'Notification message'=>"Un vote a été effectué pour votre sondage :",
    'Voted by'=>'Votant :',
    'Not authenticated'=>" (non authentifié) ",
    'CAS authenticated'=>" (authentifié)",
    'Do not reply'=>"\n-----\n NE REPONDEZ PAS A CE MESSAGE ! \n Il a été généré automatiquement, votre réponse n'aboutirait pas !",
    'check all'=>"Tout cocher",
    'uncheck all'=>"Tout décocher",
    'Click for comment'=>"Cliquer sur cette ic&ocirc;ne pour ajouter un commentaire sous la date correspondante.",
    'Write a comment'=>"Saisissez un commentaire",
    'Add'=>"Ajouter",
    'Available comments'=>"Souhaitez-vous permettre aux votants d'ajouter un commentaire global ?",
    'Available date comments'=>"Souhaitez-vous permettre aux votants d'ajouter un commentaire pour chaque date ?"
   

  );
?>
