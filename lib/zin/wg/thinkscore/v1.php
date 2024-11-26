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
        'scoreSetting?: string=0', // 分制设置
    );

    public static function getPageCSS(): ?string
    {
        $baseCss = file_get_contents(dirname(__FILE__, 2) . DS . 'thinkstepbase' . DS . 'css' . DS . 'v1.css');
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css') . $baseCss;
    }

    protected function buildDetail(): array
    {
        global $lang;
        $detailWg = parent::buildDetail();
        list($step, $quoteQuestions, $preViewModel, $isRun, $scoreSetting) = $this->prop(array('step', 'quoteQuestions', 'preViewModel', 'isRun', 'scoreSetting'));
        return $detailWg;
    }
}
