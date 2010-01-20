<?php
/**
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

  public function hashExists($hash)
  {
    $q = $this->createQuery('m')
      ->where('m.hash = ?',$hash) ;

    return count($q) ;
  }

  public function getMeetingsFromUser($uid)
  {
    $q = $this->createQuery('m')
      ->where('m.uid = ?',$uid) ;

    return $q->execute() ;
  }

  public function getByHash($hash)
  {
    $q = $this->createQuery('m')
      ->where('m.hash = ?',$hash) ;

    $meets = $q->execute() ;
    return isset($meets[0]) ? $meets[0] : null;
  }
  
  public function getByDateId($did)
  {
    $q = $this->createQuery('m')
      ->leftJoin('m.meeting_dates d')
      ->where('d.id = ?',$did) ;

    $meets = $q->execute() ;
    return isset($meets[0]) ? $meets[0] : null;
  }

  public function getWithNoTitle()
  {
    $q = $this->createQuery('m')
      ->where("m.title = ''") ;

    return $q->execute() ;
  }
}
