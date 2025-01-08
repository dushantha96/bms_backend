<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Booking;
use App\Models\User;

class BookingsController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
    }

    public function place(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'spot_id' => ['required', 'int', 'max:255'],
                'user_id' => ['required', 'int', 'max:255'],
                'from' => ['required', 'string', 'max:255'],
                'to' => ['required', 'string', 'max:255'],
                'hours' => ['required', 'int'],
                'rate' => ['required', 'string'],
                'total' => ['required', 'string'],
            ]);            

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            
            Booking::create([
                'spot_id' => $request['spot_id'],
                'user_id' => $request['user_id'],
                'from' => $request['from'],
                'to' => $request['to'],
                'hours' => $request['hours'],
                'rate' => str_replace('£', '', $request['rate']),
                'total' => str_replace('£', '', $request['total'])
            ]);            
                      
            return response()->json([
                'status' => true
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }

    public function getByUser(Request $request)
    {        
        try {
            $user = User::findOrFail($request->user_id);

            if($user->user_type == 1){
                $bookings = DB::table('bookings')
                    ->select(
                        'bookings.id', 
                        DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"), 
                        'spots.name AS location', 
                        'users.email', 
                        'bookings.from', 
                        'bookings.to', 
                        'bookings.hours', 
                        'bookings.rate', 
                        'bookings.total'
                    )
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('spots', 'spots.id', '=', 'bookings.spot_id')
                    ->orderBy('bookings.from', 'DESC')
                    ->get();
            }
            elseif($user->user_type == 2){
                $bookings = DB::table('bookings')
                    ->select(
                        'bookings.id', 
                        DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"), 
                        'spots.name AS location', 
                        'users.email', 
                        'bookings.from', 
                        'bookings.to', 
                        'bookings.hours', 
                        'bookings.rate', 
                        'bookings.total'
                    )
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('spots', 'spots.id', '=', 'bookings.spot_id')
                    ->where('spots.user_id', $request->user_id)
                    ->orderBy('bookings.from', 'DESC')
                    ->get();
            }
            else{
                $bookings = DB::table('bookings')
                    ->select(
                        'bookings.id', 
                        DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"), 
                        'spots.name AS location', 
                        'users.email', 
                        'bookings.from', 
                        'bookings.to', 
                        'bookings.hours', 
                        'bookings.rate', 
                        'bookings.total',
                        DB::raw("IF(bookings.to < '" . Carbon::now()->toDateTimeString() . "', 'expired', 'not expired') AS status"),
                        'bookings.spot_id', 
                        'bookings.user_id', 
                        'reviews.rating', 
                        'reviews.comment'
                    )
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('spots', 'spots.id', '=', 'bookings.spot_id')
                    ->leftJoin('reviews', function($join) {
                        $join->on('reviews.spot_id', '=', 'bookings.spot_id')
                             ->on('reviews.user_id', '=', 'bookings.user_id'); 
                    })
                    ->where('bookings.user_id', $request->user_id)
                    ->orderBy('bookings.from', 'DESC')
                    ->get();
            }

            return response()->json([
                'status' => true,
                'data' => $bookings
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}