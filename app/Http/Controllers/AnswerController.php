<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    public function regist(Request $request)
    {
        $user_id = Auth::id();
        $question_id = $request->question_id;
        $answer = $request->answer;

        $return = new \stdClass;

        $result = Answer::insert([
            'user_id'=> $user_id ,
            'question_id'=> $question_id ,
            'answer'=> $answer ,
            'created_at'=> Carbon::now(),
        ]);
        $return->status = "200";
        
        return redirect()->route('view_question', ['question_id' => $question_id]); 

    }

    public function view(Request $request)
    {
        $question_id = $request->question_id;

        $question = Question::where('id',$question_id)->first();
        $answers = Answer::where('question_id',$question_id)->get();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->question = $question;
        $list->answers = $answers;

        return view('question_view', ['list' => $list]);

    }

    

    public function get_list(){
        $row = 100;

        $rows = Question::limit($row)->get();

        $count = Question::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $rows;
        
        return $list;
        
    }
    
    



}
