<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Bear;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBearRequest;
use App\Http\Requests\UpdateBearRequest;
use App\Http\Resources\v1\BearResource;
use App\Http\Resources\v1\BearCollection;
use Illuminate\Http\Request;
use App\Http\Filters\V1\BearFilter;

class BearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bearFilter = new BearFilter();

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if ($latitude && $longitude) {
            $bears = $bearFilter->findBearsNearby($latitude, $longitude);
            return new BearCollection($bears);
        } else {
            return new BearCollection(Bear::all());       
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBearRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bear $bear)
    {
        return new BearResource($bear);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bear $bear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBearRequest $request, Bear $bear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bear $bear)
    {
        //
    }
}
