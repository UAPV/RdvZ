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

  public function getByUid($uid,$mid)
  {
    $q = Doctrine_Query::create()
         ->select('*')
         ->from('meeting_poll mp, meeting_date md')
         ->where('md.mid = ?',$mid)
         ->andWhere('mp.uid = ?', $uid) 
         ->andWhere('md.id = mp.date_id');

    return $q->execute() ;
  }

  public function getByParticipantName($name,$mid)
  {
    $q = Doctrine_Query::create()
         ->select('*')
         ->from('meeting_poll mp, meeting_date md')
         ->where('md.mid = ?',$mid)
         ->andWhere('mp.participant_name = ?', $name) 
         ->andWhere('md.id = mp.date_id');

    return $q->execute() ;
  }

  public function retrieveUidByMeetingId($mid)
  {
    //$q = $this->createQuery('m')
    $q = Doctrine_Query::create()
         ->select('DISTINCT mp.uid')
         ->from('meeting_poll mp, meeting_date md')
         ->where('md.mid = ?',$mid)
         ->andWhere('mp.date_id = md.id')
         ->andWhere('mp.uid is not null') ;

    $r = $q->fetchArray() ;
    $res = array() ;

    foreach($r as $l)
      if(!in_array($l['uid'],$res)) $res[] = $l['uid'] ;

    return $res ;
  }

  public function retrieveNameByMeetingId($mid)
  {
    //$q = $this->createQuery('m')
    $q = Doctrine_Query::create()
         ->select('DISTINCT mp.participant_name')
         ->from('meeting_poll mp, meeting_date md')
         ->where('md.mid = ?',$mid)
         ->andWhere('mp.date_id = md.id')
         ->andWhere('mp.participant_name is not null') ;

    $r = $q->fetchArray() ;
    $res = array() ;

    foreach($r as $l)
      if(!in_array($l['participant_name'],$res)) $res[] = $l['participant_name'] ;

    return $res ;
  }
}
