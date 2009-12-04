<?php

/**
 * This filter authenticate the user and then identify him 
 *
 * FIXME gérer les déconnections correctement
 * TODO  gérer les différentes politiques de déconnexion
 * TODO  gérer un cookie "remember me"
 *
 * Source code inspired from sfGuardPlugin and 
 * http://www.ja-sig.org/wiki/display/CASC/Symfony+CAS+Client
 */
class uapvSecurityFilterCas extends sfBasicSecurityFilter
{
  public function execute ($filterChain)
  {
    $user = $this->getContext()->getUser();

    // We put an LDAP object in the context in order to reuse it later
    $this->getContext ()->set ('ldap', new uapvLdap ());

    // Filters can be called several times (because of internal forwards) 
    // Authentication is only done the first time
    if ($this->isFirstCall() && 
       (sfConfig::get ('app_cas_server_force_authentication', false) || !$user->isAuthenticated()))
    {
      // phpCAS is not php5-compliant, we remove php warnings and strict errors
      $errorReporting = ini_get ('error_reporting');
      error_reporting ($errorReporting & ~E_STRICT & ~E_NOTICE);

      if (sfConfig::get ('app_cas_server_debug', false))
        phpCAS::setDebug (); // see /tmp/phpCAS.log
        
      phpCAS::client (sfConfig::get ('app_cas_server_version', CAS_VERSION_2_0),
                      sfConfig::get ('app_cas_server_host', 'localhost'),
                      sfConfig::get ('app_cas_server_port', 443),
                      sfConfig::get ('app_cas_server_path', ''),
                      false); // Don't call session_start again,
                              // symfony already did it

      //phpCAS::handleLogoutRequests ();
      phpCAS::setNoCasServerValidation ();
      phpCAS::forceAuthentication (); // if necessary the user will be
                                      // redirected to the cas server

      // At this point the user is authenticated, we log him in
      $user->signIn (phpCAS::getUser ());

      // Previous settings can now be restored
      error_reporting ($errorReporting);
    }

    // "credential" verification
    parent::execute($filterChain);
  }
}

