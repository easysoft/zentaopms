<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师$APPEALS模型部件类。
 * thinmory $APPEALS model widget class.
 */
class thinkAppeals extends thinkModel
{
    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }
}
