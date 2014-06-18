<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Document</title>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <style>
    body {
        margin: 60px;
    }
    </style>
</head>
<body>

<h1>Model Test</h1>
<h2>User</h2>
<h3>User::add / User::find</h3>
<?php
    include_once('../lib/User.php');
    include_once('../lib/Todo.php');
    include_once('../lib/util.php');

    // ユーザー登録
    $user = new User();

    $user->mail = 'kazunori.kimura.js@gmail.com';

    // パスワード設定 1qaz
    $user->password = stretch(AUTH_SALT . $user->mail . '1qaz');

    if(! $user->isExists())
    {
        // ユーザー登録処理
        $user->add();

        // ユーザー登録完了
        prinrf('<p class="bg-success">ユーザー登録成功 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }

    // ユーザー検索処理
    $user->findFirst(array('mail'=>'kazunori.kimura.js@gmail.com'));

    if(! empty($user->mail))
    {
        // ユーザー検索完了
        prinrf('<p class="bg-success">ユーザー検索成功 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }
    else
    {
        // ユーザー検索失敗
        prinrf('<p class="bg-danger">ユーザー検索失敗 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }

?>

    <hr>

<h3>User::auth</h3>
<?php
    $user2 = new User();

    // メール
    $user2->mail = 'kazunori.kimura.js@gmail.com';
    // パスワード 1qaz
    $user2->password = stretch(AUTH_SALT . $user->mail . '1qaz');

    if($user2->auth())
    {
        // ユーザー認証完了
        prinrf('<p class="bg-success">ユーザー認証成功 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }
    else
    {
        // ユーザー認証失敗
        prinrf('<p class="bg-danger">ユーザー認証失敗 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }

?>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>