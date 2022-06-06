
<!doctype html>
<html lang="kr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Queroll</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style type="text/css">
      #main{
        width:100%;
        min-width:400px;
        margin:auto;
        margin-top:50px;
      }

      #div_regist{
        width:100%;
        max-width:800px;
        margin:auto;
      }

      #div_list{
        width:100%;
        max-width:800px;
        margin:auto;
        margin-top:50px;
      }
      #btn_regist{
        margin-top:30px;
      }
      #btn_go_list{
        margin-top:20px;

      }
      input{
        margin-top:30px;
      }
      #answer{
          width:80%;
          margin-left:10%;
      }
      .card{
          margin-bottom:20px;
          width:80%;
          margin-left:10%;
          text-align:left;
          cursor:pointer;
      }
    </style>
  </head>
  
  <script src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script>
   

    function go_list(){
        location.href="/list";
    }

    function go_page(user_id){
      location.href="/page/"+user_id;
    }

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

  <body class="text-center">
    <div id="main">
        <div id="div_regist">
            <form class="form-signin" method="POST" action="{{ route('regist_answer') }}">
            @csrf
                <h1 class="h3 mb-3 font-weight-normal">{{ $list->question->question }}</h1>
                <textarea name="answer" id="answer" class="form-control" placeholder="자유롭게 답변해 주세요." required autofocus></textarea>
                <input type="hidden" name="question_id" value="{{ $list->question->id }}"/>
                <button class="btn btn-primary btn-block" id="btn_regist"  type="submit">등록</button>
                
            </form>  
        </div>
        <div id="div_list">
        @forelse($list->answers as $answer)
            <div class="card">
                
                <div class="card-body">
                    <div style="width:100%;">    
                      {{ $answer['answer'] }}
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
        <div>

        <button class="btn btn-gray btn-dark" id="btn_go_list" onclick="go_list()" type="button">목록으로</button>
    </div>
    
  </body>
</html>
