<?php

/**
 * meeting_poll form base class.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class Basemeeting_pollForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'date_id'          => new sfWidgetFormDoctrineChoice(array('model' => 'meeting_date', 'add_empty' => false)),
      'poll'             => new sfWidgetFormInputText(),
      'uid'              => new sfWidgetFormInputText(),
      'comment'          => new sfWidgetFormInputText(),
      'participant_name' => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'meeting_poll', 'column' => 'id', 'required' => false)),
      'date_id'          => new sfValidatorDoctrineChoice(array('model' => 'meeting_date')),
      'poll'             => new sfValidatorInteger(),
      'uid'              => new sfValidatorInteger(array('required' => false)),
      'comment'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'participant_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('meeting_poll[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting_poll';
  }

}
