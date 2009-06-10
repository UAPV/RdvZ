<?php
session_start();
$path=ini_get('include_path');
$newpath="includes:".$path;
ini_set('include_path',$newpath);


require_once('includes/ldap_lecture.php');
require_once('includes/config.inc.php');
require_once('includes/connection_auth.php');
require_once('includes/connection.php');
$msg="";

if (isset($_GET['type'])) $login_type = $_GET['type'];
else $login_type=LOGIN_TYPE;

// default check ldap!
if( !isset($login_type) && isset($_POST['login']) )
{
	
	session_start();
	if ( ($res = ldap_checkuser($_POST['login'],$_POST['password'])) == 0 )
	{
		$_SESSION['uid'] = $_POST['login'];
		if( ($res = get_infos($_SESSION['uid'],array('cn','givenname','sn','mail'))) != 1 && $res != 2 && $res !=3)
    		{
			$_SESSION['cn'] = $res['cn'];
			$_SESSION['givenname'] = $res['givenname'];
			$_SESSION['sn'] = $res['sn'];
			$_SESSION['mail'] = $res['mail'];
      			header("Location: ".ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/index.php");
			exit;
		}
		else
		{
			$msg.="ERROR-LDAP !<br />";
     			logout();
		}
	}
	else
   	{
      		$msg.="Access denied! <br /> Login/Password incorrect!<br />";
     		logout();
   	}
	echo $res;
}
// else check CAS

elseif( isset($login_type) && ($login_type == 'cas' || $login_type == 'ent'))
{
  
  // import phpCAS lib
  include_once('CAS/CAS.php');
  
  //phpCAS::setDebug('/home/jeff/CAS/phpcas.log');

  // initialize phpCAS
  phpCAS::client(CAS_VERSION_2_0,CAS_SERVER,443,CAS_FOLDER,false);  
  

  // set the language to french
  phpCAS::setLang(PHPCAS_LANG_FRENCH);

  // force CAS authentication
  phpCAS::forceAuthentication();
 
  if(phpCAS::checkAuthentication())
  {
    session_start();
    $_SESSION['uid'] = phpCAS::getUser();
    if( ($res = get_infos($_SESSION['uid'],array('cn','givenname','sn','mail'))) != 1 && $res != 2 && $res !=3)
    {
	$_SESSION['cn'] = $res['cn'];
	$_SESSION['givenname'] = $res['givenname'];
	$_SESSION['sn'] = $res['sn'];
	$_SESSION['mail'] = $res['mail'];
      
  if ($login_type == 'cas')
  
  
  header("Location: ".ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/index.php?type=cas");
  elseif($login_type == 'ent')
    header("Location: ".ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/index.php?type=ent");
	exit;
     }
   }
   else
   {
      $msg.="Erreur-LDAP <br />";
      logout();
   }
}

elseif (isset($login_type)&&($login_type='db')&&isset($_POST['login']))
{
	$sql="select ".FIELD_PWD." from ".TABLE_USERS." where ".FIELD_USERS."='".$_POST['login']."'";

	
	$pwd = $BDD_auth->getOne($sql);
	if ($pwd==$_POST['password']) 
	{
		$_SESSION['uid']=$_POST['login'];
		header("Location: ".ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/index.php?type=db");
	}
	else $msg="Identifiant ou mot de passe incorrect !";
	
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Pragma" content="no-cache">
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <LINK href="style.css" type="text/css" rel="stylesheet">
  <title>RDVZ</title>
</head>
<body>
<script language="JavaScript" type="text/javascript">
function setFocus()
{
    if (document.form_login.login.value == "") {
        document.form_login.login.focus();
    } else {
        document.form_login.password.focus();
    }
}
</script>
  <br />
  <div align="center" ><h1>Login</h1></div>
 
  <table width="300" border="1" bordercolor="#000000" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td align="center">
        <?php if(!empty($msg)) { ?> <font color="red"><?php echo $msg; ?></font><?php } ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="4" align="center">
          <form method="post" name="form_login" action="login.php">
          <tr>
            <td width="100" align="right"><label for="login">Login :</label></td>
            <td align="left"><input type="text" name="login" size="20" class="keywords"/></td>
          </tr>
          <tr>
            <td width="100" align="right"><label for="password">Mot de passe :</label></td>
            <td align="left"><input type="password" name="password" size="20" class="keywords"/></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" name="button_login" value="  Connexion  " class="button"/></td>
          </tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
<br />

<script language="JavaScript" type="text/javascript">
setFocus()
</script>
</body>
</html>
