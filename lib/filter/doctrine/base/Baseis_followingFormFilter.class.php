<?php

/**
 * is_following filter form base class.
 *
 * @package    rdvz
 * @subpackage filter
 * @author     Romain Deveaud <romain.deveaud@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class Baseis_followingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('meeting'), 'add_empty' => true)),
      'uid' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'mid' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('meeting'), 'column' => 'id')),
      'uid' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('user'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('is_following_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'is_following';
  }

  public function getFields()
  {
    return array(
      'id'  => 'Number',
      'mid' => 'ForeignKey',
      'uid' => 'ForeignKey',
    );
  }
}
