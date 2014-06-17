<?php

include_once('ModelBase.php');

/**
 * Todoクラス
 *
 *  create table todos (
 *      id integer primary key autoincrement,
 *      user_id integer,
 *      body text,
 *      done boolean,
 *      create_at datetime,
 *      update_at datetime
 *  );
 */
class Todo extends ModelBase
{
    // コンストラクタ
    public function __construct()
    {
        $this->id = 0;
        $this->user_id = 0;
        $this->body = '';
        $this->done = false;
        $this->created_at = 0;
        $this->updated_at = 0;
    }

    /**
     * 1件データを取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return Todo
     */
    public function findFirst($conditions)
    {
        $todos = $this->select($conditions);
        if(count($todos) > 0)
        {
            $this->id = $todos[0]->id;
            $this->user_id = $todos[0]->user_id;
            $this->body = $todos[0]->body;
            $this->done = $todos[0]->done;
            $this->created_at = $todos[0]->created_at;
            $this->updated_at = $todos[0]->updated_at;

            return $this;
        }
        return null;
    }

    /**
     * データ取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    public function find($conditions)
    {
        return $this->select($conditions);
    }

    /**
     * 指定されたIDのTodoが存在するか？
     * @param string $id
     * @return bool
     */
    public function isExists($id)
    {
        $todos = $this->select(array('id' => $id));
        if(count($todos) > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * 検索処理
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    private function select($conditions)
    {
        // sql本体
        $sql = <<<'SQL'
SELECT
    T.id
    , T.user_id
    , T.body
    , T.done
    , T.created_at
    , T.updated_at
FROM
    Todos T 
%s
ORDER BY
    T.id
SQL;

        // WHERE句
        $sqlWhere = '';

        // SQL実行時に渡すパラメータ
        $params = array();

        if(count($conditions) > 0)
        {
            if(array_key_exists('id', $conditions))
            {
                // idはPrimary Keyなので、ToDoが特定できる
                // -> 他の検索条件は無視する
                $sqlWhere = ' WHERE id = :id';
                $params[':id'] = $conditions['id'];
            }
            else
            {
                $cond = array();
                if(array_key_exists('user_id', $conditions))
                {
                    $cond[] = ' user_id = :user_id ';
                    $params[':user_id'] = $conditions['user_id'];
                }
                if(array_key_exists('done', $conditions))
                {
                    $cond[] = ' done = :done ';
                    $params[':done'] = $conditions['done'];
                }

                if(count($cond) > 0)
                {
                    // and で連結
                    $sqlWhere = ' WHERE ' . join(' and ', $cond);
                }
            }
        }

        // sql生成
        $sql = sprintf($sql, $sqlWhere);

        // sql実行
        $records = $this->query($sql, $params);

        $todos = array();
        foreach($records as $record)
        {
            $todo = new Todo();
            $todo->id = $record['id'];
            $todo->user_id = $record['user_id'];
            $todo->body = $record['body'];
            $todo->done = $record['done'];
            $todo->created_at = $record['created_at'];
            $todo->updated_at = $record['updated_at'];

            $todos[] = $todo;
        }

        return $todos;
    }

    /**
     * 登録
     * @return Todo
     */
    public function insert()
    {
        // id存在チェック
        if(! $this->isExists($this->id))
        {
            // SQL
            $sql = <<< 'SQL'
INSERT INTO todos
(
    user_id
    , body
    , done
    , created_at
)
VALUES
(
    :user_id
    , :body
    , :done
    , current_timestamp
)
SQL;

            // パラメータ生成
            $params = array(
                    ':user_id' => $this->user_id,
                    ':body' => $this->body,
                    ':done' => $this->done
                );

            // SQL実行
            $id = $this->insert($sql, $params);

            // 採番されたidをセット
            $this->id = $id;

            return $this;
        }

        throw new Exception('Todo登録エラー');
        return null;
    }

    /**
     * 更新
     * @return Todo
     */
    public function update()
    {
        // id存在チェック
        if($this->isExists($this->id))
        {
            // SQL
            $sql = <<< 'SQL'
UPDATE todos
SET
    body = :body
    , done = :done
    , updated_at = current_timestamp
WHERE
    id = :id
SQL;

            // パラメータ生成
            $params = array(
                    ':id' => $this->id,
                    ':body' => $this->body,
                    ':done' => $this->done
                );

            $this->update($sql, $params);

            return $this;
        }

        throw new Exception(sprintf('指定されたIDのTodoが存在しません。id=%s', $this->id);
        return null;
    }

    /**
     * 削除
     * @return int
     */
    public function delete()
    {
        // id存在チェック
        if($this->isExists($this->id))
        {
            // SQL
            $sql = <<< 'SQL'
DELETE FROM todos
WHERE
    id = :id
SQL;

            // パラメータ生成
            $params = array(':id' => $this->id);

            return $this->delete($sql, $params);
        }

        return 0;
    }
}
