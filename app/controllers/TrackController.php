<?php

class TrackController extends \BaseController {

    public $restful = true;

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($activityId, $userId, $event)
	{
        $collectionName = 'Track_' . $activityId;
        $trackActivity = new TrackActivity();
        $trackActivity->activityId = $activityId;
        $trackActivity->userId = $userId;
        $trackActivity->event = $event;
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $trackActivity->userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        $trackActivity->ipAddress = $_SERVER['REMOTE_ADDR'];
        $trackActivity->createOrUpdateCollection($collectionName);

        $activity = new Activity();
        $activity->saveActivity($activityId, $event);

        $user = new User();
        $user->saveUser($userId, $event);

        return json_encode(array('errors' => 0));
    }

}