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
    echo(htmlentities($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}




