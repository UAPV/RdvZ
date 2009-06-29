<?php set_include_path(".:"."includes:/includes/DB:".get_include_path());

//Adresse de base de donnees
define("DataBase","mysql://root:route@localhost/rdvz11new");

//configuration du fichier langue
define("LangueFile", "lang/lang_french.inc.php");

  //Debug mode.

	  //2 = afficher les informations de débogage (getDebugInfo)
  //1 = afficher les informations (getMesssage)
  //0= ne pas afficher les informations d'erreurs.

	 define("DebugMode", "2");

 // Type d'authentification
 define('LOGIN_TYPE', 'cas');

//parametres CAS
define("CAS_SERVER","cas.univ-avignon.fr");
define("CAS_FOLDER","");
define("CAS_PORT",439);
//Parametres LDAP
$LDAP_SERVERS=array('ldaps://ldap01c.univ-avignon.fr');
define("LDAP_DNREAD","cn=exploit,ou=ldap,dc=univ-avignon,dc=fr");
define("LDAP_PASSREAD","");
define("LDAP_USER_ROOT", "ou=people,dc=univ-avignon,dc=fr");
  // Nom du template
 define('TEMPLATE_NAME', 'uapv');

// Nom du site
 define('SITE_NAME', '');

// Locale
 define('LOCALE', 'fr_FR');

//Duree du sondage

	  define("nb_del_date",120); //Le sondage est supprimé nb_del_date jours après la création

	  define("nb_end_date",90); //Il n'est plus possible de répondre au sondage nb_end_date jours après la création

   
	//Parametres mails 
define("admin_mail",""); //mail de l'administrateur
define("smtp_host",""); //serveur SMTP
define("smtp_port",""); //port du précédent
?>