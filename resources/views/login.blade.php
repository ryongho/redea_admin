
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
        width:300px;
        height:300px;
        margin:auto;
        margin-top:100px;
      }
      .btn-lg{
        margin-top:30px;
      }
      input{
        margin-top:30px;
      }
    </style>
  </head>
  
  
  <script src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

  <script type="text/javascript">
    const msg = '{{Session::get('alert')}}';
    const exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }

    const go_signup = function(){
      window.location.href="/user/regist";
    }

    
  </script>

  <body class="text-center">
    <div id="main">
      <form class="form-signin" method="POST" action="{{ route('login_proc') }}">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">Please Login</h1>
        <input type="id" name="id" id="inputId" class="form-control" placeholder="ID" required autofocus>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" id="login_btn" type="submit">Login</button>  
      </form>  
    </div>
    
  </body>
</html>
