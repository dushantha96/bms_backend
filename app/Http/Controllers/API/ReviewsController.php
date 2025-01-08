<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}