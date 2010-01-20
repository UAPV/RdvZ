<?php
/**
 */
class meeting_pollTable extends Doctrine_Table
{
  public function retrieveByDateId($did)
  {
    $q = $this->createQuery('m')
        ->where('m.date_id = ?', $did) ;

    return $q->execute() ;
  }
  
  public function retrieveByUserAndDateId($did,$uid)
  {
    $q = $this->createQuery('m')
        ->where('m.date_id = ?', $did) 
        ->andWhere('m.uid = ?', $uid) ;

    $polls = $q->execute() ;
    return isset($polls[0]) ? $polls[0] : null;
  }
  
  public function getVotesByMeeting($mid)
  {
    $q = Doctrine_Query::create()
        ->select('mp.date_id, sum(poll) as cnt')
        ->from('meeting_poll mp, meeting_date md')
        ->where('md.mid = ?',$mid)
        ->andWhere('md.id = mp.date_id')
        ->orderBy('mp.date_id asc')
        ->groupBy('mp.date_id') ;

    return $q->execute() ;
  }

  public function retrieveByMid($mid)
  {
    $q = Doctrine_Query::create()
        ->select('*')
        ->from('meeting_poll mp, meeting_date md')
        ->where('md.mid = ?',$mid)
        ->andWhere('md.id = mp.date_id') ;

    return $q->execute() ;
  }
}
