<?php

class myUser extends uapvBasicSecurityUser
{
  public function configure ()
  {
    //$user = $this->getUserFromDatabase ();
    //$user->save ();

    // définition des autorisation de l'utilisateur
    //$this->addCredential ($this->getProfileVar ('edupersonaffiliation'));
    sfContext::getInstance()->getLogger()->debug('####################### '.$this->getProfileVar('uidnumber')) ;
    $this->addCredential ('member');
  }

  /**
   * Retourne les données de l'utilisateur depuis la base de données.
   * L'utilisateur sera créé s'il n'existe pas.
   * @return User 
   */
  /*
  public function getUserFromDatabase ()
  {
    $user = SympaGroupsUserPeer::retrieveByLdapUid($this->getProfileVar('uidnumber')) ;
    if ($user === null)
    {
      // L'utilisateur se connecte pour la première fois
      $user = new SympaGroupsUser ();
      $user->setLdapId($this->getProfileVar('uidnumber')) ;
    }
    return $user;
  }
  */
}
