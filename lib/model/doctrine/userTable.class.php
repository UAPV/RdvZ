<?php
/**
 */
class userTable extends Doctrine_Table
{
  public function getUserInfos($user)
  {
    $infos = array() ;

    if(sfConfig::get('app_authentication_type') == 'bd')
    {
      $infos['name'] = $user->getProfileVar(sfConfig::get('app_bdd_infos_user_name_field')) ;
      $infos['surname'] = $user->getProfileVar(sfConfig::get('app_bdd_infos_user_surname_field')) ;
      $infos['mail'] = $user->getProfileVar(sfConfig::get('app_bdd_infos_user_mail_field')) ;
    }
    else 
    {
      $infos['name'] = $user->getProfileVar(sfConfig::get('app_ldap_infos_user_name_field')) ;
      $infos['surname'] = $user->getProfileVar(sfConfig::get('app_ldap_infos_user_surname_field')) ;
      $infos['mail'] = $user->getProfileVar(sfConfig::get('app_ldap_infos_user_mail_field')) ;
    }

    return $infos ;
  }
}
