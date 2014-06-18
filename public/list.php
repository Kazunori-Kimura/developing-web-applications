<?php
// list.php
// todo一覧

include_once '../lib/util.php';
include_once '../lib/Todo.php';

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

// 認証チェック
if(!isset($_SESSION[S_KEY_USER_ID]))
{
    // ユーザーID取得失敗
    // -> ログイン画面に戻す
    header('Location index.php');
    exit;
}

// user_id取得
$uid = $_SESSION[S_KEY_USER_ID];

// 更新処理
// CSRF対策のsession_tokenを照会する
if(isset($_POST['token']))
{
    if($_POST['token'] !== $prevSessionToken)
    {
        $errorMessage = 'CSRFが疑われるため、処理を継続できません。';
    }
    else
    {
        
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

    <div class="page-header">
        <h1>ToDos</h1>
    </div>

    <form role="form" id="form"
        method="post" action="list.php">

        <input type="hidden" name="token"
            value="<?php echo($sessionToken); ?>">

        <a href="edit.php" class="btn btn-primary">追 加</a>

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
        <button type="button" class="btn btn-xs todoDone %s"
            data-todo-id="%d">
            <span class="glyphicon glyphicon-ok"></span>
        </button>
    </td>
    <td><p class="%s">%s</p></td>
    <td>
        <button type="button" class="btn btn-primary todoUpdate"
            data-todo-id="%d">
            <span class="glyphicon glyphicon-pencil"></span>
        </button>
    </td>
    <td>
        <button type="button" class="btn btn-danger todoRemove"
            data-todo-id="%d">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
    </td>
</tr>
ROW;

$todo = new Todo();

// ログインユーザーのIDをセット
$todo->user_id = $uid;

// 全てのTodoを取得
$todos = $todo->find();

foreach($todos as $t)
{
    // 完了フラグに応じてclass設定
    $btnClass = 'btn-default';
    $bodyClass = '';
    if($t->done){
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

        <!-- 処理種別 -->
        <input type="hidden" name="procType" value="">

        <!-- 対象ToDo -->
        <input type="hidden" name="todoId" value="">

        <!-- Errorメッセージ -->
        <input type="hidden" id="auth_message" value="<?php esc($errorMessage); ?>">

        <a href="edit.php" class="btn btn-primary">追 加</a>
    </form>

    <div class="alert alert-danger" id="alert_dialog">
        <span class="glyphicon glyphicon-exclamation-sign"></span>
        <span id="error_message"></span>
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