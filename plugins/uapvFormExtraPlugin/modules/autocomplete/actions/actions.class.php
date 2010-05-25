<?php

/**
  * TODO: doc
  *
  * Add your own search actions for databases, whatever...
  */

class autocompleteActions extends sfActions
{
  /**
    * Action called by the uapvWidgetFormJQueryAutocompleter for a ldap
    * search.
    */
  public function executeLdap(sfWebRequest $request)
  {
    if($request->isXmlHttpRequest())
    {
      $query = $request->getParameter('q') ;
      $attr  = $request->getParameter('attr') ;

      if(strlen($query) >= sfConfig::get('app_uapv_form_extra_autocomplete_query_length'))
        // Get the infos and format the response
        return $this->renderText(json_encode(uapvFormExtraUtils::getLdapEntriesBy($attr,$query))) ;
      else
        return $this->renderText(json_encode(array())) ;
    }
    else
      return sfView::NONE;
  }
}
