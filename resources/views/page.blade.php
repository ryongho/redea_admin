@extends('layouts.app')

@section('title', '페이지')

@section('nav')
    @parent
@endsection

    <div id="main">
        <div class="top" style="margin-top:100px;margin-bottom:100px;">
          <h1 class="h3 mb-3 font-weight-bold" style="width:150px;float:left">{{ $list->user['user_id'] }}</h1>
          <span style="float:left;font-size:9pt;margin-top:5px;">질문&nbsp;:&nbsp;{{$list->my_que_cnt}}&nbsp;/&nbsp; </span>
          <span style="float:left;font-size:9pt;margin-top:5px;">답변&nbsp;:&nbsp;{{$list->my_ans_cnt}} </span>
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card" onclick="go_view({{$data['question_id']}})">
                
                <div class="card-body">
                    <span style="font-weight:600;"><pre>{{ $data['question'] }}</pre></span>
                </div> 
                <div class="card-body">
                <span style="font-weight:400"><pre>{{ $data['answer'] }}</pre></span>
                </div> 
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    답변을 등록한 질문이 없습니다.
                </div>
            </div>
        @endforelse 
        <div>
    </div>
