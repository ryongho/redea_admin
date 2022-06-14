<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Like;
use App\Models\Tag;
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

        if($result){
            $tags = $this->get_tags($question);
            
            foreach($tags as $tag){
                $tag_table = Tag::firstOrNew(['tag' => $tag]); // your data
                // make your affectation to the $table1
                $tag_table->save();
                //$result2 = Tag::updateOrInsert('tag', $tag); 
            }
            
        }

        $return->status = "200";
        
        return redirect()->route('main');  



    }

    public function view(Request $request)
    {
        $question_id = $request->question_id;

        $user_id = 0;
        $is_login = 0;
        if(Auth::id()){
            $user_id = Auth::id();
            $is_login = 1;
        }
    
        $question = Question::select(   
            '*',
            DB::raw('(select user_id from users where questions.user_id = users.id) as user_id'),
            DB::raw('(select count(*) from answers where questions.id = answers.question_id) as ans_cnt'),
            )
            ->where('id',$question_id)->first();

        $answers = Answer::select(   
            '*',
            DB::raw('(select user_id from users where answers.user_id = users.id) as user_id'),
            DB::raw('(select count(*) from likes where likes.user_id ="'.$user_id.'" and answers.id = likes.answer_id) as is_like'),
            DB::raw('(select count(*) from likes where answers.id = likes.answer_id) as cnt_like'),
            )
            ->where('question_id',$question_id)->get();
        
        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->question = $question;
        $list->answers = $answers;
        $list->is_login = $is_login;

        //dd($list);

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

    public static function myque(){

        $user_id = Auth::id();

        $row = 100;

        $rows = Question::select(   
                '*',
                DB::raw('(select user_id from users where questions.user_id = users.id) as user_id'),
                DB::raw('(select count(*) from answers where questions.id = answers.question_id) as ans_cnt'),
                )
                ->where('questions.user_id',$user_id)
                ->limit($row)->orderby('id','desc')->get();

        $count = Question::count();

        $list = new \stdClass;

        $list->status = "200";
        $list->msg = "success";
        
        $list->data = $rows;
        
        return view('myque', ['list' => $list]);
        
    }    

    public function get_tags($string){ //문자열을 받아서 태그를 추출하는 기능
        
        $tag_arr = explode("#",$string);
        $tags = array();
        $i = 0;
        $y= 0;
        foreach($tag_arr as $tag){
            if($y > 0){
                $keys = explode(" ",$tag);
                $tags[$i] = $keys[0];
                $i++;
            }
            $y++;
        }
        return $tags;

    }
    
    



}
