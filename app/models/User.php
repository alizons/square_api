<?php


use Jenssegers\Mongodb\Model as Eloquent;

class User extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
    const CACHE_KEY_PREFIX = 'user';

    protected $connection = 'mongodb';

	/**
     * Method for saving user data into mongo db
     * @param  int $userId [description]
     * @param  string $event  [description]
     * @return boolean         [description]
     */
    public function saveUser($userId, $event) {
        try {
            $users = User::where('userId', '=', $userId)->get();
            if (isset($users[0])) {
                $user = $users[0];
                $user->$event = $user->$event + 1;
                $user->save();
            }
            else {
                $user = new User();
                $user->userId = $userId;
                $user->enter = 0;
                $user->click = 0;
                $user->exit  = 0;
                $user->$event = 1;
                $user->save();
            }
            Cache::forget(self::CACHE_KEY_PREFIX . '_' . $userId);
            return true;
        }
        catch (Exception $e) {
            //log error
        }
        return false;
    }
    /**
     * Method for getting user information by its ID.
     * This method also using memcached to cache the data for 1 hour
     * @param  int $userId [description]
     * @return array         [description]
     */
    public function getUser($userId) {
        try {
            $key = self::CACHE_KEY_PREFIX . '_' . $userId;
            if (Cache::has($key)) {
                $userData = Cache::get($key);
                return $userData;
            }
            $users = User::where('userId', '=', 1)->get();
            $user = $users[0];
            $data['user_id'] = $user->userId;
            $data['enter'] = $user->enter;
            $data['click'] = $user->click;
            $data['exit'] = $user->exit;
            $data['updated_at'] = $user->updated_at;
            $data['created_at'] = $user->created_at;
            Cache::add($key, $data, 60);
            return $data;
        }
        catch (Exception $e)  {
            //log error
        }
        return array();
    }



}