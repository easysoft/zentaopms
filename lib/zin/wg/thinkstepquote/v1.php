<?php
declare(strict_types=1);
namespace zin;

class thinkStepQuote extends wg
{
    private array $modules = array();

    protected static array $defineProps = array
    (
        'step?: object',           // 整个步骤的对象
        'questionType?: string',   // 问题类型
        'quoteQuestions?: array',  // 引用问题
        'setOption?: bool=false',  // 选项配置方式
        'quoteTitle?: string',     // 列标题
        'citation?: int=1',        // 引用方式
        'selectColumn?: string',   // 选择列
    );
}