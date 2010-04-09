<?php
/**
  * Contains the Doctrine SQL request on the meeting table.
  *
  * @author     Romain Deveaud <romain.deveaud@univ-avignon.fr>
  * @project    RdvZ v2.0
  */
class meetingTable extends Doctrine_Table
{
  static public $choices = array(
    '0' => 'Non',
    '1' => 'Oui'
  ) ;

  public function getChoices()
  {
    return self::$choices ;
  }

  /**
    * Says if the hash code already exists in the database.
    *
    * @param $hash string The hash code.
    * @return bool
    */
  public function hashExists($hash)
  {
    $q = $this->createQuery('m')
      ->where('m.hash = ?',$hash) ;

    return count($q) ;
  }

  /**
    * Get the meetings created by a user.
    *
    * @param $uid integer The user id.
    * @return array The meetings.
    */
  public function getMeetingsFromUser($uid)
  {
    $q = $this->createQuery('m')
      ->where('m.uid = ?',$uid) ;

    return $q->execute() ;
  }

  /**
    * Get the unique meeting associated to a hash code.
    *
    * @param $hash string The hash code.
    * @return Doctrine_Record The meeting.
    */
  public function getByHash($hash)
  {
    $q = $this->createQuery('m')
      ->where('m.hash = ?',$hash) ;

    $meets = $q->execute() ;
    return isset($meets[0]) ? $meets[0] : null;
  }
  
  /**
    * Get the unique meeting associated to a date.
    *
    * @param $did integer The date id.
    * @return Doctrine_Record The meeting.
    */
  public function getByDateId($did)
  {
    $q = $this->createQuery('m')
      ->leftJoin('m.meeting_dates d')
      ->where('d.id = ?',$did) ;

    $meets = $q->execute() ;
    return isset($meets[0]) ? $meets[0] : null;
  }

  public function getExpiredMeetingsNotClosed($limit = 10)
  {
    $q = $this->createQuery('m')
      ->where('m.date_end < ?', strftime("%F",time()))
      ->andWhere('m.closed = ? ', 0)
      ->limit($limit) ;

    return $q->execute() ;
  }

  public function getMeetingsToDelete($limit = 10)
  {
    $q = $this->createQuery('m')
      ->where('m.date_del < ?', strftime("%F",time()))
      ->limit($limit) ;

    return $q->execute() ;
  }
}
