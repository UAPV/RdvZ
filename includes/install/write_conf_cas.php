<?php
session_start();
$path=ini_get('include_path');
$newpath="..:".$path;
ini_set('include_path',$newpath);
$_SESSION['msg']="";

//Creation fichier conf

if (!is_writable('../') )
{
	$_SESSION['msg'].= "<p>L'application n'a pas acc&egrave;s en &eacute;criture au r&eacute;pertoire \"includes\"<br>";
	$_SESSION['msg'].="Veuillez param&eacute;trer ces droits, puis cliquez <a href=".$_PHP['SELF']."> ici </a> pour recommencer.</p>";
	header("Location:error.php");
	
}
else
	{
	if (!isset(
		$_POST["db_login"],
		$_POST["db_pwd"],
		$_POST["db_host"],
		$_POST["db_name"],
		$_POST["email_admin"],
		$_POST["smtp_server"],
		$_POST["smtp_port"],
		$_POST["casservername"],
		$_POST["cas_port"],
		$_POST["cas_folder"],
		$_POST["ldap"],
		$_POST["dnread"],
		$_POST["ldapread_pwd"],
		$_POST["user_root"],
		$_POST["email_admin"],
		$_POST["email_admin"],
		$_POST["smtp_port"],
		$_POST["update"]
	))
		{
			unset($_POST);
			$_SESSION['msg'].="<p>Erreur inconnue lors de la recuperation des donnees. </p>";
				header("Location:error.php");}
else
{				
			
		
	$text="<?php ";
	
	$text.="set_include_path(\".:\".\"includes:/includes/DB:\".get_include_path());\n\n";
	$text.=  "//Adresse de base de donnees\n";
	$db="mysql://".$_POST["db_login"].":".$_POST["db_pwd"]."@".$_POST["db_host"]."/".$_POST["db_name"];
	$text.="define(\"DataBase\",\"".$db."\");\n\n";
	$text.="//configuration du fichier langue\n";
	$text.="define(\"LangueFile\", \"lang/lang_french.inc.php\");\n\n";		
	$text.="  //Debug mode.\n
	  //2 = afficher les informations de débogage (getDebugInfo)\n  //1 = afficher les informations (getMesssage)\n  //0= ne pas afficher les informations d'erreurs.\n
	 define(\"DebugMode\", \"2\");\n\n";
	  $text.=" // Type d'authentification\n define('LOGIN_TYPE', 'cas');\n\n";
	  
	$text.="//parametres CAS\n";
	$text.="define(\"CAS_SERVER\",\"".$_POST["casservername"]."\");\n";
	$text.="define(\"CAS_FOLDER\",\"".$_POST["cas_folder"]."\");\n";
	$text.="define(\"CAS_PORT\",".$_POST["cas_port"].");\n";
	
	
	$text.="//Parametres LDAP\n";
	
	//Recupere les serveurs LDAP
	$ldap_post=$_POST["ldap"];
	$ldap_servers=explode(';',$_POST["ldap"]);
	
	$nb_servers=count($ldap_servers);
	
	
	$text.="\$LDAP_SERVERS=array('";
	for ($i=0 ; $i<$nb_servers ; $i++)
	{
		$server=$ldap_servers[$i];
		$text.=$server."'"; 
		if ($i<$nb_servers-1) $text.=",'";
				
	}	
				$text.=");\n";
				
				
	//Autres parametres LDAP 
	$text .= "define(\"LDAP_DNREAD\",\"".$_POST["dnread"]."\");\n";
	$text.="define(\"LDAP_PASSREAD\",\"".$_POST["ldapread_pwd"]."\");\n";
	$text.="define(\"LDAP_USER_ROOT\", \"".$_POST["user_root"]."\");\n";
	
		
		
	$text.="  // Nom du template\n define('TEMPLATE_NAME', 'uapv');\n\n"; 
	$text.="// Nom du site\n define('SITE_NAME', '".$_POST["site_name"]."');\n\n";
	$text.="// Locale\n define('LOCALE', 'fr_FR');\n\n";
	$text.="//Duree du sondage\n
	  define(\"nb_del_date\",120); //Le sondage est supprimé nb_del_date jours après la création\n
	  define(\"nb_end_date\",90); //Il n'est plus possible de répondre au sondage nb_end_date jours après la création\n\n   
	";
	$text.="//Parametres mails \n";
	$text.="define(\"admin_mail\",\"".$_POST["email_admin"]."\"); //mail de l'administrateur\n";
	$text.="define(\"smtp_host\",\"".$_POST["smtp_server"]."\"); //serveur SMTP\n";
	$text.="define(\"smtp_port\",\"".$_POST["smtp_port"]."\"); //port du précédent\n";
	
	$text.="?>";
	$fp = @fopen('../config.inc.php', 'x+');
	if (!@fwrite($fp,$text)) 
		{$_SESSION['msg'].="<p>Erreur d'ecriture du fichier de configuration.</p>";
		unset($_POST);
		header("Location:error.php");
		}
	else
		{
			
		fclose($fp);	
		//Creation base appli si demande
		if ((isset($_POST['create_db']))&&($_POST['create_db']=='O')){
			$link = mysql_connect($_POST['db_host'],$_POST['db_login'], $_POST['db_pwd']);
			if (!$link) {  die('Connexion impossible : ' . mysql_error());}
			$sql="create database ".$_POST["db_name"];
			if (!@mysql_query($sql, $link)) {
				$_SESSION['msg'].= "<p>Erreur lors de la création de la base de données : ' . mysql_error() . </p>";
				unset($_POST);
			header("Location:error.php");
			}
			mysql_close($link);
		
		}
	
				require_once('./connection.php');
				
		//Si update, modif table
		if (isset($_POST['update']) && $_POST['update']=='O')
		{
			$sql="ALTER TABLE meeting_poll ADD user_comment varchar(200) default NULL";
			$res =$BDD->query($sql);
			if (PEAR::isError($res)) die($res->getDebugInfo());

			$sql="ALTER TABLE meeting ADD comments varchar(1) default 'N',ADD datecomments varchar(1) default 'N'";
			$res =$BDD->query($sql);
			if (PEAR::isError($res)) die($res->getDebugInfo());	
			
			
$sql=			"CREATE TABLE IF NOT EXISTS `meeting_comment` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(50) NOT NULL default '',
  `mid` varchar(8) NOT NULL default '',
  `participant_name` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
)";
$res =$BDD->query($sql);
			if (PEAR::isError($res)) die($res->getDebugInfo());	

		}
			
		//Creation tables base appli di demande
		
		if ((isset($_POST['create_struct']))&&($_POST['create_struct']=='O')){
		
			$sql=	
		"CREATE TABLE meeting (mid varchar(8) NOT NULL,
		  title varchar(255) NOT NULL,
		  description text,
		  uid varchar(50) NOT NULL,
		  closed tinyint(1) NOT NULL default '0',
		  date_del date NOT NULL,
		  date_end date NOT NULL,
		  aifna varchar(1) NOT NULL default 'N',
		  notif varchar(1) NOT NULL default 'N',
		  comments varchar(1) NOT NULL default 'N',
		  datecomments varchar(1) NOT NULL default 'N',
		  PRIMARY KEY (mid)
		)";
		

		$res =$BDD->query($sql);
		if (PEAR::isError($res)) 
		{
			//die($res->getDebugInfo());
			$_SESSION['msg'].= "<p>Erreur lors de la création de la base de données : ' . mysql_error() . </p>";
				unset($_POST);
			header("Location:error.php");}
		
		
		$sql="CREATE TABLE meeting_date (
		  pollid int(11) NOT NULL auto_increment,
		  mid varchar(8) NOT NULL,
		  date date NOT NULL,
		  comment varchar(255) default NULL,
		  PRIMARY KEY  (pollid)
		);";
		$res =$BDD->query($sql);
		if (PEAR::isError($res)) {
			$_SESSION['msg'].= "<p>Erreur lors de la création de la base de données : ' . mysql_error() . </p>";
				unset($_POST);
			header("Location:error.php");
		}
		
		
		
		$sql="CREATE TABLE meeting_poll (
		  uid varchar(50) default NULL,
		  pollid int(11) NOT NULL,
		  poll tinyint(1) NOT NULL,
		  participant_name varchar(255) default NULL,
		  user_comment varchar (200) default NULL
		) ";
		
		$res =$BDD->query($sql);
		if (PEAR::isError($res)) {$_SESSION['msg'].= "<p>Erreur lors de la création de la base de données : ' . mysql_error() . </p>";
				unset($_POST);
			header("Location:error.php");} 
		}
		
		header('Location:success_cas.php');
	}
}
}
?>
