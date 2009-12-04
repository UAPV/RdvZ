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
}
