<?php

class myUser extends uapvBasicSecurityUser
{
  public function configure ()
  {
    if(sfConfig::get('app_authentication_type') == 'ldap')
    {
      $user = $this->getUserFromDatabase();
      $user->save ();
      $this->getProfile()->set('rdvz_user_id',$user->getId()) ;
    }

    // définition des autorisation de l'utilisateur
    //$this->addCredential ($this->getProfileVar ('edupersonaffiliation'));
    $this->addCredential ('member');
  }

  /**
   * Retourne les données de l'utilisateur depuis la base de données.
   * L'utilisateur sera créé s'il n'existe pas.
   * @return User 
   */
  public function getUserFromDatabase ()
  {
    $user = Doctrine::getTable('user')->retrieveByLdapId($this->getProfileVar(sfConfig::get('app_ldap_infos_user_id_field'))) ;

    if ($user === null)
    {
      // L'utilisateur se connecte pour la première fois
      $user = new user();
      $user->setLdapId($this->getProfileVar(sfConfig::get('app_ldap_infos_user_id_field'))) ;
      $user->setName($this->getProfileVar(sfConfig::get('app_ldap_infos_user_name_field'))) ;
      $user->setSurname($this->getProfileVar(sfConfig::get('app_ldap_infos_user_surname_field'))) ;
      $user->setMail($this->getProfileVar(sfConfig::get('app_ldap_infos_user_mail_field'))) ;
      $user->save();
    }

    return $user;
  }
}
