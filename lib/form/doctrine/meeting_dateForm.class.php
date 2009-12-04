<?php

/**
 * meeting_date form.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class meeting_dateForm extends Basemeeting_dateForm
{
  public function configure()
  {
    unset($this['id'],$this['mid']) ;

    for($i = date('Y'); $i<(date('Y')+5); $i++)
      $years[$i] = $i;

    $this->widgetSchema['date'] = new sfWidgetFormJQueryI18nDate(array(
          'config' => '',
          'culture' => 'fr',
          'years' => array_combine($years, $years),
          'image' => '"/images/30.png"'
          ), array('class' => 'dynamic_date'));

    $this->widgetSchema->setLabels(array(
      'date' => 'Date : ',
      'comment' => 'Commentaire : '
      ));
    $this->setValidators(array(
        'date' => new sfValidatorDate (array('min' => date('Y-m-d')), array('required' => 'Veuillez entrer une date.','invalid' => 'Vous n\'avez pas correctement entré la date', 'min' => 'Vous ne pouvez pas choisir une date dépassée.')),
            )) ;  
    $this->validatorSchema->setOption('allow_extra_fields', true) ;
    $this->validatorSchema->setOption('filter_extra_fields', false) ;
  }
}
