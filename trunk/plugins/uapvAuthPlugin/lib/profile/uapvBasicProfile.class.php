<?php

/**
 * Un profil permet de stocker des valeurs extraites de différentes sources 
 * de données.
 *
 * Voir la documentation de la classe sfParameterHolder.
 *
 * La différence avec sfParameterHolder est qu'il est possible d'utiliser
 * des "alias" pour récupérer des éléments. Cela permet ainsi d'adapter
 * une application à un annuaire LDAP ou une base de données ayant un
 * schéma différent.
 *
 * Ces alias sont définis dans la variable de configuration :
 *   app_profile_var_translation
 */
class uapvBasicProfile extends sfParameterHolder
{
  protected static $varTranslation = null;

  public function & get ($name, $default = null) 
  {
    if (self::$varTranslation === null)
       self::$varTranslation = sfConfig::get ('app_profile_var_translation', array ());

    if (array_key_exists ($name, self::$varTranslation))
      $name = strtolower (self::$varTranslation [$name]);

    return parent::get ($name, $default);
  }

  public function __tostring ()
  {
    return self::get ('displayName');
  }
}
