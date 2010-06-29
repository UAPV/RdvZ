<?php

/**
 * doc actions.
 *
 * @package    rdvz
 * @subpackage doc
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class docActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    require_once(sfConfig::get('sf_lib_dir').'/vendor/markdown.php');

    $d = file_get_contents(sfConfig::get('sf_web_dir').'/doc/user_doc_'.$this->getUser()->getCulture().'.markdown') ;

    $d = htmlentities($d, ENT_QUOTES, 'UTF-8');
    $this->body = Markdown($d) ;
  }
}
