<?php
// http://www.php.net//manual/ja/language.oop5.inheritance.php
// クラスの継承について

// http://www.php.net/manual/ja/language.oop5.overloading.php#object.get
// マジックメソッドを使用して、getter/setterの定義を行います。

// クラスの定数を参照する際、self::CONSTANT_NAME としないと警告が出て、
// 勝手に'CONSTANT_NAME'という文字列として扱われる。

/**
 * Userクラス、Todoクラスの継承元となるクラス
 */
class ModelBase
{
    // SQLの種別
    const TYPE_SELECT = 0;
    const TYPE_INSERT = 1;
    const TYPE_UPDATE = 2;
    const TYPE_DELETE = 3;

    // プロパティ
    protected $data = array();

    /**
     * setter定義
     * プロパティに値をセットする
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * getter定義
     * プロパティを取得する
     * @param string $name
     * @return mixed $value
     */
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
     * PDO DSNを返す
     * @return string
     */
    protected function getDsn()
    {
        return 'sqlite:' . dirname(__file__) . DIRECTORY_SEPARATOR . 'todos.db';
    }

    /**
     * SQLを実行し結果行を返す
     * @param string $sql
     * @param array $params
     * @return array
     */
    protected function query($sql, $params=array())
    {
        $db = new PDO($this->getDsn());

        // エラー時にExceptionを出すよう変更
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sqlを実行する準備を行い、PDOStatementオブジェクトを返す
        $stat = $db->prepare($sql);

        // sqlを実行し、結果をPDOStatementオブジェクトに保持
        $stat->execute($params);

        // 全ての結果行を含む配列を返す
        $records = $stat->fetchAll();

        // カーソル閉じる (fetchAll時は不要だが、念のため)
        $stat->closeCursor();

        return $records;
    }

    /**
     * INSERTを実行する
     * @param string $sql
     * @param array $params
     * @return int 採番されたid
     */
    protected function insert($sql, $params=array())
    {
        return $this->exec($sql, $params, self::TYPE_INSERT);
    }

    /**
     * UPDATEを実行する
     * @param string $sql
     * @param array $params
     * @return int 更新件数
     */
    protected function update($sql, $params=array())
    {
        return $this->exec($sql, $params, self::TYPE_UPDATE);
    }

    /**
     * DELETEを実行する
     * @param string $sql
     * @param array $params
     * @return int 削除件数
     */
    protected function delete($sql, $params=array())
    {
        return $this->exec($sql, $params, self::TYPE_DELETE);
    }

    /**
     * SQLを実行する
     * @param string $sql
     * @param array $params
     * @param int $type SQLの種別 (INSERT/UPDATE/DELETE)
     * @return int
     */
    protected function exec($sql, $params=array(), $type=self::TYPE_UPDATE)
    {
        $ret = 0;
        $db = new PDO($this->getDsn());

        // エラー時にExceptionを出すよう変更
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try
        {
            $db->beginTransaction();

            // sqlを実行する準備を行い、PDOStatementオブジェクトを返す
            $stat = $db->prepare($sql);
            
            // sqlを実行し、結果をPDOStatementオブジェクトに保持
            $stat->execute($params);

            // 直近の DELETE, UPDATE 文によって作用した行数を返す
            $ret = $stat->rowCount();

            $db->commit();

            if($type == self::TYPE_INSERT)
            {
                // INSERTの場合、autoincrementで採番されたidを返す
                $ret = $db->lastInsertId();
            }
        }
        catch(Exception $ex)
        {
            $db->rollBack();
            throw $ex;
        }

        return $ret;
    }
}
