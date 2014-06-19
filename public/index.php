<?php
// index.php
// ログインフォーム
// http://getbootstrap.com/examples/signin/

include_once '../lib/util.php';
include_once '../lib/User.php';

// エラーメッセージ
$errorMessage = '';

// logout時の処理
if(isset($_GET['logout']))
{
    // session開始
    session_start();

    // sessionを破棄
    $_SESSION = array();
    @session_destroy(); // エラー無視

    $errorMessage = 'ログアウトしました。';
}

// ページ読み込み時の共通処理
$page = initPage();

// post?
if($page['isPost'])
{
    if($page['isCSRF'])
    {
        // CSRFかも!?
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
            // sessionにログインユーザーのidを保持する
            $_SESSION[S_KEY_USER_ID] = $user->id;

            // list.phpに遷移
            header('Location: list.php');
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
                value="<?php echo($page['token']); ?>">

            <h2 class="form-signin-heading">Please sign in</h2>

            <input type="email"
                id="user_id" name="user_id" class="form-control  form-signin-top"
                value="<?php
                        if($page['isPost'])
                        {
                            edq($_POST['user_id']);
                        }
                    ?>"
                placeholder="Email address" required autofocus>
            
            <input type="password"
                id="pass" name="pass" class="form-control form-signin-bottom"
                placeholder="Password" required>

            <div class="pull-right">
                <small><a href="user.php">ユーザー登録</a></small>
            </div>
            
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        </form>

<?php

// メッセージダイアログのHTML元ネタ
$dialog = <<< 'HTML'
<div class="alert alert-danger" id="alert_dialog">
    <span class="glyphicon glyphicon-exclamation-sign"></span>
    <span id="error_message">%s</span>
</div>
HTML;

if(strlen($errorMessage) > 0)
{
    // エラーメッセージがセットされている場合
    // エラーダイアログを出力する
    printf($dialog, escape($errorMessage));
}

?>

    </div>

</body>
</html>