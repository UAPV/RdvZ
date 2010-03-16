<?php
/**
  * Contains the Doctrine SQL request on the meeting_poll table.
  *
  * @author     Romain Deveaud <romain.deveaud@univ-avignon.fr>
  * @project    RdvZ v2.0
  */
class meeting_pollTable extends Doctrine_Table
{
  /**
    * Get all the votes associated to a date.
    *
    * @param $did integer The date id.
    * @return array Doctrine_Record The votes.
    */
  public function retrieveByDateId($did)
  {
    $q = $this->createQuery('m')
        ->where('m.date_id = ?', $did) ;

    return $q->execute() ;
  }
  
  /**
    * Get all the votes associated to a date and a user.
    *
    * @param $did integer The date id.
    * @param $uid integer The user id.
    * @return array Doctrine_Record The votes.
    */
  public function retrieveByUserAndDateId($did,$uid)
  {
    $q = $this->createQuery('m')
        ->where('m.date_id = ?', $did) 
        ->andWhere('m.uid = ?', $uid) ;

    $polls = $q->execute() ;
    return isset($polls[0]) ? $polls[0] : null;
  }

  /**
    * Get all the votes associated to a date and a user name.
    *
    * @param $did integer The date id.
    * @param $uname string The participant_name.
    * @return array Doctrine_Record The votes.
    */
  public function retrieveByUserNameAndDateId($did,$uname)
  {
    $q = $this->createQuery('m')
        ->where('m.date_id = ?', $did) 
        ->andWhere('m.participant_name = ?', $uname) ;

    $polls = $q->execute() ;
    return isset($polls[0]) ? $polls[0] : null;
  }
  
  /**
    * Get the sum of votes associated to a meeting, grouped by date.
    *
    * @param $mid integer The meeting id.
    * @return array The dates with the vote counts.
    */
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

  /**
    * Get all the votes associated to a meeting.
    *
    * @param $mid integer The meeting id.
    * @return array Doctrine_Record The votes.
    */
  public function retrieveByMid($mid)
  {
    $q = Doctrine_Query::create()
        ->select('*')
        ->from('meeting_poll mp, meeting_date md')
        ->where('md.mid = ?',$mid)
        ->andWhere('md.id = mp.date_id') ;

    return $q->execute() ;
  }

  /**
    * Get all the votes associated to a meeting and a user.
    *
    * @param $uid integer The user id.
    * @param $mid integer The meeting id.
    * @return array Doctrine_Record The votes.
    */
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

  /**
    * Get all the votes associated to a meeting and an extern user.
    *
    * @param $name string The user participant_name.
    * @param $mid integer The meeting id.
    * @return array Doctrine_Record The votes.
    */
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

  /**
    * Get all the user ids from the votes of a meeting.
    *
    * @param $mid integer The meeting id.
    * @return array integer The user ids.
    */
  public function retrieveUidByMeetingId($mid)
  {
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

  /**
    * Get all the extern user names from the votes of a meeting.
    *
    * @param $mid integer The meeting id.
    * @return array string The user names.
    */
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
