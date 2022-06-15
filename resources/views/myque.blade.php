
@extends('layouts.app')

@section('title', '메인페이지')

@section('nav')
    @parent
@endsection

    <script type="text/javascript">
        function delete_que(id){
            location.href="/delete/que/"+id;
        }
    </script>
    <div id="main">
        <div class="top">
          <h1 class="h3 mb-3 font-weight-normal" style="margin-top:100px;">{{ $list->data[0]['user_id'] }}({{ $list->data[0]['name'] }})님이 등록한 질문입니다.</h1>
        </div>
        <div id="div_list">
        @forelse($list->data as $data)
            <div class="card"> 
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer" onclick="delete_que('{{ $data['id'] }}')" style="position:absolute;top:10px;right:10px;" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                </svg>   
                <div class="card-body" onclick="go_view({{$data['id']}})">
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
    

