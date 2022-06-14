@extends('layouts.app')

@section('title', '페이지')

@section('nav')
    @parent
@endsection

    <div id="main">
        <div class="top">
          <h1 class="h3 mb-3 font-weight-normal" style="margin-top:100px;">{{ $list->user['user_id'] }}</h1>
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card" onclick="go_view({{$data['question_id']}})">
                
                <div class="card-body">
                    <span style="font-weight:600;">{{ $data['question'] }}</span>
                </div> 
                <div class="card-body">
                <span style="font-weight:400">{{ $data['answer'] }}</span>
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
