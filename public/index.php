<?php
// index.php
// ログインフォーム
// http://getbootstrap.com/examples/signin/

include_once '../lib/util.php';

session_start();
// session_id更新
session_regenerate_id();

// session_token
$sessionToken = hash(HASH_ALGOS, uniqid() . session_id());

// postされた場合
if(isset($_POST["user_id"]))
{
    $uid = $_POST["user_id"];
    $pass = $_POST["pass"];
}

?><!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Simple Todo</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
        }

        .form-signin {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
            margin-bottom: 10px;
        }
        .form-signin .checkbox {
            font-weight: normal;
        }
        .form-signin .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>

    <div class="container">

        <form class="form-signin" role="form" method="post" action="index.php">
            <h2 class="form-signin-heading">Please sign in</h2>
            <input type="email"
                id="user_id" name="user_id" class="form-control"
                placeholder="Email address" required autofocus>
            <input type="password"
                id="pass" name="pass" class="form-control"
                placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

            <!-- Errorダイアログ -->
            <input type="hidden" id="auth_message" value="<?php esc($msg); ?>">
            <div class="alert alert-danger" id="alert_dialog">
                <span class="glyphicon glyphicon-exclamation-sign"></span>
                <span id="error_message"></span>
            </div>

        </form>

    </div>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script>
    // document#onload
    $(function(){
        var msg = $("input#auth_message").val();

        if(msg.length > 0){
            // エラーメッセージを表示
            $("span#error_message").html(msg);
            $("div#alert_dialog").show();
        }else{
            $("div#alert_dialog").hide();
        }
    });
    </script>
</body>
</html>