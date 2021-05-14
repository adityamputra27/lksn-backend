<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Validator;
use App\Service\Response;

class MovieController extends Controller
{
    private $destinationPath;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->destinationPath = public_path('/pictures');
    }

    public function index()
    {
        return Response::data(Movie::orderBy('name', 'ASC')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'name' => 'required|min:3',
            'minute_length' => 'required|numeric|between:1,999',
            'picture' => 'required|image'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $movie = new Movie;
        $movie->name = $request->name;
        $movie->minute_length = $request->minute_length;

        $file = $request->file('picture');
        $fileName = $file->hashName();
        $file->move($this->destinationPath, $fileName);

        $movie->picture_url = $fileName;
        $movie->save();

        return Response::successMessage('create movice success');
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
    public function update(Request $request, $movieId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'minute_length' => 'required|numeric|between:1,999',
            'picture' => 'required|image'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $movie = Movie::find($movieId);

        if (!$movie) 
        {
            return Response::validation('movie not found');
        }

        $movie->name = $request->name;
        $movie->minute_length = $request->minute_length;

        $file = $request->file('picture');
        
        if ($file) 
        {
            $destinationPath = public_path('/pictures/'.$movie->picture_url);

            if ($movie->picture_url && file_exists($destinationPath)) 
            {
                unlink($destinationPath);
            }
            $fileName = $file->hashName();
            $file->move($this->destinationPath, $fileName);
            $movie->picture_url = $fileName;
            $movie->save();
        }

        return Response::successMessage('update movice success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($movieId)
    {
        $movie = Movie::find($movieId);

        if (!$movie) 
        {
            return Response::validation('movie not found');            
        }

        $destinationPath = public_path('/pictures/'.$movie->picture_url);

        if ($movie->picture_url && file_exists($destinationPath)) 
        {
            unlink($destinationPath);            
        }

        $movie->delete();

        return Response::successMessage('delete movie success');
    }
}
