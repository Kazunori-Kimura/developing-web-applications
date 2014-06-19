<?php
// util.php

// SESSIONのキー: User ID
define('S_KEY_USER_ID', 'USER_ID');

// password の salt ('hoge'のsha256)
define('AUTH_SALT', 'ecb666d778725ec97307044d642bf4d160aabb76f56c0069c71ea25b1e926825');

// hash algorithm
define('HASH_ALGOS', 'sha256');

// session token の salt ('1234'のsha256)
define('TOKEN_SALT', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4');


/**
 * ストレッチング処理
 * @param string $str 元文字列
 * @param int $number 試行回数
 * @return string
 */
function stretch($str, $number=10)
{
    $ret = $str;
    
    for($i = 0; $i <= $number; $i++)
    {
        $ret = hash(HASH_ALGOS, $ret);
    }

    return $ret;
}

/**
 * 特殊文字を HTML エンティティに変換し、出力する
 * @param string $html
 */
function esc($html)
{
    // XSS対策のため、特殊文字を HTML エンティティに変換
    echo(escape($html));
}

/**
 * 特殊文字を HTML エンティティに変換する
 * @param string $html
 * @return string
 */
function escape($html)
{
    // XSS対策のため、特殊文字を HTML エンティティに変換
    return htmlentities($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * ダブルクォーテーションをエスケープして出力する
 * @param string $value
 */
function edq($value)
{
    echo(escapeDoubleQuarto($value));
}

/**
 * ダブルクォーテーションをエスケープする
 * @param string $value
 * @return string
 */
function escapeDoubleQuarto($value)
{
    return str_replace('"', '\"', $value);
}


/**
 * ページ読み込み時の共通処理
 * sessionの開始、認証チェック、CSRFチェック
 * @return array
 */
function initPage()
{
    // Sessionの開始
    session_start();

    // session_id更新前に、CSRF対策用のトークンを再生成しておく
    $prevSessionToken = stretch(TOKEN_SALT . session_id());

    // Session乗っ取り対策のため、session_idを更新する
    session_regenerate_id();

    // CSRF対策のためのsession_tokenを生成する
    $sessionToken = stretch(TOKEN_SALT . session_id());

    // 認証チェックとログインユーザーIDの取得
    $isAuth = false;
    $uid = 0;

    if(isset($_SESSION[S_KEY_USER_ID]))
    {
        $isAuth = true;
        $uid = (int)$_SESSION[S_KEY_USER_ID];
    }

    // POST時はCSRFのチェックを行う
    $isPost = false;
    $isCSRF = false;
    if(isset($_POST['token']))
    {
        $isPost = true;

        // 埋め込まれたtokenとsession_idから生成したtokenを比較
        if($_POST['token'] !== $prevSessionToken)
        {
            $isCSRF = true;
        }
    }

    return array(
            'token' => $sessionToken,
            'uid' => $uid,
            'isAuth' => $isAuth,
            'isPost' => $isPost,
            'isCSRF' => $isCSRF
        );
}

