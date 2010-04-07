<?php

/**
 * auth actions.
 *
 * @package    rdvz
 * @subpackage auth
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class authActions extends sfActions
{
  public function executeMh(sfWebRequest $request)
  {
    //$this->meeting = $request->getParameter('m') ;
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('m')) ;
    $this->forward404Unless($this->meeting);

    if($this->getUser()->isAuthenticated())
      $this->redirect($this->meeting->getHash().'/show') ;
  }

  public function executeWrongcred(sfWebRequest $request)
  {
    $this->ref = $request->getReferer() ;
  }

  public function executeResetauth(sfWebRequest $request)
  {
    $this->getUser()->clearCredentials() ;
    $this->getUser()->setAuthenticated(false) ;

    $this->redirect('homepage') ;
  }
}
