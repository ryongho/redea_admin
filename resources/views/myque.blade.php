
@extends('layouts.app')

@section('title', '메인페이지')

@section('nav')
    @parent
@endsection

    <div id="main">
        <div class="top">
          <h1 class="h3 mb-3 font-weight-normal" style="margin-top:100px;">{{ $list->data[0]['user_id'] }}({{ $list->data[0]['name'] }})님이 등록한 질문입니다.</h1>
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card" onclick="go_view({{$data['id']}})">
                
                <div class="card-body">
                    <pre>Q.{{ $data['question'] }}</pre>
                    <div style="width:100%;text-align:right;">
                        <span style="color:gray;font-size:9pt; "> 답변 : {{ $data['ans_cnt'] }} <br/> 질문자 : {{ $data['name'] }}<span>
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
    

