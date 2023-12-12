<?php
declare(strict_types=1);
namespace zin\utils;

function jsonEncode($data, $flags = 0, $depth = 512): string|false
{
    $data = unHTMLArray($data);
    return json_encode($data, $flags, $depth);
}
