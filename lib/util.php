<?php
// util.php

// --- 認証処理 ---
// SESSIONキー: User ID
define('S_KEY_USER_ID', 'USER_ID');

// SESSIONキー: 認証トークン
define('S_KEY_TOKEN', 'TOKEN');

// salt
define('AUTH_SALT', 'hoge');

// hash algorithm
define('HASH_ALGOS', 'sha256');

// test authentication
define('USER_NAME', 'kimura');
// '1234'
define('HASH_PASS', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');

/**
 * ユーザー名、パスワードを元に認証処理を行う
 */
function authenticate($user, $pass)
{
    // ユーザーID,パスワードのチェック。
    // 今回は固定文字列とする
    if($user === USER_NAME && hash(HASH_ALGOS, $pass) === HASH_PASS)
    {
        // * uniqid は、暗号学的にセキュアなトークンを返すわけではありません! *
        $userId = $user .'@'. uniqid();
        // ユーザーIDを元に認証トークンを取得
        $token = getToken($userId);

        // 一旦sessionを全て破棄
        @session_destroy(); // エラー無視
        // session開始
        session_start();

        // sessionに保持
        $_SESSION[S_KEY_USER_ID] = $userId;
        $_SESSION[S_KEY_TOKEN] = $token;

        return true;
    }
    else
    {
        // 認証失敗
        return false;
    }
}

/**
 * 認証トークンを取得する
 * @param string $user User ID
 * @return string
 */
function getToken($user)
{
    // 3回 sha256をかける
    return hash(HASH_ALGOS, hash(HASH_ALGOS, hash(HASH_ALGOS, AUTH_SALT . $user)));
}

// --- HTML出力 ---

/**
 * 特殊文字を HTML エンティティに変換し、出力する
 * @param string $html
 */
function esc($html)
{
    echo(htmlentities($html, ENT_QUOTES | ENT_HTML5));
}




