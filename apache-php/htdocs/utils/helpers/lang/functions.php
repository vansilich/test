<?php

function mb_ucfirst(string $str, string $encoding): string
{
    $firstChar = mb_substr($str, 0, 1, $encoding);
    $then = mb_substr($str, 1, null, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}