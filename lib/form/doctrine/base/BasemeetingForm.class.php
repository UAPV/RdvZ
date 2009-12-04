<?php

/**
 * meeting form base class.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class BasemeetingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'hash'        => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'uid'         => new sfWidgetFormInputText(),
      'closed'      => new sfWidgetFormInputText(),
      'date_del'    => new sfWidgetFormDateTime(),
      'date_end'    => new sfWidgetFormDateTime(),
      'aifna'       => new sfWidgetFormInputText(),
      'notif'       => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'meeting', 'column' => 'id', 'required' => false)),
      'hash'        => new sfValidatorString(array('max_length' => 6)),
      'title'       => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('max_length' => 4000)),
      'uid'         => new sfValidatorInteger(array('required' => false)),
      'closed'      => new sfValidatorInteger(array('required' => false)),
      'date_del'    => new sfValidatorDateTime(array('required' => false)),
      'date_end'    => new sfValidatorDateTime(array('required' => false)),
      'aifna'       => new sfValidatorInteger(array('required' => false)),
      'notif'       => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('meeting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting';
  }

}
