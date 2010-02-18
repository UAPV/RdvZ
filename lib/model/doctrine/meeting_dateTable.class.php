<?php
/**
  * Contains the Doctrine SQL request on the meeting_date table.
  *
  * @author     Romain Deveaud <romain.deveaud@univ-avignon.fr>
  * @project    RdvZ v2.0
  */
class meeting_dateTable extends Doctrine_Table
{
  /**
    * Get all the dates from the meeting $mid.
    *
    * @param $mid integer The meeting id.
    * @return array Doctrine_Record The dates.
    */
  public function retrieveByMid($mid)
  {
    $q = $this->createQuery('m')
        ->where('m.mid = ?', $mid)
        ->orderBy('m.date') ;

    return $q->execute() ;
  }

  /**
    * Get the date with this id and this meeting.
    *
    * @param $did integer The date id.
    * @param $mid integer The meeting id.
    * @return Doctrine_Record The date.
    */
  public function findWithMid($did,$mid)
  {
    $q = $this->createQuery('m')
        ->where('m.mid = ?', $mid)
        ->andWhere('m.id = ?', $did)
        ->orderBy('m.date') ;

    $dates = $q->execute() ;
    return isset($dates[0]) ? $dates[0] : null;
  }
}
