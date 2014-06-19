<?php
// edit.php
// 編集画面

include_once '../lib/util.php';
include_once '../lib/Todo.php';

// エラーメッセージ
$errorMessage = '';

// ページ読み込み時の共通処理
$page = initPage();

// 認証チェック
if(! $page['isAuth'])
{
    // ユーザーID取得失敗
    // -> ログイン画面に戻す
    header('Location: index.php');
    exit;
}

// Todoインスタンス生成
$todo = new Todo();

// todo_id取得
if(isset($_GET["id"]))
{
    // QueryStringからidを取得
    $todoId = (int)$_GET["id"];

    // Todo検索
    $todo->findFirst(array('id'=>$todoId));
}

// 更新処理
if($page['isPost'])
{
    if($page['isCSRF'])
    {
        $errorMessage = 'CSRFが疑われるため、処理を継続できません。';
    }
    else
    {
        try
        {
            // 更新
            update($page['uid']);

            // 一覧ページに戻る
            header('Location: list.php');
            exit;
        }
        catch(Exception $ex)
        {
            $errorMessage = $ex->getMessage();
        }
    }
}

// 更新処理
function update($uid)
{
    // formから送られてきた値を取得
    // http://www.php.net/manual/ja/language.types.type-juggling.php#language.types.typecasting
    $id = (int)$_POST['todoId'];
    $body = $_POST['todoBody'];
    $done = (boolean)$_POST['todoDone'];

    // todoインスタンス生成
    $todo = new Todo();

    if($id === 0)
    {
        // postされた値をセット
        $todo->user_id = $uid;
        $todo->body = $body;
        $todo->done = $done;

        // 登録
        $todo->add();
    }
    else
    {
        // idに一致するTodoを取得
        $todo->findFirst(array('id'=>$id));

        // postされた値をセット
        $todo->body = $body;
        $todo->done = $done;

        // 更新
        $todo->sync();
    }
}

?><!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Todo</title>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a href="#" class="navbar-brand">更新画面</a>
            </div>
            <div class="collapse navbar-collapse navbar-right">
                <a href="index.php?logout=1" class="btn btn-default navbar-btn">LOG OUT</a>
            </div>
        </div>
    </div>

    <form role="form" id="form"
        method="post" action="edit.php">

        <input type="hidden" name="token"
            value="<?php echo($page['token']); ?>">

        <input type="hidden" name="todoId"
            value="<?php echo($todo->id); ?>">

        <div class="form-group">
            <label for="todoBody">内容</label>
            <textarea name="todoBody"
                class="form-control" id="todoBody"
                rows="4"
                value="<?php edq($todo->body); ?>">
            </textarea>
        </div>

        <div class="checkbox">
            <label for="todoDone">
                <input type="checkbox"
                    id="todoDone" name="todoDone" value="1"
<?php
    if($todo->done === 1)
    {
        echo('checked');
    }
?>
                    >
                完了
            </label>
        </div>

        <button type="submit" class="btn btn-primary">登 録</button>
        <a href="list.php" class="btn btn-default">キャンセル</a>
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


</body>
</html>