<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addmeetingpoll extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('meeting_poll', array(
             'id' => 
             array(
              'type' => 'integer',
              'length' => 8,
              'autoincrement' => true,
              'primary' => true,
             ),
             'date_id' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'poll' => 
             array(
              'type' => 'integer',
              'notnull' => true,
              'length' => 8,
             ),
             'uid' => 
             array(
              'type' => 'integer',
              'length' => 8,
             ),
             'comment' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'participant_name' => 
             array(
              'type' => 'string',
              'length' => 255,
             ),
             'created_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'updated_at' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             ), array(
             'indexes' => 
             array(
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             ));
    }

    public function down()
    {
        $this->dropTable('meeting_poll');
    }
}