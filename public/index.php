<?php
// index.php
// ログインフォーム
// http://getbootstrap.com/examples/signin/

include_once '../lib/util.php';
include_once '../lib/User.php';

// Sessionの開始
session_start();

// session_id更新前に、CSRF対策用のトークンを再生成しておく
$prevSessionToken = stretch(TOKEN_SALT . session_id());

// Session乗っ取り対策のため、session_idを更新する
session_regenerate_id();

// CSRF対策のためのsession_tokenを生成する
$sessionToken = stretch(TOKEN_SALT . session_id());

// エラーメッセージ
$errorMessage = '';

// CSRF対策のsession_tokenを照会する
if(isset($_POST['token']))
{
    if($_POST['token'] !== $prevSessionToken)
    {
        $errorMessage = 'CSRFが疑われるため、処理を継続できません。';
    }
    else
    {
        // ログイン処理
        $errorMessage = login();
    }
}

/**
 * ログイン処理
 */
function login()
{
    $msg = '';
    if(isset($_POST["user_id"]))
    {
        $user = new User();

        // メールアドレス
        $user->mail = $_POST['user_id'];
        // パスワード
        // パスワード漏洩対策のため、saltを付けてストレッチングしている
        $user->password = stretch(AUTH_SALT .
            $user->mail .
            $_POST['pass']);

        if($user->auth())
        {
            // 認証成功時、一旦Sessionを全て破棄
            //   前回ログイン時の情報等が残らないように
            $_SESSION = array();
            @session_destroy(); // エラー無視

            // Session再開
            session_start();

            // sessionにログインユーザーのidを保持する
            $_SESSION[S_KEY_USER_ID] = $user->id;

            // list.phpに遷移
            header('Location list.php');
            exit;
        }
        else
        {
            // 認証失敗
            $msg = '認証に失敗しました。';
        }
    }
    return $msg;
}

?><!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Simple Todo</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="container">

        <form class="form-signin" role="form"
            method="post"
            action="index.php">

            <input type="hidden" name="token"
                value="<?php echo($sessionToken); ?>">

            <h2 class="form-signin-heading">Please sign in</h2>

            <input type="email"
                id="user_id" name="user_id" class="form-control"
                value="<?php esc($_POST["user_id"]); ?>"
                placeholder="Email address" required autofocus>
            
            <input type="password"
                id="pass" name="pass" class="form-control"
                placeholder="Password" required>
            
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

            <!-- Errorメッセージ -->
            <input type="hidden" id="auth_message" value="<?php esc($errorMessage); ?>">
        </form>

        <div class="alert alert-danger" id="alert_dialog">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            <span id="error_message"></span>
        </div>

    </div>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script>
    // document#onload
    $(function(){
        // hiddenからエラーメッセージ取得
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