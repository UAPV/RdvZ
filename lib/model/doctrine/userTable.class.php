<?php
/**
  * Contains the Doctrine SQL request on the user table.
  *
  * @author     Romain Deveaud <romain.deveaud@univ-avignon.fr>
  * @project    RdvZ v2.0
  */
class userTable extends Doctrine_Table
{
  /*
  public function getUserInfos($user)
  {
    $infos = array() ;

    $infos['name'] = $user->getProfileVar(sfConfig::get('app_'.sfConfig::get('app_authentication_type').'_infos_user_name_field')) ;
    $infos['surname'] = $user->getProfileVar(sfConfig::get('app_'.sfConfig::get('app_authentication_type').'_infos_user_surname_field')) ;
    $infos['mail'] = $user->getProfileVar(sfConfig::get('app_'.sfConfig::get('app_authentication_type').'_infos_user_mail_field')) ;

    return $infos ;
  }
  */

  /**
    * Get the user with a ldap id.
    *
    * @param $uid string The ldap id.
    * @return Doctrine_Record The user.
    */
  public function retrieveByLdapId($uid)
  {
    $q = $this->createQuery('u')
      ->where('ldap_id = ?',$uid) ;

    $user = $q->execute() ;
    return isset($user[0]) ? $user[0] : null;
  }

  public function retrieveLdapUsers()
  {
    $q = $this->createQuery('u')
      ->where('ldap_id is not null') ;

    return $q->execute() ;
  }
}
