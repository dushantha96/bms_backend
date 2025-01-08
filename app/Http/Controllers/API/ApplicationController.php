<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Application;
use App\Models\Notification;
use App\Models\ProgrammeDocument;

class ApplicationController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function applications(Request $request)
    {        
        try {
            $applications = DB::table('applications')
                ->select('applications.id', 'programmes.title','programmes.enroll_fee', 'applications.created_at', 'applications.status', 'applications.programme_id', 'programmes.feature', 'programmes.documents')
                ->join('programmes', 'programmes.id', '=', 'applications.programme_id')
                ->where('applications.user_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->get();
            
            $domain = request()->getSchemeAndHttpHost();

            foreach($applications as $application ){                
                $application->feature = isset($application->feature) ? asset($domain.'/storage/programme/'.$application->programme_id.'/'.$application->feature ): null;
                $application->documents = isset($application->documents) ? json_decode($application->documents) : []; 
            }

            return response()->json([
                'status' => true,
                'data' => $applications
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function apply(Request $request)
    {        
        try {
            if(!$request->programme_id){
                return response()->json([
                    'message' => 'Programme Id Requeired'
                ], 400);
            }

            if(Auth::user()->updated != 1){
                return response()->json([
                    'message' => 'Please update your profile'
                ], 400);
            }
    
            $application = Application::where('programme_id', $request->programme_id)->where('user_id', Auth::id())->get()->count();
            
            if($application > 0){
                return response()->json([
                    'message' => 'Already applied for this programme'
                ], 400);
            } 
    
            $application = new Application();
            $application->programme_id = $request->programme_id;
            $application->user_id = Auth::id();
            $application->status = 0;
            $application->save();
    
            // $notification = new Notification();
            // $notification->user_id = ;
            // $notification->msg_id = 0;
            // $notification->save();

            return response()->json([
                'status' => true,
                'message' => 'Applied Successfully'
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function document(Request $request)
    {        
        try {
            if(!$request->application_id){
                return response()->json([
                    'message' => 'Application Id Requeired'
                ], 400);
            }

            if(!$request->document_type){
                return response()->json([
                    'message' => 'Document Type Requeired'
                ], 400);
            }

            if(!$request->file('document')){
                return response()->json([
                    'message' => 'Document Requeired'
                ], 400);
            }

            $application = Application::where('id', $request->application_id)->where('user_id', Auth::id())->get()->count();
            
            if(!($application > 0)){
                return response()->json([
                    'message' => 'You are not Authorized for this action'
                ], 400);
            } 

            $document = new ProgrammeDocument();
            $document->application_id = $request->application_id;
            $document->type = $request->document_type;
            $document->status = 0;
            $document->save();

            if ($request->file('document')) {
                $name = Str::uuid();
                $guessExtension = $request->file('document')->guessExtension();
                $document_name = $name.'.'.$guessExtension;
                $request->file('document')->storeAs('application/'.$document->application_id.'/document//'.$document->id, $document_name, 'public');
                $document->attachment = $document_name;
                $document->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Document Uploaded Successfully'
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }

    public function documents(Request $request)
    {        
        try {
            if(!$request->application_id){
                return response()->json([
                    'message' => 'Application Id Requeired'
                ], 400);
            }

            $documents = ProgrammeDocument::where('application_id', $request->application_id)->get(['id', 'application_id', 'attachment', 'status']);
            
            $domain = request()->getSchemeAndHttpHost();

            foreach($documents as $document){                
                $document->attachment = isset($document->attachment) ? asset($domain.'/storage/application/'.$document->application_id.'/document//'.$document->id.'/'.$document->attachment ): null;
            }

            return response()->json([
                'status' => true,
                'data' => $documents
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ], 500);
        }
    }
}