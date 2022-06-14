
@extends('layouts.app')

@section('title', '메인페이지')

@section('nav')
    @parent
@endsection

    <div id="main">
        <div id="div_regist">
            <form class="form-signin" method="POST" action="{{ route('regist_que') }}">
            @csrf
                <h1 class="h3 mb-3 font-weight-normal" style="margin-left:10%;">오늘의 질문을 </h1>
                <textarea name="question" id="question" class="form-control" placeholder="자유롭게 질문을 입력해 주세요.&#13;&#10;태그(#)를 추가하여 질문을 분류 할 수 있습니다.&#13;&#10;ex)가장 좋아하는 음식은? #음식 #취향 #맛집소개" required autofocus></textarea>
                <button class="btn btn-primary btn-block" id="btn_regist" style="margin-left:80%;" type="submit">입력</button>
            </form>  
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card" onclick="go_view({{$data['id']}})">
                
                <div class="card-body">
                    <pre>{{ $data['question'] }}</pre>
                    <div style="width:100%;text-align:right;">
                        <span style="color:gray;font-size:9pt; "> 답변 : {{ $data['ans_cnt'] }} <br/> 질문자 : {{ $data['user_id'] }}<span>
                    </div>
                </div> 
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    질문이 없습니다.
                </div>
            </div>
        @endforelse 
        <div>
    </div>
    

