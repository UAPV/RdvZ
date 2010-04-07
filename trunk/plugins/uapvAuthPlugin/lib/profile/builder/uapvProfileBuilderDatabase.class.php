<?php

/**
 * Cette classe construit un profil à partir d'une base de données
 */
class uapvProfileBuilderDatabase implements uapvProfileBuilderInterface
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
    $bd = new uapvDB() ;
    if (($result = $bd->getUser($id)) !== null) 
      $profile->add($result) ; 
  }
}
