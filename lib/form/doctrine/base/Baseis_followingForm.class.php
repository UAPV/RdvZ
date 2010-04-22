<?php

/**
 * is_following form base class.
 *
 * @method is_following getObject() Returns the current form's model object
 *
 * @package    rdvz
 * @subpackage form
 * @author     Romain Deveaud <romain.deveaud@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class Baseis_followingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'  => new sfWidgetFormInputHidden(),
      'mid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('meeting'), 'add_empty' => true)),
      'uid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'mid' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('meeting'), 'required' => false)),
      'uid' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('is_following[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'is_following';
  }

}
