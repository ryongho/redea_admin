<!-- resources/views/layouts/app.blade.php -->

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
      .navbar{
        width:100%;
        max-width:800px;
        margin:auto;
        padding:10px 10%;
        background: #FFF;
        height:60px;
        border-bottom:3px solid #77bdf7;
      }
      .navbar-brand{
          text-align:left;
          width:50%;
          font-weight:bold; 
          color:#77bdf7;
          float:left;
      }
      .nav_btn{
          cursor:pointer;
          float:right;
      }
      .dropdown-menu{
          text-align:right;
          left:-122px;
      }
      #main{
        width:100%;
        min-width:380px;
        margin:auto;
        margin-top:60px;
      }

      #div_regist{
        width:100%;
        max-width:800px;
        margin:auto;
        margin-top:100px;
      }

      #div_list{
        width:100%;
        max-width:800px;
        margin:auto;
        margin-top:50px;
      }
      #btn_regist{
        margin-top:30px;
        background: #77bdf7;
        border-color:#77bdf7;
      }
      input{
        margin-top:30px;
      }
      .pointer{
          cursor:pointer;
      }
      #question{
          width:80%;
          margin-left:10%;
          height:100px;
      }
      #tag{
        width:80%;
        margin-left:10%;
        margin-top:15px;
      }
      #answer{
          width:80%;
          margin-left:10%;
          height:100px;
      }
      
      .card{
          margin-bottom:20px;
          width:80%;
          margin-left:10%;
          text-align:left;
      }
      .h3{
          font-size:18px;
          width:80%;
          margin-left:10%;
          text-align:left;
       
      }
      .top{
        width:100%;
        max-width:800px;
        margin:auto;
        margin-top:50px;
      }
      pre{
        overflow: auto;
        white-space: pre-wrap; /* pre tag내에 word wrap */
      }
      .div_tags{
        text-align:left;
        color:#77bdf7;
        font-size:10pt;
      }
      

      
    </style>
  </head>
  
  <script src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  <script>
    $().ready(function(){
        $('.dropdown-toggle').dropdown();

      /*$("#btn_regist").click(function(){
        const question = $("#question").val();
        
        $.ajax({
          method: "POST",
          url: "/regist_question",
          data: { question : question, user_id : user_id}
        })
        .done(function(data) {
            const status = data.status;
            alert(data.status);
            if(status == "200"){ 
              $.cookie('token', data.token, { path: '/' });
              window.location.href="reservation_cs.html";
            }else{
              alert("계정정보를 확인해 주세요.");
            }
        });
      });*/
    });

    function go_view(id){
        location.href="/view_que/"+id;
    }

    function go_login(){
        location.href="/login";
    }

    function go_list(){
        location.href="/list";
    }

    function go_page(user_id){
        location.href="/page/"+user_id;
    }

    function go_search(tag){
        location.href="/search/"+tag;
    }

  </script>

    
        @section('nav')
        
        <nav class="navbar fixed-top">
            <a class="navbar-brand" href="/">Queroll</a>
            @auth
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        My
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/mypage">마이페이지</a>
                        <a class="dropdown-item" href="/myque">내가 남긴 질문</a>
                        <a class="dropdown-item" href="/logout">로그아웃</a>
                        <a class="dropdown-item" href="#">공유하기</a>
                    </div>
                </div>
            @else
                <span style="float:right;" class="nav_btn" onclick="go_login()">Login</span>
            @endauth
        </nav>
        @show

            @yield('content')        
        
    </body>
</html>