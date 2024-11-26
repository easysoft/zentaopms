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
        if($step)
        {
            $fields         = !empty($step->options->fields) ? $step->options->fields : array();
            $scoreSetting   = isset($step->options->scoreSetting) ? $step->options->scoreSetting : '0';
            $quoteTitleList = !empty($step->options->quoteTitle) ? explode(", ", $step->options->quoteTitle) : array();
        }
        if(empty($fields) && !empty($quoteTitleList)) $fields = array($lang->thinkwizard->previewSteps->objectReference, $lang->thinkwizard->previewSteps->objectReference,$lang->thinkwizard->previewSteps->objectReference);

        $scoreCount = $scoreSetting == '0' ? 5 : 10;
        $items      = array();
        $radioItems = array();

        for($i=1; $i<=$scoreCount; $i++){$items[] = array('text' => $i, 'value' => $i, 'disabledPrefix' => true);}

        foreach ($fields as $field) {
            $radioItems[] = array(
                div(setClass('my-1 text-md leading-5 break-words'), $field),
                thinkBaseCheckbox
                (
                    set::type('radio'),
                    set::items($items),
                    set::name('result[]'),
                    set::value($result[0] ?? ''),
                    set::inline(true),
                    set::disabled(false)
                )
            );
        }
        $detailWg[] = div(setClass('score-content', $scoreSetting == '0' ? 'score-5' : 'score-10'), $radioItems);
        return $detailWg;
    }
}
