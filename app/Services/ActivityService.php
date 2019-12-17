<?php


namespace App\Services;


use App\Helper\NominatimClient;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityService
{

    /**
     * Create new activity
     * @param Request $request
     * @return Activity
     */
    public function createActivity(Request $request) {
        $toSave = $this->buildBasicInformation($request, new Activity());
        $toSave = $this->buildAndSaveImage($request, $toSave);
        $toSave = $this->retrieveCoordinate($toSave);
        $toSave->save();
        return $toSave;
    }

    /**
     * Update new Activity
     * Throw exception if activity not exists
     * @param Request $request
     * @param $id
     * @return Activity
     * @throws \Exception
     */
    public function updateActivity(Request $request, $id) {
        $existing = Activity::find($id);

        //If activity not exist we throw error
        if(!isset($existing)) {
            throw new \Exception("Not activity exist for the given id");
        }

        //If new Image specified delete the old one
        if(isset($request->image_url)) {
            $this->deleteExistingImage($existing);
        }

        $toUpdate = $this->buildBasicInformation($request,$existing);
        $toUpdate = $this->buildAndSaveImage($request, $toUpdate);
        $toUpdate = $this->retrieveCoordinate($toUpdate);
        $toUpdate->update();

        return $toUpdate;

    }

    /**
     * Delete activity
     * @param $id
     */
    public function deleteActivity($id) {
        $existing = Activity::find($id);
        if(isset($existing)) {
            $this->deleteExistingImage($existing);
            $existing->delete();
        }
    }

    private function buildBasicInformation(Request $request, Activity $activity) : Activity
    {
        $activity->name = $request->name;
        $activity->date = $request->date;
        $activity->adress = $request->adress;
        $activity->description = $request->description;
        $activity->creator_name = $request->creator_name;
        $activity->organization = $request->organization;
        $activity->contact = $request->contact;
        $activity->category = $request->category;
        $activity->long = null;
        $activity->lat = null;
        return $activity;
    }

    private function buildAndSaveImage(Request $request, Activity $activity) : Activity{
//        Save image and save url
        if(isset($request->image_base64)) {
            $base64 = base64_decode($request->image_base64);
            $file_extension = pathinfo($request->image_filename, PATHINFO_EXTENSION);
//        Put file in file system
            $path_file = "activities/".Str::random(10).".".$file_extension;
            Storage::put($path_file,$base64);

            $activity->image_url = $path_file;
        }
        return $activity;
    }

    private function deleteExistingImage(Activity $activity) : Activity {
        if(isset($activity->image_url) && Storage::exists($activity->image_url)) {
            Storage::delete($activity->image_url);
        }
        return $activity;
    }

    private function retrieveCoordinate($activity) : Activity {
//        Retrieve lat and long
        $nominatim = new NominatimClient();

        $coordinate = $nominatim->retrieveCoordinate($activity->adress);

        if(isset($coordinate)) {
            $activity->lat = $coordinate["lat"];
            $activity->long = $coordinate["long"];
        }

        return $activity;
    }
}
