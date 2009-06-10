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
  
  define("DataBase", "mysql://db_user:db_pwd@db_host/dbname"); //BDD de l'application

  define("DataBaseUsers","mysql://db_user:db_pwd@db_host/dbname");//BDD des utilisateurs (pour authentification par BDD)
  define("TABLE_USERS",'users'); //Nom de la table des utilisateurs
  define("FIELD_USERS",'login'); //Champ contenant les logins
  define("FIELD_PWD",'pwd');//Champ contenant les mots de passe 

  //configuration du fichier langue
  define("LangueFile", "lang/lang_french.inc.php");		

  //Debug mode.
  //2 = afficher les informations de débogage (getDebugInfo)
  //1 = afficher les informations (getMesssage)
  //0= ne pas afficher les informations d'erreurs.
  define("DebugMode", "2");

  define("CAS_SERVER","casservername");
  define('CAS_FOLDER','');

  // Type d'authentification
  // cas : force authentification par cas
  // db : force auth par DB
  // vide : par défaut, LDAP
  define('LOGIN_TYPE', 'cas');

  // Nom du template
  define('TEMPLATE_NAME', 'uapv');

  // Nom du site
  define('SITE_NAME', 'RDVZ');

  // Locale
  define('LOCALE', 'fr_FR');

  // LDAP
  $LDAP_SERVERS = array('ldapserver1', 'ldapserver2', 'ldapserver3');
  define("LDAP_DNREAD","cn=exploit,ou=ldap,dc=univ,dc=fr");
  define("LDAP_PASSREAD","");
  define("LDAP_USER_ROOT", "ou=people,dc=univ,dc=fr");
  
  //Duree du sondage
  
  define("nb_del_date",120); //Le sondage est supprimé nb_del_date jours après la création
  define("nb_end_date",90); //Il n'est plus possible de répondre au sondage nb_end_date jours après la création   
?>
