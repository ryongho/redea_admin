
@extends('layouts.app')

@section('title', '질문 상세 페이지')

@section('nav')
    @parent
@endsection
<script>

function like(answer_id){
    
    const is_login = {{$list->is_login}};
    if(is_login){

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          }
      });

      $.ajax({
        method: "POST",
        url: "/like",
        data: { answer_id : answer_id},
      })
      .done(function(data) {
          const status = data.status;
          //alert(data.status);
          if(data.status == "200"){
            const fill_html = "";

            if(data.added == "Y"){
              $("#div_like_added_"+data.answer_id).css('display','inline');
              $("#div_like_"+data.answer_id).css('display','none');
            }else{
              $("#div_like_added_"+data.answer_id).css('display','none');
              $("#div_like_"+data.answer_id).css('display','inline');
            }
            
          }
      });
    }else{
      alert("로그인이 필요합니다.");
      location.href="/login";
    }
    
  }

</script>
    <div id="main">
        <div id="div_regist">
            <form class="form-signin" method="POST" action="{{ route('regist_answer') }}">
            @csrf
                <h1 class="h3 mb-3 font-weight-normal"  style="margin-left:10%;margin-top:100px;">{{ $list->question->question }}</h1>
                <textarea name="answer" id="answer" class="form-control" placeholder="자유롭게 답변해 주세요." required autofocus></textarea>
                <input type="hidden" name="question_id" value="{{ $list->question->id }}"/>
                <button class="btn btn-primary btn-block" id="btn_regist" style="margin-left:80%;" type="submit">등록</button>
                
            </form>  
        </div>
        <div id="div_list" class="text-center">
        @forelse($list->answers as $answer)
            <div class="card">
                
                <div class="card-body">
                    <div style="width:100%;">    
                      <pre>{{ $answer['answer'] }}</pre>
                    </div>
                    <div style="width:30%;float:left">
                        @if($answer->is_like)
                          <svg xmlns="http://www.w3.org/2000/svg" id="div_like_added_{{$answer->id}}" style="display:inline;" width="16" height="16" onclick="like({{$answer->id}})" fill="currentColor" color="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                          </svg>
                          <svg xmlns="http://www.w3.org/2000/svg" id="div_like_{{$answer->id}}" style="display:none;" width="16" height="16" onclick="like({{$answer->id}})" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                          </svg>
                        @else
                          <svg xmlns="http://www.w3.org/2000/svg" id="div_like_added_{{$answer->id}}" style="display:none;" width="16" height="16" onclick="like({{$answer->id}})" fill="currentColor" color="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                          </svg>
                          <svg xmlns="http://www.w3.org/2000/svg" id="div_like_{{$answer->id}}" style="display:inline;" width="16" height="16" onclick="like({{$answer->id}})" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                          </svg>
                        @endif&nbsp;&nbsp;&nbsp;   
                      @if($answer->cnt_like)
                        좋아요 {{$answer->cnt_like}}
                      @endif
                    </div>
                    
                    <div style="width:70%;text-align:right;float:right;">
                      <span style="color:gray;font-size:11pt;" onclick="go_page('{{$answer['user_id']}}')"> {{ $answer['user_id'] }} <span><br/>
                      <span style="color:gray;font-size:9pt; ">  작성시간 : {{ $answer['created_at'] }} <span>
                    </div>
                </div> 
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    답변이 없습니다.
                </div>
            </div>
        @endforelse 

          <button class="btn btn-gray btn-dark"  id="btn_go_list" onclick="go_list()" type="button">목록으로</button>
        <div>

        
    </div>
