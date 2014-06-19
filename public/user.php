<?php
// user.php
// ユーザー登録画面
// http://getbootstrap.com/examples/signin/

include_once '../lib/util.php';
include_once '../lib/User.php';

// エラーメッセージ
$errorMessage = '';

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
        try
        {
            // ユーザー登録
            $errorMessage = regist();

            if(strlen($errorMessage) === 0)
            {
                // 登録成功時、一覧画面に遷移
                header('Location: list.php');
                exit;
            }
        }
        catch(Exception $ex)
        {
            $errorMessage = $ex->getMessage();
        }
    }
}

/**
 * ユーザー登録
 * @return string
 */
function regist()
{
    // フォームから入力を取得
    $mail = $_POST["user_id"];
    $pass1 = $_POST['pass'];
    $pass2 = $_POST['pass2'];

    // パスワードチェック
    if($pass1 != $pass2)
    {
        // パスワードが一致しない
        return 'パスワードが一致しません。';
    }

    // Userインスタンス
    $user = new User();

    // メールアドレス
    $user->mail = $mail;

    // パスワード
    // パスワード漏洩対策のため、saltを付けてストレッチングしている
    $user->password = stretch(AUTH_SALT .
        $user->mail .
        $pass1);

    if(! $user->isExists())
    {
        // ユーザー登録
        $user->add();

        // sessionにユーザーidをセット
        $_SESSION[S_KEY_USER_ID] = $user->id;
    }
    else
    {
        return '既に登録されています。';
    }

    return '';
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
            action="user.php">

            <input type="hidden" name="token"
                value="<?php echo($page['token']); ?>">

            <h2 class="form-signin-heading">Please sign up</h2>

            <input type="email"
                id="user_id" name="user_id" class="form-control form-signin-top"
                value="<?php
                        if($page['isPost'])
                        {
                            edq($_POST['user_id']);
                        }
                    ?>"
                placeholder="Email address" required autofocus>
            
            <input type="password"
                id="pass" name="pass" class="form-control form-signin-mid"
                placeholder="Password" required>

            <input type="password"
                id="pass2" name="pass2" class="form-control form-signin-bottom"
                placeholder="Retype Password" required>

            <button class="btn btn-lg btn-primary btn-block"
                type="submit">Sign up</button>

            <div class="pull-right">
                <p>
                    <a href="index.php">
                        <span class="glyphicon glyphicon-hand-left"></span>
                        戻る
                    </a>
                </p>
            </div>

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