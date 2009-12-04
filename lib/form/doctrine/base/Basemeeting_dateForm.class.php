<?php

/**
 * meeting_date form base class.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class Basemeeting_dateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'mid'     => new sfWidgetFormDoctrineChoice(array('model' => 'meeting', 'add_empty' => false)),
      'date'    => new sfWidgetFormDateTime(),
      'comment' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => 'meeting_date', 'column' => 'id', 'required' => false)),
      'mid'     => new sfValidatorDoctrineChoice(array('model' => 'meeting')),
      'date'    => new sfValidatorDateTime(),
      'comment' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('meeting_date[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting_date';
  }

}
