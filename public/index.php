<?php
    include_once '../lib/util.php';
    include_once '../lib/todo.php';

    // Todo全件取得
    $t = new Todo();
    $todos = $t->findAll();

?><!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Simple Todo</title>
    <style>
    .done a{
        text-decoration: line-through;
        color: #ccc;
    }
    </style>
</head>
<body>
    <ul>
        <?php
            foreach($todos as $todo)
            {
                $item = '<li class="todo %s"><a href="edit.php?id=%s">%s</a></li>';
                $className = '';
                if($todo->done)
                {
                    $className = 'done';
                }

                printf($item, $className, $todo->id, esc($todo->title));
            }
        ?>
    </ul>
</body>
</html>