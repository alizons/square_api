<?php

use Jenssegers\Mongodb\Model as Eloquent;

class Activity extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activities';
    const CACHE_KEY_PREFIX = 'activity';

    protected $connection = 'mongodb';
    /**
     * Method for saving activity instance into mongo DB
     * @param  string $activityId [description]
     * @param  string $event      [description]
     * @return  boolean            [description]
     */
    public function saveActivity($activityId, $event) {
        try {
            $activity = self::where('activityId', '=', $activityId)->get();
            if (isset($activity[0])) {
                $activity = $activity[0];
                $activity->$event = $activity->$event + 1;
                $activity->save();
            }
            else {
                $activity = new self();
                $activity->activityId = $activityId;
                $activity->enter = 0;
                $activity->click = 0;
                $activity->exit  = 0;
                $activity->$event = 1;
                $activity->save();
            }
            Cache::forget(self::CACHE_KEY_PREFIX . '_' . $activityId);
            return true;
        }
        catch (Exception $e) {
            //log error here
        }
    }

    /**
     * Method for getting activity information by its ID.
     * This method also using memcached to cache the data for 1 hour
     * @param  int $activityId [description]
     * @return array         [description]
     */
    public function getActivity($activityId) {
        try {
            $key = self::CACHE_KEY_PREFIX . '_' . $activityId;
            if (Cache::has($key)) {
                $data = Cache::get($key);
                return $data;
            }
            $activity = Activity::where('activityId', '=', $activityId)->get();
            if (!isset($activity[0])) {
                return json_encode(array());
            }
            $activity = $activity[0];
            $data = array();
            $data['activity_id'] = $activity->activityId;
            $data['enter'] = $activity->enter;
            $data['click'] = $activity->click;
            $data['exit'] = $activity->exit;
            $data['updated_at'] = $activity->updated_at;
            $data['created_at'] = $activity->created_at;
            Cache::add($key, $data, 60);
            return $data;
        }
        catch (Exception $e)  {
            //log error
        }
        return array();
    }
}