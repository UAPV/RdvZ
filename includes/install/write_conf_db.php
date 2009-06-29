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
	$text="<?php ";
	$text.="set_include_path(\".:\".\"includes:/includes/DB:\".get_include_path());\n\n";
	$text.=  "//Adresse de base de donnees\n";
	$db="mysql://".$_POST["db_login"].":".$_POST["db_pwd"]."@".$_POST["db_host"]."/".$_POST["db_name"];
	$text.="define(\"DataBase\",\"".$db."\");\n\n";
	$text.="//Base de donnees des utilisateurs\n";
	$dbu="mysql://".$_POST["users_db_login"].":".$_POST["users_db_pwd"]."@".$_POST["users_db_host"]."/".$_POST["users_db_name"];
	$text.="define(\"DataBaseUsers\",\"".$dbu."\");//BDD des utilisateurs (pour authentification par BDD)\n";
	$text.="define(\"TABLE_USERS\",'".$_POST["users_table"]."'); //Nom de la table des utilisateurs\n";
	$text.="define(\"FIELD_USERS\",'".$_POST["users_field"]."'); //Champ contenant les logins\n";
	$text.="define(\"FIELD_PWD\",'".$_POST["pwd_field"]."');//Champ contenant les mots de passe\n";
	$text.="define(\"FIELD_MAIL\",'".$_POST["email_field"]."');//Champ contenant les emails\n\n"; 
	$text.="define(\"PWD_ENCR\",'".$_POST["pwd_encr"]."');//Methode d'encryptage des mots de passe\n";
	$text.="//configuration du fichier langue\n";
	$text.="define(\"LangueFile\", \"lang/lang_french.inc.php\");\n\n";		
	$text.="  //Debug mode.\n
	  //2 = afficher les informations de débogage (getDebugInfo)\n  //1 = afficher les informations (getMesssage)\n  //0= ne pas afficher les informations d'erreurs.\n
	 define(\"DebugMode\", \"2\");\n\n";
	  $text.=" // Type d'authentification\n define('LOGIN_TYPE', 'db');\n\n";
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
	$text.="define(\"smtp_port\",\"".$_POST["smtp_port"]."\"); //port du précédent";
	$text.="?>";
	$fp = @fopen('../config.inc.php', 'x+');
	if (!@fwrite($fp,$text)) 
	{$_SESSION['msg'].="<p>Erreur d'ecriture du fichier de configuration.</p>";
		header("Location:error.php");}
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
			header("Location:error.php");
			}
			mysql_close($link);
		
		}
		
		//Creation base users si demande
		if (
			(isset($_POST['create_db_users'])&&($_POST['create_db_users']=='O'))&&
			(isset($_POST['create_db'])&&($_POST['create_db']=='O')&&($_POST["db_name"]!=$_POST["users_db_name"]))
			)
		{	
				$link = mysql_connect($_POST['users_db_host'],$_POST['users_db_login'], $_POST['users_db_pwd']);
			if (!$link) { die('Connexion impossible : ' . mysql_error());}
			$sql="create database ".$_POST["users_db_name"];
			if (!@mysql_query($sql, $link)) 
			{
				$_SESSION['msg'].="Erreur lors de la création de la base de données : ' . mysql_error() . </p>";
				header("Location:error.php");
				
			}
			mysql_close($link);			
		}
		
				require_once('./connection.php');
		
		
		
		//Creation tables base appli si demande
		
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
		  PRIMARY KEY (mid)
		)";
		

		$res =$BDD->query($sql);
		if (PEAR::isError($res)) 
		{
			die($res->getDebugInfo());}
		
		
		$sql="CREATE TABLE meeting_date (
		  pollid int(11) NOT NULL auto_increment,
		  mid varchar(8) NOT NULL,
		  date date NOT NULL,
		  comment varchar(255) default NULL,
		  PRIMARY KEY  (pollid)
		);";
		$res =$BDD->query($sql);
		if (PEAR::isError($res)) {die($res->getDebugInfo());}
		
		if (isset($_POST['update'])&&($_POST['update']=='O'))
		{
			$sql="ALTER TABLE meeting_poll add user_comment varachar(200) default NULL";
		}
		
		$sql="CREATE TABLE meeting_poll (
		  uid varchar(50) default NULL,
		  pollid int(11) NOT NULL,
		  poll tinyint(1) NOT NULL,
		  participant_name varchar(255) default NULL,
		  user_comment varchar(200) default NULL
		) ";
		$res =$BDD->query($sql);
		if (PEAR::isError($res)) {die($res->getDebugInfo());} 
		}
		else
		{
		//Dans le cas d'une mise a jour depuis la 0.9, on rajoute les champs aifna et notif
		
		$sql="desc meeting";
		$res =$BDD->query($sql);
		$is_aifna=false;
		$is_notif=false;
		while($result=$res->fetchRow())
		{
			if($result[0]=='aifna') $is_aifna=true;
			if ($result[0]=='notif') $is_notif=true;
		}
		if ($is_aifna==false) 
		{
			$sql="alter table meeting add aifna varchar(1) default 'N'";
			$BDD->query($sql);
		} 
		if ($is_notif==false) 
		{
			$sql="alter table meeting add notif varchar(1) default 'N'";
			$BDD->query($sql);
		} 
		
		}
		
		
		
		//Creation tables users si demande
		if( (isset($_POST["create_table_users"]))&&($_POST["create_table_users"]=='O'))
		{
			require_once('./connection_auth.php');
			//var_dump($BDD_auth);
			
			$sql="create table ".$_POST["users_table"]."(  " .
					$_POST["users_field"]." varchar(255)," .
					$_POST["pwd_field"]." varchar(255)," .
					$_POST["email_field"]." varchar(255)," .
							"primary key(".$_POST["users_field"].")" .
					")";
			$res =$BDD_auth->query($sql);
			if (PEAR::isError($res)) {die($res->getDebugInfo());} 	
		}
		
		//Insertion utilisateur par defaut, s'il n'existe pas deja
		require_once('./connection_auth.php');
		$sql="select count(*) from ".$_POST["users_table"]." where ".$_POST["users_field"]."='rdvz'";
//		echo $sql."<br>";
		$res=$BDD_auth->getOne($sql);
				if ($res==0)
			{
				
				switch ($_POST["pwd_encr"])
				{
					case 'none':
					$default_pwd='rdvz_pwd';
					break;
					
					case 'md5':
					$default_pwd=md5('rdvz_pwd');
					break;
				}
				
				$sql="insert into ".$_POST["users_table"]."(".$_POST["users_field"].",".$_POST["pwd_field"].",". $_POST["email_field"].") values('rdvz','".$default_pwd."','".$_POST["email_admin"]."')";
			$res =$BDD_auth->query($sql);
			if (PEAR::isError($res)) {die($res->getDebugInfo());}}		   
		header('Location:success_db.php');
	}
}
?>