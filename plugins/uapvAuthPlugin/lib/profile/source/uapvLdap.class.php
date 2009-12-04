<?php

/**
 * Classe de connection à un annuaire LDAP
 *
 * Inspiré de
 * http://code.google.com/p/iup-gestion-projets/source/browse/trunk/src/library/GP/Ldap.php
 */
class uapvLdap
{
  /**
   * Ressource de connection LDAP
   * @var resource
   */
  private $ldapCon = null;

  /**
   * Options de connections
   * @var array
   */
  private $config;

  /**
   * Contructeur
   * @param array $conf Paramètres de connection à l'annuaire. Les paramètres
   *                    possibles sont : "host", "port", "options", "dn"
   *                    "password", "basedn"
   */
  public function __construct ($conf = null)
  {
    if (! is_array ($conf))
    {
      $conf = array ();
      if (sfConfig::has ('app_ldap_server_host'))     $conf ['host']    = sfConfig::get ('app_ldap_server_host');
      if (sfConfig::has ('app_ldap_server_port'))     $conf ['port']    = sfConfig::get ('app_ldap_server_port');
      if (sfConfig::has ('app_ldap_server_dn'))       $conf ['dn']      = sfConfig::get ('app_ldap_server_dn');
      if (sfConfig::has ('app_ldap_server_password')) $conf ['password']= sfConfig::get ('app_ldap_server_password');
      if (sfConfig::has ('app_ldap_server_basedn'))   $conf ['basedn']  = sfConfig::get ('app_ldap_server_basedn');
      if (sfConfig::has ('app_ldap_server_options'))  $conf ['options'] = sfConfig::get ('app_ldap_server_options');
    }

    $this->config = $conf;
  }


  /**
    * Vérifie si l'utilisateur a entré un login et un mdp qui
    * correspondent à une entrée de l'annuaire Ldap.
    *
    * @param string $user Une requête de la forme "uid=uapv8802366", mais
    * pas obligé.
    * @param string $password Le mot de passe en clair.
    *
    * @return true ou false.
    */
  public function checkPassword($user, $password)
  {
    $us = $this->searchOne($user) ;
    $this->disconnect() ;

    if (is_null($us['dn'])) return false ;

    $this->config['dn'] = $us['dn'] ;
    $this->config['password'] = $password ;

    $resp = true ;
    try
    {
      $this->connect() ;
    }
    catch(sfException $e)
    {
      $resp = false ;
    }

    return $resp ;
  }

  /**
   * Destructeur
   * Coupe la connection
   */
  public function __destruct ()
  {
    $this->disconnect ();
  }

  /**
   * Se connecte à l'annuaire qui a été configuré lors de la construction de
   * l'objet.
   */
  public function connect ()
  {
    if ($this->ldapCon !== null)
      return;

    $host     = array_key_exists ('host', $this->config) ? $this->config['host'] : 'localhost';
    $port     = array_key_exists ('port', $this->config) ? $this->config['port'] : null;
    $dn       = array_key_exists ('dn', $this->config)   ? $this->config['dn']   : null;
    $password = array_key_exists ('password', $this->config) ? $this->config['password'] : null;

    if ($port === null)
      $this->ldapCon = @ldap_connect ($host);
    else
      $this->ldapCon = @ldap_connect ($host, $port);

    if ($this->ldapCon === false)
      throw new sfException ("Unable to connect to ldap server $host:$port");

    if (array_key_exists('options', $this->config) && is_array($this->config['options']))
      foreach ($this->config ['options'] as $k => $v)
        if (! @ldap_set_option ($this->ldapCon, $k, $v))
          throw new sfException ("Unable to set ldap option ($k = $v)");

    if ($dn === null)
        $binded = @ldap_bind ($this->ldapCon);
    else if ($password === null)
        $binded = @ldap_bind ($this->ldapCon, $dn);
    else
        $binded = @ldap_bind ($this->ldapCon, $dn, $password);

    if ($binded === false)
      throw new sfException ("Cannot bind to the ldap server $host:$port with dn '$dn'");
  }

