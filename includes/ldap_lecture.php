<?php
/********************************************
*   Fonction de modification ldap           *
*   fichier: ldap_fonctions.php             *
*   version: 5.10                           *
*   date: 26/10/06                          *
*   auteur: Jean-françois Rey               *
*   mail: jean-francois.rey@univ-avignon.fr  *
********************************************/

// URL de redirection par defaut
  define('DEFAULT_URL', 'login.php');
  
  include('config.inc.php');
  global $LDAP_SERVERS;
 

  /************************************************************************/
  /* FONCTION GET_SERVEUR
  /* renvoie un serveur parmi ceux dans le tableau $_LDAPSERVERS
  /* out: serveur
  /************************************************************************/
  function get_serveur()
  {
    global $LDAP_SERVERS;
    
	  // on crée un tableau contenant tous les serveurs que l'on peut atteindre

    srand((double)microtime()*1000000);
    $num_serv = rand(0,count($LDAP_SERVERS)-1);
    // on génére un nombre aléatoire sur le nombre de serveurs pour choisir

    return $LDAP_SERVERS[$num_serv];
  }

  /************************************************************************/
  /* FONCTION IS_LOGGED
  /* renvoie si le user est logge
  /* out: true | false
  /* prerequis : session_start effectué précédemment
  /************************************************************************/
  function is_logged()
  {
    return (isset($_SESSION['uid']));
  }

  /************************************************************************/
  /* FONCTION WHOS_LOGGED
  /* renvoie l'uid du user
  /* out: uid
  /* prerequis : session_start effectué précédemment et user logge
  /************************************************************************/
  function whos_logged()
  {
    if (is_logged)
      return $_SESSION['uid'];
    else
      return NULL;
  }

  /************************************************************************/
  /* FONCTION LDAP_SECURE
  /* redirige vers une adresse si le user n'est pas logge
  /* in: adresse de redirection
  /* prerequis : session_start effectué précédemment
  /************************************************************************/
  function ldap_secure($url_redirect=DEFAULT_URL)
  {
    if (!is_logged())
    {
      header("Location: $url_redirect");
      exit;
    }
  }

   /************************************************************************/
  /* FONCTION LDAP_CHECKUSER : verifie l'authentification LDAP d'un USER
  /* in : dn du user, password
  /* out:
  /* 0 : check ok
  /* 1 : erreur de connexion
  /* 2 : erreur ldap_bind
  /* 3 : erreur ldap_read
  /* 4 : edupersonaffiliation non autoris?  /* prerequis : session_start effectu?pr�c�demment
  /************************************************************************/
  function ldap_checkuser($uid, $password, $autorisation='all', $param=0)
  {
    if ((is_logged()) && ($_SESSION['uid'] == $uid) && ($param!=1))
      return 0;
      // si la variable de session uid est set et que les deux uid correspondent, on retourne 0 => on est d�j?authentifi?

    // sinon on va se connecter pour verifier l'auth

    if($connect=@ldap_connect(get_serveur()))
    // on se connecte sur un des serveurs disponibles
    {
      ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
      $dn='uid='.$uid.','.LDAP_USER_ROOT;
      if($bind=@ldap_bind($connect, $dn, $password))
      {
        if ($autorisation!='all')
        {
          // Si on veut un edupersonaffiliation particulier
          $dn = 'uid='.$uid.','.LDAP_USER_ROOT; //the object itself instead of the top search level as in ldap_search
          $filter='(objectclass=eduPerson)'; // this command requires some filter
          $justthese = array('eduPersonAffiliation'); //the attributes to pull, which is much more efficient than pulling all attributes if you don't do this
          if ($sr = @ldap_read($connect, $dn, $filter,$justthese))
          {
            $entry = ldap_get_entries($connect, $sr);
            // Si cela correspond, on l'ajoute en session
            if ($entry[0]['edupersonaffiliation'][0] == $autorisation)
              $_SESSION['edupersonaffiliation'] = $entry[0]['edupersonaffiliation'][0];
            else
            {
              // pas le bon edupersonaffiliation, on retourne le code d'erreur 4
              @ldap_close($connect);
              return 4;
            }
          }
          else
          {
            // problème sur le ldap_read, on retourne le code d'erreur 3
            @ldap_close($connect);
            return 3;
          }
        }
        // Si le check est correct, on retourne 0 et on met en session l'uid
        @ldap_close($connect);
        $_SESSION['uid'] = $uid;
        return 0;
      }
      else
      {
        // problème sur le ldap_bind, on retourne le code d'erreur 2
        @ldap_close($connect);
        return 2;
      }

    }
    @ldap_close($connect);
    return 1;
    // échec de connexion, on retourne le code d'erreur 1
  }

  /************************************************************************/
  /* FONCTION LDAP_CHECKUSER2 : verifie l'authentification LDAP d'un USER  */
  /* in : dn du user, password                                            */
  /* out: true | false                                                    */
	/************************************************************************/
	function ldap_checkuser2($dn,$password)
	{
		if($connect=@ldap_connect(get_serveur()))
		{
			#echo "connecting...";
			ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
			if($bind=@ldap_bind($connect, $dn, $password))
			{
				#echo "true <BR>";
				@ldap_close($connect);
				return(true);
			}
		}
		#echo "failed <BR>";
		@ldap_close($connect);
		return(false);
	}

  /************************************************************************/
  /* FONCTION LOGOUT
  /* prerequis : session_start effectué précédemment
  /************************************************************************/
  function logout()
  {
    unset($_SESSION['uid']);
  }

  /************************************************************************/
  /* FONCTION GET_INFOS : retourne 
  /* in : uid du user, infos : attributs LDAP sous formes d'un array
  /*        mode = 0 une valeur par attribut : = 1 toutes les valeur
  /*                                                    de chaque attribut
  /* out:
  /* array : infos voulues
  /* mode 0: tableau associatif ['attribut']
  /* mode 1: tableau associatif + indexe ['attribut'][nb] nb>=0
  /* 1 : erreur de connexion
  /* 2 : erreur ldap_read
  /* 3 : erreur ldap_bind
  /************************************************************************/
  function get_infos($uid, $infos,$mode = 0)
  {
  	$ldap_server=get_serveur();
    return $ldap_server;
   
  	if($connect=ldap_connect($ldap_server))
    // on se connecte sur un des serveurs disponibles
    {
      //return $connect;
    	ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
    	//return ldap_error($connect);
    	
    	
    	
    	
	 
      if( $ldapbind = ldap_bind($connect))
      {
        $dn='uid='.$uid.','.LDAP_USER_ROOT;
        $filter='(objectclass=*)';
        
        if ($sr=@ldap_read($connect, $dn, $filter,$infos))
        {
          if($mode == 0)
          {
            $entry = ldap_get_entries($connect, $sr);
            $retour=array();
            for ($i=0; $i<count($infos); $i++)
              $retour[$infos[$i]]=$entry[0][$infos[$i]][0];
            @ldap_close($connect);
            return $retour;
          }
          // mode == 1
          else
          {
            $entry = ldap_get_entries($connect, $sr);
            $retour=array();
            //var_dump($infos);
            //var_dump($entry);
            for ($i=0; $i<count($infos); $i++)
              for($j=0;$j<$entry[0][$infos[$i]]['count'];$j++)
                $retour[$infos[$i]][$j]=$entry[0][$infos[$i]][$j];
            @ldap_close($connect);
            return $retour;
          }
        }
        else
        {
          @ldap_close($connect);
          return 2;
          // echec ldap_read, on retourne le code d'erreur 2
        }
      }
      else
      {
        @ldap_close($connect);
        return 3;
        // echec ldap_bind, on retourne le code d'erreur 3
      }
    }
    @ldap_close($connect);
    return 1;
    // echec de connexion, on retourne le code d'erreur 1 
  }

?>
