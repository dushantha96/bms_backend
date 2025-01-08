<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use App\Models\Spot;
use App\Models\User;
use App\Models\Booking;
use App\Models\Review;

class SpotsController extends Controller
{    
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function search(Request $request)
    {        
        try {
            $from = Carbon::parse($request->from);
            $to = Carbon::parse($request->to);
            
            $differenceInHours = $to->diffInHours($from);

            $spots = Spot::select(
                'spots.id',
                'spots.name',
                'spots.image',
                'spots.lat',
                'spots.lng',
                'spots.description',
                DB::raw("CONCAT('£', FORMAT(spots.rate, 2)) AS rate"),
                DB::raw("(
                    6371 * acos(
                        cos(radians($request->lat)) *
                        cos(radians(spots.lat)) *
                        cos(radians(spots.lng) - radians($request->lng)) +
                        sin(radians($request->lat)) *
                        sin(radians(spots.lat))
                    )
                ) AS distance"),
                DB::raw('AVG(reviews.rating) as rating'),
                DB::raw('COUNT(reviews.id) as reviews'),DB::raw("CONCAT('£', FORMAT(spots.rate * $differenceInHours, 2)) AS final_rate"), 
                DB::raw("$differenceInHours AS total_hours"),
                DB::raw("'$request->from' AS from_time"),
                DB::raw("'$request->to' AS to_time") 
            )
            ->leftJoin('reviews', 'reviews.spot_id', '=', 'spots.id')
            ->groupBy('spots.id', 'spots.lat', 'spots.lng', 'spots.name', 'spots.image', 'spots.description', 'spots.rate')
            ->having('distance', '<=', 5)
            ->orderBy('distance', 'asc')
            ->get();

            return response()->json([
                'status' => true,
                'data' => $spots
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function details(Request $request)
    {        
        try {
            $spot = Spot::withCount('reviews')
                ->withAvg('reviews', 'rating') 
                ->findOrFail($request->id);

            return response()->json([
                'status' => true,
                'data' => $spot
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function getByUser(Request $request)
    {        
        try {
            $user = User::findOrFail($request->user_id);

            if($user->user_type == 1){
                $spots = DB::table('spots')
                    ->select('spots.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS owner"))
                    ->join('users', 'users.id', '=', 'spots.user_id')
                    ->orderBy('spots.name', 'DESC')
                    ->get();
            }
            else{
                $spots = DB::table('spots')
                    ->select('spots.*', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS owner"))
                    ->join('users', 'users.id', '=', 'spots.user_id')
                    ->where('spots.user_id', $request->user_id)
                    ->orderBy('spots.name', 'DESC')
                    ->get();
            }

            return response()->json([
                'status' => true,
                'data' => $spots
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function save(Request $request)
    {        
        try {
            
            $validator = Validator::make($request->all(), [
                'user_id' => ['required', 'int', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'lat' => ['required'],
                'lng' => ['required'],
                'rate' => ['required']
            ]);
            

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            
            $spot = isset($request->id) ? Spot::find($request->id) : new Spot();
            
            $spot->user_id = $request['user_id'];
            $spot->name = $request['name'];
            $spot->lat = $request['lat'];
            $spot->lng = $request['lng'];
            $spot->rate = $request['rate'];
            $spot->description = $request['description'];
            $spot->save();
                      
            return response()->json([
                'status' => true
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function delete(Request $request)
    {        
        try {
            if(!$request->id){
                return response()->json([
                    'message' => 'Spot Id Requeired'
                ], 400);
            }
    
            Booking::where('spot_id', $request->id)->delete();
            Review::where('spot_id', $request->id)->delete();
            Spot::where('id', $request->id)->delete();

            return response()->json([
                'status' => true
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}
