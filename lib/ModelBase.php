<?php
// http://www.php.net//manual/ja/language.oop5.inheritance.php
// クラスの継承について

// http://www.php.net/manual/ja/language.oop5.overloading.php#object.get
// マジックメソッドを使用して、getter/setterの定義を行います。

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
     * @param mix $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * getter定義
     * プロパティを取得する
     * @param string $name
     * @return mix $value
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
        return 'sqlite:' . dirname(__FILE__) . '/todos.db';
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

        // sqlを実行する準備を行い、PDOStatementオブジェクトを返す
        $stat = $db->prepare($sql);
        // sqlを実行し、結果をPDOStatementオブジェクトに保持
        $stat->execute($params);
        // 全ての結果行を含む配列を返す
        $records = $stat->fetchAll();

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
        return $this->exec($sql, $params, TYPE_INSERT);
    }

    /**
     * UPDATEを実行する
     * @param string $sql
     * @param array $params
     * @return int 更新件数
     */
    protected function update($sql, $params=array())
    {
        return $this->exec($sql, $params, TYPE_UPDATE);
    }

    /**
     * DELETEを実行する
     * @param string $sql
     * @param array $params
     * @return int 削除件数
     */
    protected function delete($sql, $params=array())
    {
        return $this->exec($sql, $params, TYPE_DELETE);
    }

    /**
     * SQLを実行する
     * @param string $sql
     * @param array $params
     * @param int $type SQLの種別 (INSERT/UPDATE/DELETE)
     * @return int
     */
    protected function exec($sql, $params=array(), $type=TYPE_UPDATE)
    {
        $ret = 0;
        $db = new PDO($this->getDsn());

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

            if($type == TYPE_INSERT)
            {
                // INSERTの場合、autoincrementで採番されたidを返す
                $ret = $db->lastInsertId();
            }
        }
        catch(Exception ex)
        {
            $db->rollBack();
            throw ex;
        }

        return $ret;
    }
}
