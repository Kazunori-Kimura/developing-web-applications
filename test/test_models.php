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
        printf('<p class="bg-success">ユーザー登録成功 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }

    // ユーザー検索処理
    $user->findFirst(array('mail'=>'kazunori.kimura.js@gmail.com'));

    printf('<p class="bg-info">id=%d, mail=%s, password=%s</p>',
        $user->id,
        $user->mail,
        $user->password);

    if(strlen($user->mail) > 0)
    {
        // ユーザー検索完了
        printf('<p class="bg-success">ユーザー検索成功 (id=%d, mail=%s)</p>',
            $user->id,
            $user->mail);
    }
    else
    {
        // ユーザー検索失敗
        printf('<p class="bg-danger">ユーザー検索失敗 (id=%d, mail=%s)</p>',
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
        printf('<p class="bg-success">ユーザー認証成功 (id=%d, mail=%s)</p>',
            $user2->id,
            $user2->mail);
    }
    else
    {
        // ユーザー認証失敗
        printf('<p class="bg-danger">ユーザー認証失敗 (id=%d, mail=%s)</p>',
            $user2->id,
            $user2->mail);
    }

?>

<hr>

<h2>Todos</h2>
<h3>add</h3>

<?php
    $todo = new Todo();

    $todo->user_id = $user2->id;
    $todo->body = 'dummy text!!';

    $todo->add();

    printf('<p class="bg-success">id=%d, body=%s, create_at=%s(%s)</p>',
        $todo->id,
        $todo->body,
        $todo->create_at,
        gettype($todo->create_at));

?>

<h3>find</h3>

<table class="table table-striped">
    <thead>
        <th>id</th>
        <th>body</th>
        <th>done</th>
        <th>create_at</th>
    </thead>
    <tbody>
<?php
    $todos = $todo->find();

    $row = <<< 'HTML'
<tr>
    <td>%d</td>
    <td>%s</td>
    <td>%d</td>
    <td>%s</td>
</tr>
HTML;

    foreach($todos as $t)
    {
        printf($row,
            $t->id,
            $t->body,
            $t->done,
            $t->create_at);
    }
?>
    </tbody>
</table>

<hr>

<h3>update</h3>

<?php
// 更新
$todo->body = 'update text.';
$todo->done = true;

$todo->sync();

    printf('<p class="bg-success">id=%d, body=%s, done=%s, update_at=%s</p>',
        $todo->id,
        $todo->body,
        $todo->done,
        $todo->update_at);

?>

<hr>

<h3>remove</h3>

<?php
// 削除

$todo->remove();

?>

<table class="table table-striped">
    <thead>
        <th>id</th>
        <th>body</th>
        <th>done</th>
        <th>create_at</th>
        <th>update_at</th>
    </thead>
    <tbody>
<?php
    $todos = $todo->find();

    $row = <<< 'HTML'
<tr>
    <td>%d</td>
    <td>%s</td>
    <td>%d</td>
    <td>%s</td>
    <td>%s</td>
</tr>
HTML;

    foreach($todos as $t)
    {
        printf($row,
            $t->id,
            $t->body,
            $t->done,
            $t->create_at,
            $t->update_at);
    }
?>
    </tbody>
</table>

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</body>
</html>