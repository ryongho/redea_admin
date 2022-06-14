
@extends('layouts.app')

@section('title', '메인페이지')

@section('nav')
    @parent
@endsection

    <div id="main">
        <div id="div_regist">
            <form class="form-signin" method="POST" action="{{ route('regist_que') }}">
            @csrf
                <h1 class="h3 mb-3 font-weight-normal" style="margin-left:10%;">오늘의 질문을 등록해주세요. </h1>
                <textarea name="question" id="question" class="form-control" placeholder="자유롭게 질문을 입력해 주세요.&#13;&#10;" required autofocus></textarea>
                <input type="text" name="tag" id="tag" class="form-control" placeholder="질문에 태그(#)를 추가해 주세요. ex) #음식 #취향 #맛집소개"/>
                <button class="btn btn-primary btn-block" id="btn_regist" style="margin-left:80%;" type="submit">입력</button>
            </form>  
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card" onclick="go_view({{$data['id']}})">
                
                <div class="card-body">
                    <pre>{{ $data['question'] }}</pre>
                    <div style="width:100%;text-align:right;">
                        <!--<div class="div_tags">
                            @forelse(explode(",",$data['tag_str']) as $tag)
                             <span style="color:gray;font-size:9pt;color:blue; "> #{{ $tag }}<span>
                            @empty

                            @endforelse
                        </div>-->
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
    

