<?php 
/********************************************
Fonction: Fichier Configuration
Version: 1.0 
Date: Decembre 2007
Auteurs: Liang HONG, Min WANG
IUP GMI d'Avignon
http://www.iup.univ-avignon.fr
********************************************/

  //Adresse de base de donnees
  
  define("DataBase", "mysql://root:route@localhost/rdvz-revisited");
  
  //BAse de données user (pour authentification par BD)
  define("DataBaseUsers", "mysql://root:route@localhost/rdvz-usrs");
  define("TABLE_USERS",'users');
  define("FIELD_USERS",'login');
  define("FIELD_PWD",'pwd');
  define("FIELD_MAIL",'mail');
  

  //configuration du fichier langue
  define("LangueFile", "lang/lang_french.inc.php");		

  //Debug mode.
  //2 = afficher les informations de débogage (getDebugInfo)
  //1 = afficher les informations (getMesssage)
  //0= ne pas afficher les informations d'erreurs.
  define("DebugMode", "2");

  define("CAS_SERVER","cas.univ-avignon.fr");
  define('CAS_FOLDER','');

  // Type d'authentification
  // cas : force authentification par cas
  // db : force auth par DB
  // vide : par défaut, LDAP
  define('LOGIN_TYPE', 'cas');

  //Identification par bdd
  define('DB_LOGIN','1');

  // Nom du template
  define('TEMPLATE_NAME', 'uapv');

  // Nom du site
  define('SITE_NAME', 'RDVZ');

  // Locale
  define('LOCALE', 'fr_FR');

  // LDAP
  $LDAP_SERVERS = array('ldaps://ldap01c.univ-avignon.fr', 'ldaps://ldap02c.univ-avignon.fr', 'ldaps://ldap03c.univ-avignon.fr');
  define("LDAP_DNREAD","cn=exploit,ou=ldap,dc=univ-avignon,dc=fr");
  define("LDAP_PASSREAD","");
  define("LDAP_USER_ROOT", "ou=people,dc=univ-avignon,dc=fr");
  
  //Duree du sondage
  
  define("nb_del_date",120); //Le sondage est supprimé nb_del_date jours après la création
  define("nb_end_date",90); //Il n'est plus possible de répondre au sondage nb_end_date jours après la création
  
  
  //Paramètres envois de mail
define("admin_mail","rdvz-admin@univ-avignon.fr"); //mail de l'administrateur
define("smtp_host","smtp.univ-avignon.fr"); //serveur SMTP
define("smtp_port","25"); //port du précédent
     
?>