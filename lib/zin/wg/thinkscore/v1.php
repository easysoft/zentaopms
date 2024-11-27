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
        'criterions5?: array',     // 5分制的评分标准
        'criterions10?: array',    // 10分制的评分标准
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
        list($step, $scoreSetting, $criterions5, $criterions10) = $this->prop(array('step', 'scoreSetting', 'criterions5', 'criterions10'));
        if($step)
        {
            $fields         = !empty($step->options->fields) ? $step->options->fields : array();
            $scoreSetting   = isset($step->options->scoreSetting) ? $step->options->scoreSetting : '0';
            $quoteTitleList = !empty($step->options->quoteTitle) ? explode(", ", $step->options->quoteTitle) : array();
            $criterions5    = isset($step->options->criterions5) ? $step->options->criterions5 : $lang->thinkwizard->criterion->defaultCriteria5;
            $criterions10   = isset($step->options->criterions10) ? $step->options->criterions10 : $lang->thinkwizard->criterion->defaultCriteria10;
            $answer         = $step->answer;
            $result         = !empty($answer->result) ? $answer->result : array();
        }
        if(empty($fields) && !empty($quoteTitleList)) $fields = array($lang->thinkwizard->previewSteps->objectReference, $lang->thinkwizard->previewSteps->objectReference,$lang->thinkwizard->previewSteps->objectReference);

        $scoreCount = $scoreSetting == '0' ? 5 : 10;
        $items      = array();
        $radioItems = array();
        $criterions = $scoreSetting == '0' ? $criterions5 : $criterions10;

        for($i=1; $i<=$scoreCount; $i++){$items[] = array('text' => $i, 'value' => $i, 'disabledPrefix' => true, 'title' => $criterions[$i - 1]);}

        foreach ($fields as $index => $field)
        {
            $radioItems[] = array(
                div(setClass('my-1 text-md leading-5 break-words'), $field),
                thinkBaseCheckbox
                (
                    set::type('radio'),
                    set::items($items),
                    set::name('result[' . $index . ']'),
                    set::value(!empty($result[$index]) ? $result[$index] : ''),
                    set::inline(true),
                    set::disabled(false)
                )
            );
        }
        $detailWg[] = div(setClass('score-content', $scoreSetting == '0' ? 'score-5' : 'score-10'), $radioItems);
        return $detailWg;
    }
}
