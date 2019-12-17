<?php

namespace App\Http\Controllers;

use App\Helper\NominatimClient;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use maxh\Nominatim\Nominatim;


class ActivityController extends Controller
{
    public function create(Request $request) {

//        TODO : Make validation
        $activity = new Activity;

//       Set basic information
        $activity->name = $request->name;
        $activity->date = $request->date;
        $activity->adress = $request->adress;
        $activity->description = $request->description;
        $activity->creator_name = $request->creator_name;
        $activity->organization = $request->organization;
        $activity->contact = $request->contact;
        $activity->category = $request->category;


//       Save image and save url
        if(isset($request->image_base64)) {
            $base64 = base64_decode($request->image_base64);
            $file_extension = pathinfo($request->image_filename, PATHINFO_EXTENSION);
//        Put file in file system
            $path_file = "activities/".Str::random(10)."/".$file_extension;
            Storage::put($path_file,$base64);

            $activity->image_url = $path_file;
        }

//        Retrieve lat and long
//        TODO : Retrieve lat and long
        $nominatim = new NominatimClient("https://nominatim.openstreetmap.org/");

        $coordinate = $nominatim->retrieveCoordinate($request->adress);

        if(!isset($coordinate)) {
            //TODO : Que faire si pas de coordinate
        }

        $activity->lat = $coordinate["lat"];
        $activity->long = $coordinate["long"];

//        Save activity
        $activity->save();


        return $this->activityToActivityJson($activity);
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
