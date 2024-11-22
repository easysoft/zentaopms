<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkQuestion');

class thinkScore extends thinkQuestion
{
    protected static array $defineProps = array
    (
        'fields?: array',          // 列标题
        'setOption?: bool=false',  // 选项配置方式
        'quoteTitle?: string',     // 列标题
        'quoteQuestions?: array',  // 引用问题
        'citation?: int=1',        // 引用方式
        'selectColumn?: string',   // 选择列
    );
}
