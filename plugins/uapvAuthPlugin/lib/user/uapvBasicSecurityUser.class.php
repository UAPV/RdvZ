<?php

/**
 *
 * TODO Documenter
 *
 */
class uapvBasicSecurityUser extends sfBasicSecurityUser
{
  const SESSION_NAMESPACE = 'uapvBasicSecurityUser';

  /**
   * @var uapvBasicProfile
   */
  protected $profile = null;

  /**
   * @return boolean
   */
  public function isAnonymous ()
  {
    return ! $this->isAuthenticated();
  }

  /**
   * Log user in
   * @param string $login Username
   */
  public function signIn ($login)
  {
    sfContext::getInstance()->getLogger()->log('User "'.$login.'" sign in.');
    
    // enregistrement dans la session de l'utilisateur
    $this->setAttribute ('login', $login, self::SESSION_NAMESPACE);
    $this->setAuthenticated (true);
    $this->clearCredentials ();

    // récupération du profil
    $profile = uapvProfileFactory::find ($login);
    if ($profile !== null)
      $this->setProfile ($profile);

    // on passe la main à l'application pour qu'elle configure les autorisation
    // de l'utilisateur courant
    if (method_exists ($this, 'configure'))
      $this->configure();
  }

  /**
   * Log user out
   */
  public function signOut()
  {
    $this->getAttributeHolder()->removeNamespace (self::SESSION_NAMESPACE);
    $this->profile = null;
    $this->clearCredentials();
    $this->setAuthenticated(false);
  }

  /**
   * @return uapvBasicProfile
   */
  public function getProfile ()
  {
    if ($this->profile !== null)
      return $this->profile;

    if ($this->hasAttribute ('profile', self::SESSION_NAMESPACE))
      return $this->profile = unserialize ($this->getAttribute ('profile', null, self::SESSION_NAMESPACE));
    
    return null;
  }

  /**
   * @param $profile uapvBasicProfile
   */
  public function setProfile ($profile)
  {
    $this->profile = $profile;
  }

  /**
   * @return boolean
   */
  public function hasProfile ()
  {
    return ($this->profile !== null || $this->hasAttribute ('profile', self::SESSION_NAMESPACE));
  }

  /**
   * Retourne la valeur d'une donnée de profil
   */
  public function getProfileVar ($name, $default = null)
  {
    if (! $this->hasProfile ())
      return $default;

    return $this->getProfile()->get ($name, $default);
  }

  /**
   * Cette fonction est appelée lorsque la requête a été traitée
   * On serialise a ce moment là le profil afin de le stocker dans la session
   *
   * @see sfBasicSecurityUser.shutdown()
   */
  public function shutdown ()
  {
    if ($this->profile !== null)
      $this->setAttribute ('profile', serialize ($this->profile), self::SESSION_NAMESPACE);

    parent::shutdown ();
  }
}
