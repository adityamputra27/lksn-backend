<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Validator;
use App\Service\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::data(Branch::orderBy('name', 'ASC')->get());
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
            'name' => 'required|min:3'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $branch = new Branch;
        $branch->name = $request->name;
        $branch->save();

        return Response::successMessage('create branch success');
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
    public function update(Request $request, $branchId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $branch = Branch::find($branchId);

        if (!$branch) 
        {
            return Response::validation('branch not found');
        }
        else
        {
            $branch->name = $request->name;
            $branch->save();
        }

        return Response::successMessage('update branch success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $branch->delete();

        return Response::successMessage('delete branch success');
    }
}
