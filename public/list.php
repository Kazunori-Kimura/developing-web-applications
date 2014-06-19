<?php
// list.php
// todo一覧

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
            update();
        }
        catch(Exception $ex)
        {
            $errorMessage = $ex->getMessage();
        }
    }
}

// 更新処理
function update()
{
    $todo = new Todo();

    // クリックされたボタンのnameにvalueがセットされる
    // 押されていないボタンのnameに該当する$_POSTはセットされない
    if(isset($_POST['todoDone']))
    {
        // Todo取得
        $todo->findFirst(
            array('id' => (int)$_POST['todoDone'])
        );

        // doneを反転させる
        $todo->done = ! $todo->done;

        // 更新する
        $todo->sync();
    }
    else if(isset($_POST['todoRemove']))
    {
        // Todo取得
        $todo->findFirst(
            array('id' => (int)$_POST['todoRemove'])
        );

        // 削除する
        $todo->remove();
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
                <a href="#" class="navbar-brand">一覧画面</a>
            </div>
            <div class="collapse navbar-collapse navbar-right">
                <a href="index.php?logout=1" class="btn btn-default navbar-btn">LOG OUT</a>
            </div>
        </div>
    </div>


    <form role="form" id="form"
        method="post" action="list.php">

        <input type="hidden" name="token"
            value="<?php echo($page['token']); ?>">

        <div class="pull-right">
            <a href="edit.php" class="btn btn-primary">追 加</a>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>完了</th>
                    <th>内容</th>
                    <th>更新</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
<?php

// 行のHTML元ネタ
$rowTemplate = <<< 'ROW'
<tr>
    <td>
        <button type="submit"
            name="todoDone"
            class="btn btn-xs todoDone %s"
            value="%d">
            <span class="glyphicon glyphicon-ok"></span>
        </button>
    </td>
    <td><p class="%s">%s</p></td>
    <td>
        <a class="btn btn-primary todoUpdate"
            href="edit.php?id=%d">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
    </td>
    <td>
        <button type="submit"
            name="todoRemove"
            class="btn btn-danger todoRemove"
            value="%d">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
    </td>
</tr>
ROW;

$todo = new Todo();

// ログインユーザーのIDをセット
$todo->user_id = $page['uid'];

// 全てのTodoを取得
$todos = $todo->find();

foreach($todos as $t)
{
    // 完了フラグに応じてclass設定
    $btnClass = 'btn-default';
    $bodyClass = '';
    if($t->done)
    {
        $btnClass = 'btn-success';
        $bodyClass = 'done';
    }

    // 行出力
    printf($rowTemplate,
        $btnClass,
        $t->id,
        $bodyClass,
        escape($t->body),
        $t->id,
        $t->id
    );
}

?>
            </tbody>
        </table>
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