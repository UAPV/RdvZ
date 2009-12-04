<?php

/**
 * meeting_date filter form base class.
 *
 * @package    rdvz
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id$
 */
class Basemeeting_dateFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'mid'     => new sfWidgetFormDoctrineChoice(array('model' => 'meeting', 'add_empty' => true)),
      'date'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'comment' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'mid'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'meeting', 'column' => 'id')),
      'date'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'comment' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('meeting_date_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting_date';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'mid'     => 'ForeignKey',
      'date'    => 'Date',
      'comment' => 'Text',
    );
  }
}
