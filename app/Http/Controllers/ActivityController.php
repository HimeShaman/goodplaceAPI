<?php

namespace App\Http\Controllers;

use App\Helper\NominatimClient;
use App\Models\Activity;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use maxh\Nominatim\Nominatim;


class ActivityController extends Controller
{


    private $activityService;

    /**
     * ActivityController constructor.
     */
    public function __construct()
    {
        $this->activityService = new ActivityService();
    }



    public function create(Request $request) {
        return $this->activityToActivityJson($this->activityService->createActivity($request));
    }

    public function delete(Request $request,$id) {
        return $this->activityService->deleteActivity($id);
    }

    public function update(Request $request, $id) {
        return $this->activityToActivityJson($this->activityService->updateActivity($request,$id));
    }

    public function find(Request $request){
        $activitiesJson = [];
        foreach (Activity::all() as $activity) {
            $activitiesJson[] = $this->activityToActivityJson($activity);
        }
        Activity::all();

        return $activitiesJson;
    }

    private function activityToActivityJson($activity) {
        $activityJson =  [
            "id" => $activity->id,
            "name" => $activity->name,
            "date" => $activity->date,
            "adress" => $activity->adress,
            "description" => $activity->description,
            "creator_name" => $activity->creator_name,
            "organization" => $activity->organization,
            "contact" => $activity->contact,
            "long" => $activity->long,
            "lat" => $activity->lat
        ];

        if(isset($activity->image_url)) {
            $activityJson["image_url"] = asset(Storage::url($activity->image_url));
        }

        return $activityJson;
    }
}
