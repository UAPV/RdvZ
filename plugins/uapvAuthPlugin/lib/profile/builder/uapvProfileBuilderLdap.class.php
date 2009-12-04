<?php

/**
 * Cette classe construit un profil à partir d'une entrée LDAP
 */
class uapvProfileBuilderLdap implements uapvProfileBuilderInterface
{
  /**
   * Récupère les données d'un utilisateur et les place dans le profil
   * passé en paramètre.
   *
   * @param uapvBasicProfile $profile   Profil à remplir
   * @param string           $id        Identifiant de l'utilisateur
   *
   * @return void
   */
  public static function buildProfile (&$profile, $id)
  {
    if (($result = self::getLdap ()->searchOne ("uid=$id")) !== null)
      $profile->add ($result);
  }

  /**
   * @return uapvLdap 
   */
  private static function getLdap ()
  {
    return sfContext::getInstance()->get('ldap');
  }
}
