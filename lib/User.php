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
        $this->id = 0;
        $this->mail = '';
        $this->password = '';
        $this->last_login = 0;
        $this->created_at = 0;
        $this->updated_at = 0;
    }


    public function findFirst($conditions)
    {

    }

    public function find($conditions)
    {

    }

    public function isExists($mail)
    {

    }

    private function select($conditions)
    {

    }

    public function insert()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
