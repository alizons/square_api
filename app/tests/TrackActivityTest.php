<?php

class TrackActivityTest extends TestCase {

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testTrackActivity() {

        $activityId = 1;
        $userId = 1;
        $event = 'click';
        $collectionName = 'Track_' . $activityId;

        $trackActivity = new TrackActivity();
        $trackActivity->activityId = $activityId;
        $trackActivity->userId = $activityId;
        $trackActivity->event = $event;
        $trackActivity->userAgent = 'osx';
        $trackActivity->ipAddress = '127.0.0.1';
        $trackActivity->createOrUpdateCollection($collectionName);

        $collection = $trackActivity->getCollectionByBName($collectionName);
        $cursor = $collection->find(array('activity_id' => $activityId));
        foreach ($cursor as $doc) {
            $this->assertEquals($doc['activity_id'], $activityId);
            $this->assertEquals($doc['user_agent'], 'osx');
            $this->assertEquals($doc['ip_address'], '127.0.0.1');
            break;
        }
    }

    public function testActivity() {
        $activityId = 1;
        $event = 'click';
        $activity = Activity::where('activityId', '=', $activityId)->get();
        if (isset($activity[0])) {
            $activity = $activity[0];
            $counter = $activity->$event + 1;
            $activity->$event = $counter;
            $activity->save();
        }
        else {
            $counter = 1;
            $activity = new Activity();
            $activity->activityId = $activityId;
            $activity->enter = 0;
            $activity->click = 0;
            $activity->exit  = 0;
            $activity->$event = 1;
            $activity->save();
        }

        $activity = Activity::where('activityId', '=', $activityId)->get();
        $activity = $activity[0];
        $this->assertEquals($activity->activityId, $activityId);
        $this->assertEquals($activity->$event, $counter);


    }

    public function testUser () {
        $userId = 1;
        $event = 'click';
        $users = User::where('userId', '=', $userId)->get();

        if (isset($users[0])) {
            $user = $users[0];
            if (is_object($user)) {
                $counter = $user->$event + 1;
                $user->$event = $counter;
                $user->save();
            }
        }
        else {
            $counter = 1;
            $user = new User();
            $user->userId = $userId;
            $user->enter = 0;
            $user->click = 0;
            $user->exit  = 0;
            $user->$event = 1;
            $user->save();
        }

        $users = User::where('userId', '=', $userId)->get();
        $user = $users[0];

        $this->assertEquals($user->userId, $userId);
        $this->assertEquals($user->$event, $counter);

    }
}