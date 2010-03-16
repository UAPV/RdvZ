<?php

interface uapvProfileBuilderInterface
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
  public static function buildProfile (&$profile, $id);

}
