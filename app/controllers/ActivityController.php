<?php

class ActivityController extends \BaseController {

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

        $activity = new Activity();
        return json_encode($activity->getActivity($id));
    }
}