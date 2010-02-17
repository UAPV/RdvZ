<?php
/**
 */
class meeting_dateTable extends Doctrine_Table
{
  public function retrieveByMid($mid)
  {
    $q = $this->createQuery('m')
        ->where('m.mid = ?', $mid)
        ->orderBy('m.date') ;

    return $q->execute() ;
  }


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
