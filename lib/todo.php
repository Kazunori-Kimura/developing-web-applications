<?php
// http://www.php.net/manual/ja/language.oop5.overloading.php#object.get
// マジックメソッドを使用して、getter/setterの定義を行います。

/**
 * Todoクラス
 *
 *  - string $id
 *  - string $title
 *  - string $description
 *  - bool $done
 *  - datetime $created_at
 *  - datetime $updated_at
 */
class Todo
{
    // プロパティ
    private $data = array();

    public function __construct()
    {
        $this->id = '';
        $this->title = '';
        $this->description = '';
        $this->done = false;

        $this->created_at = 0;
        $this->updated_at = 0;
    }

    // setter
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    // getter
    public function __get($name)
    {
        if(array_key_exists($name, $this->data))
        {
            return $this->data[$name];
        }

        // 存在しないプロパティ名を指定された場合
        // エラーを発生させる
        throw new Exception(sprintf('存在しないプロパティです。%s', $name));

        return null;
    }

    /**
     * 1件データを取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return Todo
     */
    public function find($conditions)
    {
        $todos = $this->select($conditions);
        if(count($todos) > 0)
        {
            $this->id = $todos[0]->id;
            $this->title = $todos[0]->title;
            $this->description = $todos[0]->description;
            $this->done = $todos[0]->done;
            $this->created_at = $todos[0]->created_at;
            $this->updated_at = $todos[0]->updated_at;

            return $this;
        }
        return null;
    }

    /**
     * 全件取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    public function findAll($conditions)
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
    , T.title
    , T.description
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
        $params = array();
        if(count($conditions) > 0)
        {
            if(array_key_exists('id', $conditions))
            {
                // id設定時
                $sqlWhere = ' WHERE id = :id';
                $params[':id'] = $conditions['id'];
            }
            else
            {
                $cond = array();
                if(array_key_exists('title', $conditions))
                {
                    // titleは部分一致
                    $cond[] = ' title like :title ';
                    $params[':title'] = '%' . $conditions['title'] . '%';
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
            $todo->titile = $record['title'];
            $todo->description = $record['description'];
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
        // idを生成
        $this->id = $this->createUniqId();

        // id存在チェック
        if(! $this->isExists($this->id))
        {
            // SQL
            $sql = <<< 'SQL'
INSERT INTO todos
(
    id
    , title
    , description
    , done
    , created_at
)
VALUES
(
    :id
    , :titile
    , :description
    , :done
    , current_timestamp
)
SQL;

            // パラメータ生成
            $params = array(
                    ':id' => $this->id,
                    ':title' => $this->title,
                    ':description' => $this->description,
                    ':done' => $this->done
                );

            $this->exec($sql, $params);

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
    title = :title
    , description = :description
    , done = :done
    , updated_at = current_timestamp
WHERE
    id = :id
SQL;

            // パラメータ生成
            $params = array(
                    ':id' => $this->id,
                    ':title' => $this->title,
                    ':description' => $this->description,
                    ':done' => $this->done
                );

            $this->exec($sql, $params);

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

            return $this->exec($sql, $params);
        }

        return 0;
    }

    /**
     * UniqIdを生成する
     * @return string
     */
    private function createUniqId()
    {
        $uid = uniqid();
        $hash = $uid . md5($this->data['title']);

        return sprintf('%08s-%04s-%04s-%04s-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash,12, 4),
            substr($hash,16, 4),
            substr($hash,20,12));
    }


    /**
     * PDO DSNを返す
     * @return string
     */
    private function getDsn()
    {
        return 'sqlite:' . dirname(__FILE__) . '/todos.db';
    }

    /**
     * SQLを実行し結果行を返す
     * @param string $sql
     * @param array $params
     * @return array
     */
    private function query($sql, $params=array())
    {
        $db = new PDO($this->getDsn());

        // sqlを実行する準備を行い、PDOStatementオブジェクトを返す
        $stat = $db->prepare($sql);
        // sqlを実行し、結果をPDOStatementオブジェクトに保持
        $stat->execute($params);
        // 全ての結果行を含む配列を返す
        $records = $stat->fetchAll();

        return $records;
    }

    /**
     * SQLを実行し作用した件数を返す
     * @param string $sql
     * @param array $params
     * @return int
     */
    private function exec($sql, $params=array())
    {
        $count = 0;
        $db = new PDO($this->getDsn());

        try
        {
            $db->beginTransaction();

            // sqlを実行する準備を行い、PDOStatementオブジェクトを返す
            $stat = $db->prepare($sql);
            // sqlを実行し、結果をPDOStatementオブジェクトに保持
            $stat->execute($params);
            // 直近の DELETE, INSERT, UPDATE 文によって作用した行数を返す
            $count = $stat->rowCount();

            $db->commit();
        }
        catch(Exception ex)
        {
            $db->rollBack();
            throw ex;
        }

        return $count;
    }
}
