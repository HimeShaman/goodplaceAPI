<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;


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
        try{

            //Process validation
            $validator = Validator::make($request->all(),$this->activityService->buildValidationRules());


            if($validator->fails()) {
                return response()->json([
                    "error" => 400,
                    "message" => $validator->errors()->all()
                ],400);
            }

            //Build and save
            return $this->activityToActivityFullJson($this->activityService->createActivity($request));
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => 500, 'message' => $e->getMessage()],500);
        }
    }

    public function delete(Request $request,$id) {
        try{
            return $this->activityService->deleteActivity($id);
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => 500, 'message' => $e->getMessage()],500);
        }
    }

    public function update(Request $request, $id) {
        try{


            //Process validation
            $validator = Validator::make($request->all(),$this->activityService->buildValidationRules());

            if($validator->fails()) {
                return response()->json([
                    "error" => 400,
                    "message" => $validator->errors()->all()
                ],400);
            }

            //Build and save
            return $this->activityToActivityFullJson($this->activityService->updateActivity($request,$id));
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => 500, 'message' => $e->getMessage()],500);
        }
    }

    public function find(Request $request){
        try{
            $activitiesJson = [];
            $activites = $this->activityService->findByCriteria($request);
            foreach ( $activites as $activity) {
                $activitiesJson[] = $this->activityToActivitySearchJson($activity);
            }
            return $activitiesJson;
        }catch (\Exception $e) {
            Log::error($e);
            return response()->json(["error" => 500, 'message' => $e->getMessage()],500);
        }
    }

    public function findById(Request $request, $id) {
        try{
            $activity = $this->activityService->findById($id);
            if(!isset($activity)) {
                return response()->json(["error" => 404, 'message' => 'Activity not found'],404);
            }
            return $this->activityToActivityFullJson($activity);
        }catch (\Exception $e) {
            return response()->json(["error" => 500, 'message' => $e->getMessage()],500);
        }
    }

    private function activityToActivitySearchJson($activitySearch) {
        $activityJson =  [
            "id" => $activitySearch->id,
            "name" => $activitySearch->name,
            "date" => $activitySearch->date,
            "long" => $activitySearch->long,
            "lat" => $activitySearch->lat,
            "dist" => $activitySearch->dist,
            "category" => $activitySearch->category
        ];
        if(isset($activitySearch->image_url)) {
            $activityJson["image_url"] = asset(Storage::url($activitySearch->image_url));
        }
        return $activityJson;
    }

    private function activityToActivityFullJson($activity) {

        if(!isset($activity)) {
            return;
        }

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
            "lat" => $activity->lat,
            "category" => $activity->category
        ];

        if(isset($activity->image_url)) {
            $activityJson["image_url"] = asset(Storage::url($activity->image_url));
        }

        return $activityJson;
    }
}
