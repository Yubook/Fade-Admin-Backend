<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResources;
use App\Http\Resources\Country as ResourcesCountry;
use App\Http\Resources\City as ResourcesCity;
use App\Http\Resources\Service as ServiceResources;
use App\Http\Resources\State as ResourcesState;
use App\Http\Resources\Subcategory as SubcategoryResources;
use App\Http\Resources\Time as TimeResources;
use App\Reason;
use App\Service;
use App\State;
use App\Subcategory;
use App\Timing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function getTimes()
    {
        try {
            $response = [];

            $data = Timing::where('is_active', 1)->get();

            if (!empty($data) && $data->count()) {
                $response = TimeResources::collection($data);
            }

            return $this->sendResponse($response, $message = "Successfully get times");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getCountries()
    {
        try {
            $response = [];

            $data = Country::where('active', 1)->orderBy('name', 'asc')->get();

            if (!empty($data) && $data->count()) {
                $data = $data->map(function ($singleData) {
                    $singleData->name = str_replace("\n", '', $singleData->name);
                    return $singleData;
                });
                $response = ResourcesCountry::collection($data);
            }

            return $this->sendResponse($response, $message = "Successfully get country list");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getStates(Request $request)
    {
        try {
            $check_validation = array(
                'country_id' => 'required|integer'
            );

            $validator = Validator::make($request->all(), $check_validation);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $this->statusArr['validation']);
            }
            $response = [];

            $data = State::where('active', 1);
            if (!empty($request->country_id)) {
                $data = $data->where('country_id', $request->country_id);
            }

            $data = $data->orderBy('name', 'asc')->get();

            if (!empty($data) && $data->count()) {
                $data = $data->map(function ($singleData) {
                    $singleData->name = str_replace("\n", '', $singleData->name);
                    return $singleData;
                });
                $response = ResourcesState::collection($data);
            }

            return $this->sendResponse($response, $message = "Successfully get state list");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getCities(Request $request)
    {
        try {
            $check_validation = array(
                'country_id' => 'required|integer'
            );

            $validator = Validator::make($request->all(), $check_validation);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), $this->statusArr['validation']);
            }

            $response = [];

            $data = City::where('active', 1);
            if (!empty($request->country_id)) {
                $data = $data->where('country_id', $request->country_id);
            }

            $data = $data->orderBy('name', 'asc')->get();

            if (!empty($data) && $data->count()) {
                $data = $data->map(function ($singleData) {
                    $singleData->name = str_replace("\n", '', $singleData->name);
                    return $singleData;
                });
                $response = ResourcesCity::collection($data);
            }

            return $this->sendResponse($response, $message = "Successfully get city list");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getServices()
    {
        try {
            $response = [];

            $query = Service::with('category', 'subcategory')->where('is_active', 1)->get();

            if (!empty($query) && $query->count()) {
                $response = ServiceResources::collection($query);
            }

            return $this->sendResponse($response, $message = "Successfully get services");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getAllCategories()
    {
        try {
            $response = [];

            $query = Category::where('is_active', 1)->get();

            if (!empty($query) && $query->count()) {
                $response = CategoryResources::collection($query);
            }

            return $this->sendResponse($response, $message = "Successfully get categories");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function getAllSubCategories()
    {
        try {
            $response = [];

            $query = Subcategory::with('category')->where('is_active', 1)->get();

            if (!empty($query) && $query->count()) {
                $response = SubcategoryResources::collection($query);
            }

            return $this->sendResponse($response, $message = "Successfully get sub categories");
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }

    public function cancelReasons()
    {
        try {
            $response = [];

            $reasons = Reason::where(['is_active' => 1])->get();
            if (!empty($reasons) && $reasons->count()) {
                $response = $reasons;
                $message = "Successfully Get Reasons";
            } else {
                $message = "No Reasons Found";
            }

            return $this->sendResponse($response, $message);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $this->statusArr['something_wrong']);
        }
    }
}
