<?php

/**
 * authentication actions.
 */
class authenticationActions extends sfActions
{
  public function executeLogin (sfWebRequest $request)
  {
    $this->form = new uapvLoginForm() ;

    if($request->isMethod('post'))
    {
      $form_info = $request->getParameter('login') ;
      $this->form->bind($form_info);
      if ($this->form->isValid())
      {
        // Quel type d'authentification a été choisit lors de l'installation?
        $auth_type = sfConfig::get('app_authentication_type') ;
        if($auth_type == 'bd')
        {
          $bd = new uapvDB() ;
          $resp = $bd->checkPassword($form_info['username'], $form_info['password']) ;
          if($resp)
          {
            $this->getContext()->getUser()->signIn($form_info['username']) ;
            $this->getContext()->getUser()->addCredentials('member') ;
            $this->redirect($request->getReferer()) ;
          }
          else
            $this->getContext()->getUser()->setFlash('error', 'Identifiant ou mot de passe erroné.') ;
        }
        else if($auth_type == 'ldap')
        {
          $ldap = new uapvLdap() ;

          // "uid=..." à changer, pour utiliser les paramètres de configuration
          // pour que ça marche avec un LDAP qui n'a pas des uid mais des trululuid.
          $resp = $ldap->checkPassword("uid=".$form_info['username'], $form_info['password']) ;
          
          if($resp)
          {
            // Si l'utilisateur a entré le bon login et le bon mdp, on l'autorise
            // à accéder à l'appli.
            $this->getContext()->getUser()->signIn($form_info['username']) ;
            $this->getContext()->getUser()->addCredentials('member') ;
            $this->redirect($request->getReferer()) ;
          }
          else
            $this->getContext()->getUser()->setFlash('error', 'Identifiant ou mot de passe erroné.') ;
        }
      }
    }
  }

  public function executeLogout (sfWebRequest $request) 
  {
    $this->getContext()->getUser()->signOut ();
    error_reporting (ini_get ('error_reporting') & ~E_STRICT & ~E_NOTICE);

    // Le filtre uapvSecurityFilterCas n'ayant pas forcément été déclanché
    // on force l'appel phpCas::client()
    phpCAS::client (sfConfig::get ('app_cas_server_version', CAS_VERSION_2_0),
                    sfConfig::get ('app_cas_server_host', 'localhost'),
                    sfConfig::get ('app_cas_server_port', 443),
                    sfConfig::get ('app_cas_server_path', ''),
                    false); // Don't call session_start again,
                            // symfony already did it

    // Redirection vers le CAS
    phpCAS::logoutWithRedirectService ($request->getParameter ('redirect',
                                         $this->getContext ()
                                              ->getController ()
                                              ->genUrl ('@homepage', true)));
  }
}
