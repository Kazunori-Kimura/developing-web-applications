<?php
    include_once '../lib/util.php';
    include_once '../lib/todo.php';

    $todo = new Todo();

    if(!empty($_POST["id"]))
    {
        $todo->id = $_POST["id"];
        $todo->title = $_POST["title"];
        $todo->description = $_POST["description"];

        if(isset($_POST["done"]))
        {
            $todo->done = true;
        }

        if($todo->id == '')
        {
            // 登録
            $todo->insert();
        }
        else
        {
            // 更新
            $todo->update();
        }
    }

    if(!empty($_GET["id"]))
    {
        $todo->find($_GET["id"]);
    }

?><!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Simple Todo</title>
</head>
<body>
    <form method="post" action="edit.php">
        <input type="hidden" name="id" id="id" value="<?php $todo->id ?>">

        <label class="label" for="title">タイトル:</label>
        <input type="text" name="title" id="title" value="<?php $todo->title ?>"><br>
        
        <label class="label" for="description">内容:</label>
        <textarea name="description" id="description" cols="30" rows="10"><?php $todo->description ?></textarea><br>
        
        <label for="done"><input type="checkbox" id="done" name="done" value="1"
            <?php if($todo->done){ echo("checked"); } ?>>完了</label>
        
        <div><span class="label">作成日</span><span class="date"><?php $todo->created_at ?></span></div>
        <div><span class="label">更新日</span><span class="date"><?php $todo->updated_at ?></span></div>

        <div>
            <input type="submit" value="更新">
            <a href="index.php">戻る</a>
        </div>
    </form>
</body>
</html>