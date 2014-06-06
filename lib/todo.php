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
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);

        return null;
    }

    /**
     * 1件データを取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return Todo
     */
    public function find($conditions)
    {

    }

    /**
     * 全件取得
     * @param array $conditions keyとvalueの組み合わせ
     * @return array<Todo>
     */
    public function findAll($conditions)
    {

    }

    /**
     * 登録
     */
    public function insert()
    {

    }

    /**
     * 更新
     */
    public function update()
    {

    }

    /**
     * 削除
     */
    public function delete()
    {

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
}
