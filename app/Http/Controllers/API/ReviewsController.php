<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Review;

class ReviewsController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
    }
    
    public function rate(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'spot_id' => ['required', 'int', 'max:255'],
                'user_id' => ['required', 'int', 'max:255'],
                'rating' => ['required'],
                'comment' => ['required']
            ]);            

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()->first(),
                ], 400);
            }
            
            Review::create([
                'spot_id' => $request['spot_id'],
                'user_id' => $request['user_id'],
                'rating' => $request['rating'],
                'comment' => $request['comment']
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
    
    public function top(Request $request){
        try{
            $reviews = DB::table('reviews')
                ->select('reviews.rating', 'reviews.comment', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user"), 'spots.name AS location')
                ->join('users', 'users.id', '=', 'reviews.user_id')
                ->join('spots', 'spots.id', '=', 'reviews.spot_id')
                ->orderBy('reviews.rating', 'DESC')
                ->limit(5)
                ->get();   
                      
            return response()->json([
                'status' => true,
                'data' =>  $reviews
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}