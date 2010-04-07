<?php

/**
 * language actions.
 *
 * @package    rdvz
 * @subpackage language
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class languageActions extends sfActions
{
  public function executeChange(sfWebRequest $request)
  {
    $languages = sfConfig::get('app_languages') ;
               //array('fr','en') ; // it would be nice if this variable
                                    // could be global to the application...

    $lang = $request->getParameter('lang') ;
    
    if(array_key_exists($lang,$languages))
    {
      $user = Doctrine::getTable('user')->find($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
      $user->setLanguage($lang) ;
      $user->save() ;
      $this->getUser()->setCulture($lang) ;
    }

    $this->redirect($request->getReferer()) ;
  }
}
