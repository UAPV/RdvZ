<?php

class uapvProfileFactory {

  /**
   * Les utilisateurs récupérés du LDAP sont mis en cas dans ce tableau.
   * Les clefs du tableau sont les uid des utilisateurs
   * @var array
   */
  private static $profileCache = array ();

  /**
   * Recherche le profil d'un utilisateur par son identifiant
   * La classe du profil construit est déterminée par la variable de configuration
   * app_profile_builder.
   * Une fois contruit, le profil est mis en cache pour toutes les requêtes
   * suivantes.
   *
   * @return uapvBasicProfile
   */
  public static function find ($id)
  {
    if (array_key_exists ($id, self::$profileCache))
      return self::$profileCache [$id];

    // TODO permettre de configurer plusieurs builders
    $profileBuilderClass = sfConfig::get ('app_profile_builder_class',
                                          'uapvProfileBuilderLdap');

    if (! class_exists ($profileBuilderClass))
      throw new sfException ('La classe spécifiée dans le paramètre '
        .'app_profile_builder_class ("'.$profileBuilderClass.'") n\'existe pas');

    $profileClass = sfConfig::get('app_profile_class', 'uapvBasicProfile');
    if (! class_exists ($profileClass))
      throw new sfException ('La classe spécifiée dans le paramètre '
        .'app_profile_class ("'.$profileClass.'") n\'existe pas');

    $profile = new $profileClass ();
    call_user_func_array (array ($profileBuilderClass, 'buildProfile'), array (&$profile, $id));

    return self::$profileCache [$id] = $profile;
  }

}
