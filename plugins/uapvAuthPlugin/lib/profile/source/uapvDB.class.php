<?php

/**
  * Classe de connection à une base de données utilisée pour
  * l'authentification d'utilisateurs.
  *
  */

class uapvDB
{
  /**
   * Ressource de connection Base de Données
   * @var resource
   */
  private $bdCon = null;

  /**
    * Options de connections
    * @var array
    */
  private $config ;
  
  /**
   * Contructeur
   *
   * @param array $conf Paramètres de connection à la bas. Les paramètres
   *                    obligatoires sont : "host", "pdo", "dbname", 
                        "username", "password"
   */
  public function __construct ($conf = null)
  {
    if (! is_array ($conf))
    {
      $conf = array ();
      $conf['host'] = sfConfig::has('app_bdd_server_host')   ? sfConfig::get('app_bdd_server_host')   : 'localhost' ;
      $conf['pdo']  = sfConfig::has('app_bdd_server_pdo')    ? sfConfig::get('app_bdd_server_pdo')    : 'mysql' ;
      $conf['db']   = sfConfig::has('app_bdd_server_dbname') ? sfConfig::get('app_bdd_server_dbname') : 'rdvz' ;
      $conf['user'] = sfConfig::get('app_bdd_server_username') ;
      $conf['pass'] = sfConfig::get('app_bdd_server_password') ;
      
      $conf['user_tab']     = sfConfig::has('app_bdd_infos_user_table_name') ? sfConfig::get('app_bdd_infos_user_table_name') : 'user' ;
      $conf['user_login']   = sfConfig::has('app_bdd_infos_user_login_field') ? sfConfig::get('app_bdd_infos_user_login_field') : 'login' ;
      $conf['user_pass']    = sfConfig::has('app_bdd_infos_user_pass_field') ? sfConfig::get('app_bdd_infos_user_pass_field') : 'pass' ;
      $conf['pass_crypt']   = sfConfig::has('app_bdd_infos_user_pass_encrypt') ? sfConfig::get('app_bdd_infos_user_pass_encrypt') : 'sha1' ;
    }

    $this->config = $conf;
  }

  public function connect()
  {
    if ($this->bdCon !== null)
      return ;

    //$dsn = $this->config['pdo'].':dbname='.$this->config['db'].';host='.$this->config['host'] ;
    //$dbh = new PDO($dsn, $this->config['user'] , $this->config['pass']) ; 

    $dsn = $this->config['pdo']."://".$this->config['user'].":".$this->config['pass']."@".$this->config['host']."/".$this->config['db'] ;

    $this->bdCon = Doctrine_Manager::connection($dsn) ;
  }

  /**
    * Fonction qui regarde dans la table utilisateurs si le couple login/password
    * entré existe vraiment.
    *
    * @return true or false
    */
  public function checkPassword($user, $pass)
  {
    $this->connect() ;
    
    $crypt = $this->config['pass_crypt'] ;

    switch($crypt)
    {
      case 'md5' :   $pass = md5($pass) ;
                     break ;
      case 'sha1' :  $pass = sha1($pass) ;
                     break ;
      default :      break ;
    }

    $q = $this->bdCon->prepare("select count(*) from ".$this->config['user_tab']." where ".$this->config['user_login']."='$user' and ".$this->config['user_pass']."='$pass'") ;
    $q->execute() ;
    $res = $q->fetch() ;
    return $res[0] ;
  }

  public function getUser($login)
  {
    $this->connect() ;

    $q = $this->bdCon->prepare("select * from ".$this->config['user_tab']." where ".$this->config['user_login']."='$login'") ;
    $q->execute() ;
    $res = $q->fetch() ;
    return $res ;
  }
}