  /**
   * Fermeture de la connection courante si elle existe
   */
  public function disconnect ()
  {
      if (is_resource ($this->ldapCon) && ! @ldap_unbind ($this->ldapCon))
          throw new sfException ('Echec de la déconnexion du serveur ldap');

      $this->ldapCon = null;
  }

  /**
   * Recherche une entrée dans la base ldap.
   * La recherche peut être filtrée suivant le paramètre $filter. Si ce
   * paramètre n'est pas précisé, l'ensemble des données de l'annuaire
   * seront renvoyées.
   * La recherche est effectué dans le noeud $dnPrefix de l'arbre ldap.
   * Si l'option baseDn à été utilisé, la valeur de l'option servira
   * de "racine" pour le noeud paramètre $dnPrefix.
   *
   * Exemple de valeur des parametres :
   * $dn = "o=Ma Compagnie, c=FR";
   * $filter="(|(sn=$person*)(givenname=$person*))";
   * $justthese = array( "ou", "sn", "givenname", "mail");
   *
   * Utilisation :
   * $uapvLdap = new uapvLdap();
   * $sr=$uapvLdap->search($filter,$dn, $justthese);
   *
   *
   * TODO permettre de ne récupérer que certains attributs
   *
   * @param string $filter     Filtre utilisé par la recherche.
   */
  public function search ($filter = null, $dnPrefix = null, $justthese = null)
  {
      $this->connect ();

      // Filtre par défaut s'il n'est pas précisé.
      if ($filter === null)
         $filter = 'objectClass=*';

      if ($justthese === null)
        $justthese = array();

      // Détermination de la racine de la recherche.
      $baseDn = array ();
      if ($dnPrefix !== null && $dnPrefix != "")
        $baseDn [] = $dnPrefix;

      if (array_key_exists ('basedn', $this->config)
        && $this->config ['basedn'] != "")
        $baseDn [] = $this->config ['basedn'];

      $baseDn = implode (',', $baseDn);

      // Envoie et récupération de la requête.
      $result = ldap_search ($this->ldapCon, $baseDn, $filter, $justthese);
      if ($result === false)
        throw new sfException ('Impossible d\'effectuer la recherche dans l\'annuaire ldap');

      // Suppression des attributs "count"
      $entries = ldap_get_entries ($this->ldapCon, $result);

      return $this->cleanEntries ($entries);
  }

  /**
   * Cette fonction sert à extraire une entrée unique de l'annuaire.
   *
   * @param string $filter   Filtre de recherche.
   * @param string $dnPrefix Préfixe de la racine utilisée par la recherche. Ce
   *                         paramètre à le même comportement que la fonction search.
   * @return                 Entrée trouvée ou null dans le cas inverse
   * @see #search (string, string)
   */
  public function searchOne ($filter, $dnPrefix = null)
  {
      $this->connect ();
      $entrySet = $this->search ($filter, $dnPrefix);
      return (is_array ($entrySet) && count ($entrySet)) ?
        $entrySet[0] : null;
  }

  /**
   * Supprime les attributs "count" dans les résultats retournés par ldap_get_entries
   *
   * @param array
   * @return array
   */
  private function & cleanEntries (array &$entries)
  {
    unset ($entries ['count']);

    // Pour chaque entrée
    foreach ($entries as $key => $attributes)
    {
      unset ($entries [$key]['count']);

      for ($i = 0; array_key_exists ($i, $entries [$key]); $i++)
          unset ($entries [$key][$i]);

      // Pour chaque attribut d'une entrée
      foreach ($entries[$key] as $attName => $attValue)
      {
        if (is_array ($entries[$key][$attName]))
        {
          if ($entries[$key][$attName]['count'] > 1)
            unset ($entries [$key][$attName]['count']);
          else
            $entries [$key][$attName] = $entries [$key][$attName][0];
        }
      }
    }

    return $entries;
  }
}
