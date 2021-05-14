<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;
use App\Models\Movie;
use App\Models\Studio;
use App\Models\Branch;

use App\Service\Response;

use App\Http\Requests\ScheduleStoreRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Requests\ScheduleViewRequest;

use Carbon\Carbon;
use Validator;
use DateTime;

class ScheduleController extends Controller
{
    private $schedule;
    private $movie;
    private $studio;
    private $branch;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Schedule $schedule, Movie $movie, Studio $studio, Branch $branch)
    {
        $this->schedule = $schedule;
        $this->movie = $movie;
        $this->studio = $studio;
        $this->branch = $branch;
    }

    public function index(Request $request)
    {
        return Response::data($this->schedule->with('movie', 'studio.branch')->get());
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
            'studio_id' => 'required|exists:studios,id',
            'movie_id' => 'required|exists:movies,id',
            'start' => 'required'
        ]);

        if($validator->fails())
        {
            return Response::validation($validator->errors());
        }

        $params = $request->only([
            'studio_id',
            'movie_id',
            'start'
        ]);

        $studio = $this->studio->find($params['studio_id']);
        // echo 'Studio Name : ' . $studio->name;
        $movie = $this->movie->find($params['movie_id']);

        if (!$studio || !$movie) 
        {
            return Response::validation('studio or movie not found');
        }

        // echo date('Y-m-d H:i:s', strtotime($params['start']));
        $start = date('Y-m-d H:i:s', strtotime($params['start']));
        $end =  Carbon::createFromFormat('Y-m-d H:i:s', $start)->addMinutes($movie->minute_length);
        $end = date('Y-m-d H:i:s', strtotime($end));

        $overlap = $this->schedule->where('start', '<=', $start)
                                ->where('end', '>=', $start)
                                ->where('studio_id', '=', $params['studio_id'])
                                ->count();

        if ($overlap != 0) 
        {
            return Response::failed('schedule overlapped');
        }

        // echo date('l', strtotime($params['start']));
        $day =  date('l', strtotime($params['start']));

        $price = $studio->basic_price;
        
        switch ($day) {
            case 'Friday':
                $price += $studio->additional_friday_price;
                break;
            
            case 'Saturday':
                $price += $studio->additional_saturday_price;
                break;
            
            case 'Sunday':
                $price += $studio->additional_sunday_price;
                break;
            
            default:
                $price += 0;
                break;
        }

        $this->schedule->create([
            'movie_id' => $params['movie_id'],
            'studio_id' => $params['studio_id'],
            'start' => $start,
            'end' => $end,
            'price' => $price
        ]);

        return Response::successMessage('create schedule success');
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
    public function update(Request $request, $scheduleId)
    {
        $validator = Validator::make($request->all(), [
            'studio_id' => 'required|exists:studios,id',
            'movie_id' => 'required|exists:movies,id',
            'start' => 'required'
        ]);

        if($validator->fails())
        {
            return Response::validation($validator->errors());
        }

        $params = $request->only([
            'studio_id',
            'movie_id',
            'start'
        ]);

        $find = $this->schedule->find($scheduleId);

        if (!$find) 
        {
            return Response::validation('schedule not found');
        }

        $studio = $this->studio->find($params['studio_id']);
        // echo 'Studio Name : ' . $studio->name;
        $movie = $this->movie->find($params['movie_id']);
        // echo date('Y-m-d H:i:s', strtotime($params['start']));

        if (!$studio || !$movie) 
        {
            return Response::validation('studio or movie not found');
        }

        $start = date('Y-m-d H:i:s', strtotime($params['start']));
        $end =  Carbon::createFromFormat('Y-m-d H:i:s', $start)->addMinutes($movie->minute_length);
        $end = date('Y-m-d H:i:s', strtotime($end));

        $overlap = $this->schedule->where('start', '<=', $start)
                                ->where('end', '>=', $start)
                                ->where('studio_id', '=', $params['studio_id'])
                                ->count();

        if ($overlap != 0) 
        {
            return Response::failed('schedule overlapped');
        }

        // echo date('l', strtotime($params['start']));
        $day =  date('l', strtotime($params['start']));

        $price = $studio->basic_price;
        
        switch ($day) {
            case 'Friday':
                $price += $studio->additional_friday_price;
                break;
            
            case 'Saturday':
                $price += $studio->additional_saturday_price;
                break;
            
            case 'Sunday':
                $price += $studio->additional_sunday_price;
                break;
            
            default:
                $price += 0;
                break;
        }

        $find->fill([
            'movie_id' => $params['movie_id'],
            'studio_id' => $params['studio_id'],
            'start' => $start,
            'end' => $end,
            'price' => $price
        ]);

        $find->save();

        return Response::successMessage('update schedule success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($scheduleId)
    {
        $schedule = Schedule::find($scheduleId);

        if (!$schedule) 
        {
            return Response::validation('schedule not found');
        }

        $schedule->delete();

        return Response::successMessage('delete schedule success');
    }

    public function viewSchedules(Request $request)
    {
        $params = $request->only(['branch_id', 'date']);

        $studios = $this->branch->find($params['branch_id'])->studios;

        // echo $studio;
        $arr = [];
        $movieDone = [];
        foreach ($studios as $key => $studio) 
        {
            $schedules = $this->schedule->where('studio_id', '=', $studio->id)->whereDate('start', '=', $params['date'])->get();
            // echo $schedules;
            foreach ($schedules as $key => $schedule) 
            {
                if (!in_array($schedule->movie_id, $movieDone)) 
                {
                    $arr[$schedule->movie_id] = [
                        'name' => $schedule->movie->name,
                        'price' => $schedule->price
                    ];
                }
                $arr[$schedule->movie_id]['start_time'][] = date('H:i', strtotime($schedule->start));
                $movieDone[] = $schedule->movie_id;
            }
        }

        $result = [];
        foreach ($arr as $key => $value) 
        {
            $result[] = $value;
        }

        // echo json_encode($result);
        return Response::data($result);

    }
}
