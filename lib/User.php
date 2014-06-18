<?php

include_once('ModelBase.php');

/**
 * Userクラス
 *
 *  create table users (
 *      id integer primary key autoincrement,
 *      mail text unique,
 *      password text,
 *      last_login datetime,
 *      create_at datetime,
 *      update_at datetime
 *  );
 */
class User extends ModelBase
{
    // コンストラクタ
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * 初期化処理
     */
    public function initialize()
    {
        $this->id = 0;
        $this->mail = '';
        $this->password = '';
        $this->last_login = 0;
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
        // 初期化
        $this->initialize();

        // 検索
        $users = $this->select($conditions);

        // 先頭項目を取得
        if(count($users) > 0)
        {
            $this->id = $users[0]->id;
            $this->mail = $users[0]->mail;
            $this->password = $users[0]->password;
            $this->last_login = $users[0]->last_login;
            $this->created_at = $users[0]->created_at;
            $this->updated_at = $users[0]->updated_at;
        }

        return $this;
    }

    /**
     * データ取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    public function find($conditions=array())
    {
        return $this->select($conditions);
    }

    /**
     * 指定されたユーザーが存在するか？
     * @return bool
     */
    public function isExists()
    {
        $users = $this->select(array('mail' => $this->mail));

        if(count($users) > 0)
        {
            return true;
        }
        return false;
    }

    /**
     * 認証を行う
     * @return bool
     */
    public function auth()
    {
        $params = array(
            'mail' => $this->mail,
            'password' => $this->password
        );

        // 1件取得
        $this->findFirst($params);

        if(! empty($this->id))
        {
            // ログイン日時更新
            $this->logined();

            // 認証成功
            return true;
        }

        // 認証失敗
        return false;
    }

    /**
     * 検索処理
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    private function select($conditions)
    {
        // sql
        $sql = <<<'SQL'
SELECT
    U.id
    , U.mail
    , U.password
    , U.last_login
    , U.created_at
    , U.updated_at
FROM
    Users U
%s
ORDER BY
    U.id
SQL;

        // WHERE句
        $sqlWhere = '';

        // SQL実行時に渡すパラメータ
        $params = array();

        if(count($conditions) > 0)
        {
            if(array_key_exists('id', $conditions))
            {
                // idはPrimary Keyなので、Userが特定できる
                // -> 他の検索条件は無視する
                $sqlWhere = ' WHERE id = :id';
                $params[':id'] = $conditions['id'];
            }
            else
            {
                $cond = array();
                if(array_key_exists('mail', $conditions))
                {
                    $cond[] = ' mail = :mail ';
                    $params[':mail'] = $conditions['mail'];
                }
                if(array_key_exists('password', $conditions))
                {
                    $cond[] = ' password = :password ';
                    $params[':password'] = $conditions['password'];
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

        $users = array();
        foreach($records as $record)
        {
            $user = new User();
            $user->id = $record['id'];
            $user->mail = $record['mail'];
            $user->password = $record['password'];
            $user->last_login = $record['last_login'];
            $user->created_at = $record['created_at'];
            $user->updated_at = $record['updated_at'];

            $users[] = $user;
        }

        return $users;
    }

    /**
     * 登録
     * @return User
     */
    public function add()
    {
        // 存在チェック
        if(! $this->isExists())
        {
            // SQL
            $sql = <<< 'SQL'
INSERT INTO users
(
    mail
    , password
    , created_at
)
VALUES
(
    :mail
    , :password
    , current_timestamp
)
SQL;

            // パラメータ生成
            $params = array(
                ':mail' => $this->mail,
                ':password' => $this->password
            );

            // SQL実行
            $id = $this->insert($sql, $params);

            // 採番されたidをセット
            $this->id = $id;

            return $this;
        }

        throw new Exception('User登録エラー');
        return null;
    }

    /**
     * ログイン日時更新
     * return User
     */
    private function logined()
    {
        if($this->isExists())
        {
            // SQL
            $sql = <<< 'SQL'
UPDATE users
SET
    last_login = current_timestamp
WHERE
    id = :id
SQL;

            // パラメータ生成
            $params = array(
                ':id' => $this->id
            );

            // SQL実行
            $this->update($sql, $params);

            // ユーザー情報再取得
            $this->findFirst(array('id' => $this->id));

            return $this;
        }

        // 更新失敗
        throw new Exception("ユーザー更新エラー", 1);
        return null;
    }

    /**
     * 更新
     * return User
     */
    public function sync()
    {
        if($this->isExists())
        {
            // SQL
            $sql = <<< 'SQL'
UPDATE users
SET
    mail = :mail
    , password = :password
    , updated_at = current_timestamp
WHERE
    id = :id
SQL;

            // パラメータ生成
            $params = array(
                ':id' => $this->id,
                ':mail' => $this->mail,
                ':password' => $this->password
            );

            // SQL実行
            $this->update($sql, $params);

            return $this;
        }

        // 更新失敗
        throw new Exception("ユーザー更新エラー", 1);
        return null;
    }

    /**
     * 削除
     * @return int
     */
    public function remove()
    {
        // 存在チェック
        if($this->isExists())
        {
            // SQL
            $sql = <<< 'SQL'
DELETE FROM users
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
