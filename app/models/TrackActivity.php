<?php

use Jenssegers\Mongodb\Model as Eloquent;

class TrackActivity extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activities';

    protected $connection = 'mongodb';
    /**
     * Method for creating or updating colletion if it is already exists
     * @param  string $collectionName [description]
     * @return boolean                 [description]
     */
    public function createOrUpdateCollection($collectionName) {
        /*
            couldnt find how to use lavarel Mongodb plugin with creating collection on the fly
            so not using db config here
         */
        $mongo = new Mongo('mongodb://testuser01:testpassword01@ds037368.mongolab.com:37368/testdb01');
        $db = $mongo->selectDB('testdb01');
        $collection = new MongoCollection($db, $collectionName);
        $trackActivityCollection = $mongo->selectDB('testdb01')->createCollection($collectionName);
        $trackActivity = array();
        $trackActivity['activity_id'] = $this->activityId;
        $trackActivity['user_id'] = $this->userId;
        $trackActivity['event'] = $this->event;
        $trackActivity['user_agent'] = $this->userAgent;
        $trackActivity['ip_address'] = $this->ipAddress;
        $trackActivityCollection->save($trackActivity);
        return true;
    }

    /**
     * Method for returning track_xxx collection instance
     * @param  string $collectionName [description]
     * @return instance of mongo db collection                 [description]
     */
    public function getCollectionByBName($collectionName) {
        /*
            couldnt find how to use lavarel Mongodb plugin with creating collection on the fly
            so not using db config here
         */
        $mongo = new Mongo('mongodb://testuser01:testpassword01@ds037368.mongolab.com:37368/testdb01');
        $db = $mongo->selectDB('testdb01');
        $collection = new MongoCollection($db, $collectionName);
        return $collection;
   }

}