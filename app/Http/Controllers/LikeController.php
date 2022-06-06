<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{

    public function toggle(Request $request)
    {
        //dd($request);
        $return = new \stdClass;

        $login_user = Auth::user();
        $user_id = $login_user->getId();

        $cnt = Like::where('answer_id',$request->answer_id)->where('user_id',$user_id)->count();

        if($cnt){
            
            Like::where('answer_id',$request->answer_id)->where('user_id',$user_id)->delete();
            $return->status = "200";
            $return->added = 'N';

        }else{
            Like::insert([
                'user_id'=> $user_id ,
                'answer_id'=> $request->answer_id ,
                'created_at'=> Carbon::now(),
            ]);

            $return->status = "200";
            $return->added = 'Y';
            
        }

        $return->answer_id = $request->answer_id;


        return response()->json($return, 200)->withHeaders([
            'Content-Type' => 'application/json'
        ]);;
        
    }

    



}
