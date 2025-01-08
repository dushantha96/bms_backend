<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Spot;

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

    public function filterMap(Request $request)
    {        
        try {
            $spots = Spot::select(
                'id',
                'lat',
                'lng',
                'rate',
                DB::raw("(
                    6371 * acos(
                        cos(radians($request->lat)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians($request->lng)) +
                        sin(radians($request->lat)) *
                        sin(radians(lat))
                    )
                ) AS distance")
            )
            ->having('distance', '<=', 2)
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

    public function filterList(Request $request)
    {        
        try {
            $spots = Spot::select(
                '*',
                DB::raw("(
                    6371 * acos(
                        cos(radians($request->lat)) *
                        cos(radians(lat)) *
                        cos(radians(lng) - radians($request->lng)) +
                        sin(radians($request->lat)) *
                        sin(radians(lat))
                    )
                ) AS distance")
            )
            ->having('distance', '<=', 15)
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
}
