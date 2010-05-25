<?php

/**
  * @synopsis : usefull functions
  * @author : Romain Deveaud
  */

class uapvFormExtraUtils
{

  /**
    * TODO: when PHP >= 5.3.0 will be used, rewrite this function
    *       with __callStatic.
    *
    * Get all the LDAP entries the parameter attribute.
    *
    * @param $attr  string 
    * @param $query string
    *
    * @return array
    */
  static public function getLdapEntriesBy($attr, $query)
  {
    $attr = strtolower($attr) ;
    if(is_null(sfConfig::get('app_profile_var_translation_'.$attr)))
      throw new sfException('No profile_var_translation attribute : '.$attr) ;
    
    // We need to use uapvAuthPlugin here...
    $ldap = new uapvLdap() ;

    return self::formatUsers($ldap->search(sfConfig::get('app_profile_var_translation_'.$attr)."=$query*"),10) ;
  }

  /**
    * TODO: write a generic function !
    *
    * Formats the LDAP search results into something usefull.
    *
    * @param $users array
    *
    * @return array
    */ 
  static public function formatUsers($users)
  {
    $result = array() ;

    foreach($users as $user)
      // to be adapted to the datas you need for your application.
      // $result keys are displayed in the field after an item is
      // submitted.
      $result[$user[sfConfig::get('app_profile_var_translation_mail')]] = 
        $user[sfConfig::get('app_profile_var_translation_name')].' '.
        $user[sfConfig::get('app_profile_var_translation_surname')].
        '<div class="autocomplete_user_mail">'.
        $user[sfConfig::get('app_profile_var_translation_mail')].'</div>';

    asort($result) ;
    return $result ;
  }
}
