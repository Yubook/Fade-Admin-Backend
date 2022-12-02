<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use DB;

use function App\Helpers\commonUploadImage;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('admin.city.index');
        } catch (\Exception $e) {
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('admin.city.create');
        } catch (\Exception $e) {
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        DB::beginTransaction();
        try {
            $city = new city();
            $city->name = $request->name;
            $city->save();

            DB::commit();
            return redirect()->route('cities.index')->with('message', trans('message.city.create'));
        } catch (\Exception $e) {
            DB::rollback();
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        try {
            return view('admin.city.show', compact('city'));
        } catch (\Exception $e) {
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        try {
            return view('admin.city.edit', compact('city'));
        } catch (\Exception $e) {
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, City $city)
    {
        DB::beginTransaction();
        try {
            $city = City::find($city->id);
            $city->name = $request->name;
            $city->save();

            DB::commit();
            return redirect()->route('cities.index')->with('message', trans('message.city.update'));
        } catch (\Exception $e) {
            DB::rollback();
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $cities = City::find(request('ids'));
            $cities->delete();
            DB::commit();
            return response()->noContent();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    public function switchUpdate(Request $request)
    {
        try {
            $city = City::find($request->ids);
            if (empty($city->is_active)) {
                $city->is_active = 1;
            } else {
                $city->is_active = 0;
            }
            $city->save();
            return response()->noContent();
        } catch (\Exception $e) {
            throw new \App\Exceptions\CustomException($e->getMessage());
        }
    }

    public function listing(Request $request)
    {
        extract($this->DTFilters($request->all()));
        $cities = DB::table('cities as city')->select('city.*', 'city.name as city_name', 'state.name as state_name', 'c.name as country_name')
            ->join('states as state', 'city.state_id', '=', 'state.id')
            ->join('countries as c', 'state.country_id', '=', 'c.id');

        $count = $cities->count();

        $records["recordsTotal"] = $count;
        $records["recordsFiltered"] = $count;
        $records['data'] = array();

        if ($search != '') {
            $cities->where(function ($query) use ($search) {
                $query->whereRaw('lower(city.name) like "%' . strtolower($search) . '%"')
                    ->orWhereRaw('lower(state.name) like "%' . strtolower($search) . '%"')
                    ->orWhereRaw('lower(c.name) like "%' . strtolower($search) . '%"');
            });
        }

        $cities = $cities->offset($offset)->limit($limit)->orderBy($sort_column, $sort_order)->get();

        foreach ($cities as $city) {
            $records['data'][] = [
                'id' => $city->id,
                'name' => $city->city_name,
                'state_id' => $city->state_name,
                'country_id' => $city->country_name,
                'latitude' => $city->latitude,
                'longitude' => $city->longitude,
                'active' => view('partials.switch')->with(['is_active' => $city->active, 'id' => $city->id])->render(),
                'action' => view('partials.actions')->with('id', $city->id)->render(),
            ];
        }
        return $records;
    }
}
