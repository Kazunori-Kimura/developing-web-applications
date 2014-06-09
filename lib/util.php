<?php
// util.php

/**
 * 特殊文字を HTML エンティティに変換し、出力する
 */
function esc($html)
{
    echo(htmlentities($html, ENT_QUOTES | ENT_HTML5));
}

