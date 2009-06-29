<?php 

//PARAMETRES A RENSEIGNER
//Ces parametres doivent etre adaptes en fonction de votre configuration
//-------------------------


//Adresse de base de donnees
//---------------------------
//db_user : utilisateur 
//db_pwd : mot de passe correspondant
//db_host : hote de la base
//dbname : nom de la base de donnees
define("DataBase", "mysql://rdvz_test:okpasOKmaisSI@sql00c.univ-avignon.fr/rdvz_test");//BDD de l'applciation
    
// Type d'authentification
//------------------------
// cas : force authentification par cas
// db : force auth par DB
// vide : par défaut, LDAP
define('LOGIN_TYPE', 'cas');
    

// Base de donnees pour authentification par BDD
//-----------------------------------------------
//Les parametres ci-dessous ne doivent etre renseignes et commentes que si le mode d'authentification choisi est "base de donnees"
/*define("DataBaseUsers","mysql://db_user:db_pwd@db_host/dbname");//BDD des utilisateurs (pour authentification par BDD)
define("TABLE_USERS",'users'); //Nom de la table des utilisateurs
define("FIELD_USERS",'login'); //Champ contenant les logins
define("FIELD_PWD",'pwd');//Champ contenant les mots de passe*/ 
 
  
//Parametres CAS
//--------------
define("CAS_SERVER","cas.univ-avignon.fr"); //Le nom du sergveur CAS
define ("CAS_PORT",443);
define("CAS_FOLDER","");


//Parametres LDAP
//---------------
$LDAP_SERVERS = array('ldaps://ldap01c.univ-avignon.fr', 'ldaps://ldap02c.univ-avignon.fr', 'ldaps://ldap03c.univ-avignon.fr'); //serveur(s) LDAP (laisser sous forme de tableau meme s'il n'ya qu'un seul serveur)
define("LDAP_DNREAD","cn=exploit,ou=ldap,dc=univ-avignon,dc=fr");
  define("LDAP_PASSREAD","");
  define("LDAP_USER_ROOT", "ou=people,dc=univ-avignon,dc=fr");
 

//Parametres mail
//---------------
// Pour l'envoi de mails par l'application
define("admin_mail","rdvz-admin@univ.fr"); //e-mail de l'administrateur
define("smtp_host","smtp.univ-avignon.fr"); //serveur SMTP a utiliser
define("smtp_port","25"); //port du précédent



//PARAMETRES OPTIONNELS
//---------------------
// Les paremetres ci-dessous peuvent etre laisses tels quels. 
// Vous pouvez les modifier pour une personnalisation plus fine de l'application
 
// Nom du template
//----------------
define('TEMPLATE_NAME', 'uapv');

// Nom du site
//------------
define('SITE_NAME', 'RDVZ');

// Langue (laisser fr_FR pour francais)
//--------------------------------------
define('LOCALE', 'fr_FR') ;

//Debug mode
//----------
//2 = afficher les informations de débogage (getDebugInfo)
//1 = afficher les informations (getMesssage)
//0= ne pas afficher les informations d'erreurs.
define("DebugMode", "2");


//Duree du sondage
//---------------- 
define("nb_del_date",120); //Le sondage est supprimé nb_del_date jours après la création
define("nb_end_date",90); //Il n'est plus possible de répondre au sondage nb_end_date jours après la création   

define('CAS_FOLDER','');

  
  
?>
