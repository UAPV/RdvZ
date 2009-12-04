<?php

/**
 * user form base class.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class BaseuserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'login'   => new sfWidgetFormInputText(),
      'pass'    => new sfWidgetFormInputText(),
      'name'    => new sfWidgetFormInputText(),
      'surname' => new sfWidgetFormInputText(),
      'mail'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => 'user', 'column' => 'id', 'required' => false)),
      'login'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'pass'    => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'name'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'surname' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mail'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'user';
  }

}
