<?php
/**
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

  public function retrieveByLdapId($uid)
  {
    $q = $this->createQuery('u')
      ->where('ldap_id = ?',$uid) ;

    $user = $q->execute() ;
    return isset($user[0]) ? $user[0] : null;
  }
}
