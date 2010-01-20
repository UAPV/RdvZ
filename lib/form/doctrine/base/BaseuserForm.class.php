<?php

/**
 * user form base class.
 *
 * @method user getObject() Returns the current form's model object
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseuserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'ldap_id' => new sfWidgetFormInputText(),
      'login'   => new sfWidgetFormInputText(),
      'pass'    => new sfWidgetFormInputText(),
      'name'    => new sfWidgetFormInputText(),
      'surname' => new sfWidgetFormInputText(),
      'mail'    => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'ldap_id' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'login'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'pass'    => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'name'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'surname' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mail'    => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'user', 'column' => array('ldap_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'user', 'column' => array('login'))),
      ))
    );

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
