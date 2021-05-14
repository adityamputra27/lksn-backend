<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Studio;
use Validator;
use App\Service\Response;

class StudioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::data(Studio::with('branch')->orderBy('name', 'ASC')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'basic_price' => 'required|numeric|between:1,1000000',
            'additional_friday_price' => 'required|numeric|between:0,1000000',
            'additional_saturday_price' => 'required|numeric|between:0,1000000',
            'additional_sunday_price' => 'required|numeric|between:0,1000000'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $studio = new Studio;
        $studio->name = $request->name;
        $studio->branch_id = $request->branch_id;
        $studio->basic_price = $request->basic_price;
        $studio->additional_friday_price = $request->additional_friday_price;
        $studio->additional_saturday_price = $request->additional_saturday_price;
        $studio->additional_sunday_price = $request->additional_sunday_price;
        $studio->save();

        return Response::successMessage('create studio success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $studioId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'basic_price' => 'required|numeric|between:1,1000000',
            'additional_friday_price' => 'required|numeric|between:0,1000000',
            'additional_saturday_price' => 'required|numeric|between:0,1000000',
            'additional_sunday_price' => 'required|numeric|between:0,1000000'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $studio = Studio::find($studioId);

        if (!$studio) 
        {
            return Response::validation('studio not found');
        }
        else 
        {
            $studio->name = $request->name;
            $studio->branch_id = $request->branch_id;
            $studio->basic_price = $request->basic_price;
            $studio->additional_friday_price = $request->additional_friday_price;
            $studio->additional_saturday_price = $request->additional_saturday_price;
            $studio->additional_sunday_price = $request->additional_sunday_price;
            $studio->save();
        }

        return Response::successMessage('update studio success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($studioId)
    {
        $studio = Studio::find($studioId);

        if (!$studio) 
        {
            return Response::validation('studio not found');
        }
        else
        {
            $studio->delete();
        }

        return Response::successMessage('delete studio success');
    }
}
