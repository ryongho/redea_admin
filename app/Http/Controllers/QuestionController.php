<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function regist(Request $request)
    {
        $user_id = Auth::id();
        $question = $request->question;

        $return = new \stdClass;

        $result = Question::insert([
            'user_id'=> $user_id ,
            'question'=> $question ,
            'created_at'=> Carbon::now(),
        ]);
        $return->status = "200";
        
        return redirect()->route('main');  



    }

    public function view(Request $request)
    {
        $question_id = $request->question_id;

        $question = Question::select(   
            '*',
            DB::raw('(select user_id from users where questions.user_id = users.id) as user_id'),
            DB::raw('(select count(*) from answers where questions.id = answers.question_id) as ans_cnt'),
            )
            ->where('id',$question_id)->first();

        $answers = Answer::select(   
            '*',
            DB::raw('(select user_id from users where answers.user_id = users.id) as user_id'),
            )
            ->where('question_id',$question_id)->get();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->question = $question;
        $list->answers = $answers;

        return view('question_view', ['list' => $list]);

    }

    

    public static function get_list(){
        $row = 100;

        $rows = Question::select(   
                '*',
                DB::raw('(select user_id from users where questions.user_id = users.id) as user_id'),
                DB::raw('(select count(*) from answers where questions.id = answers.question_id) as ans_cnt'),
                )
                ->limit($row)->orderby('id','desc')->get();

        $count = Question::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $rows;
        
        return $list;
        
    }
    
    



}
