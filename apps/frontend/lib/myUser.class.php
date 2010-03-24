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
      $this->setCulture($user->getLanguage()) ;
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
    $user = Doctrine::getTable('user')->retrieveByLdapId($this->getProfileVar(sfConfig::get('app_profile_var_translation_uid'))) ;

    if ($user === null)
    {
      // L'utilisateur se connecte pour la première fois
      $user = new user();
      $user->setLdapId($this->getProfileVar(sfConfig::get('app_profile_var_translation_uid'))) ;
      $user->setName($this->getProfileVar(sfConfig::get('app_profile_var_translation_name'))) ;
      $user->setSurname($this->getProfileVar(sfConfig::get('app_profile_var_translation_surname'))) ;
      $user->setMail($this->getProfileVar(sfConfig::get('app_profile_var_translation_mail'))) ;
      $user->save();
    }

    return $user;
  }
}
