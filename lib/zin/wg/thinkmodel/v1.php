<?php
declare(strict_types=1);
namespace zin;

class thinkModel extends wg
{
    protected static array $defineProps = array(
        'mode?: string', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array'  // 模型节点
    );

    protected function buildQuestionItem(object $step): wg|array
    {
        $questionType = $step->options->questionType;
        $wgMap        = array('input' => 'thinkInput', 'radio' => 'thinkRadio', 'checkbox' => 'thinkCheckbox', 'tableInput' => 'thinkTableInput', 'multicolumn' => 'thinkMulticolumn');
        if(!isset($wgMap[$questionType])) return array();

        return createWg($wgMap[$questionType], array(set::step($step), set::questionType($questionType), set::mode('detail'), set::isResult(true)));
    }

    protected function buildOptionsContent(object $step, int $blockID): array
    {
        global $lang;

        if(isset($step->options->enableOther) && $step->options->enableOther == 'on') array_push($step->options->fields, 'other');
        $unselectedOptions = array_unique(array_diff($step->options->fields, $step->answer->result));
        $showOptions       = !empty($step->link['selectedBlock']) && $step->link['selectedBlock'] == $blockID ? $step->answer->result :  $unselectedOptions;

        $content = array();
        foreach($showOptions as $option)
        {
            if($option == 'other') $option = $step->answer->other ? $step->answer->other : $lang->other;
            if(!empty($option)) $content[] = div(setClass('mt-1 border p-1.5 break-all'), $option);
        }

        return empty($content) ? array() : array
        (
            div(setClass('text-lg mb-0.5'), $lang->thinkstep->label->option),
            $content
        );
    }

    protected function buildMulticolumnContent(object $step): array
    {
        global $lang;

        $title  = '';
        $colKey = $step->link['column'][0];
        if(isset($step->options->fields[$colKey - 1])) $title = $step->options->fields[$colKey - 1];

        $result = array();
        foreach($step->answer->result as $col => $answer)
        {
            $answerKey = 'col' . $colKey;
            if($col == $answerKey) $result = $answer;
        }

        $content = array();
        foreach($result as $item)
        {
            if(!empty($item)) $content[] = div(setClass('mt-1 border p-1.5 break-all'), $item);
        }

        return empty($content) ? array() : array
        (
            div(setClass('text-lg mb-0.5'), $lang->thinkstep->label->columnTitle . ': ' . $title),
            $content
        );
    }

    protected function buildResultCard(array $steps, int $key, bool $isPosition = false): array
    {
        $questionList = array();
        return $questionList;
    }
}
