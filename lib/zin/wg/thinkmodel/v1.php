<?php
declare(strict_types=1);
namespace zin;

class thinkModel extends wg
{
    protected static array $defineProps = array(
        'mode?: string',  // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array',  // 模型节点
        'wizard?: object' // 模型数据
    );

    protected function buildQuestionItem(object $step): wg|array
    {
        $wizard       = $this->prop('wizard');
        $questionType = $step->options->questionType;
        $wgMap        = array('input' => 'thinkInput', 'radio' => 'thinkRadio', 'checkbox' => 'thinkCheckbox', 'tableInput' => 'thinkTableInput', 'multicolumn' => 'thinkMulticolumn');
        if(!isset($wgMap[$questionType])) return array();

        return createWg($wgMap[$questionType], array(set::step($step), set::questionType($questionType), set::mode('detail'), set::isResult(true), set::wizard($wizard)));
    }

    protected function buildOptionsContent(object $step, int $blockID): array
    {
        global $app, $lang;
        $app->loadLang('thinkstep');

        if(isset($step->options->enableOther) && $step->options->enableOther == 'on') array_push($step->options->fields, 'other');
        if(empty($step->answer->result)) $step->answer->result = array();
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
        global $app, $lang;
        $app->loadLang('thinkstep');

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

    protected function existEmptyCol(object $data): bool
    {
        foreach($data as $value)
        {
            if(!empty($value)) return false;
        }
        return true;
    }

    protected function checkEmptyOfMulticolumn(object $data): bool
    {
        foreach($data as $item)
        {
            if(!$this->existEmptyCol($item)) return false;
        }
        return true;
    }

    protected function buildResultCard(array $steps, int $key, bool $isPosition = false): array
    {
        $questionList = array();
        foreach($steps as $step)
        {
            if(is_string($step->link))    $step->link = json_decode($step->link, true);
            if(is_string($step->answer))  $step->answer = json_decode($step->answer);
            if(is_string($step->options)) $step->options = json_decode($step->options);

            $resultCard = array();
            $className  = '';
            if($step->link['showMethod'] == 2)
            {
                $className  = "card-{$step->options->questionType}";
                if($step->options->questionType == 'multicolumn' && $this->checkEmptyOfMulticolumn($step->answer->result))
                {
                    $resultCard = array();
                }
                else
                {
                    $resultCard = empty($step->answer->result) ? array() : $this->buildQuestionItem($step);
                }
            }
            elseif($step->link['showMethod'] == '1')
            {
                $className  = 'card-options';
                $resultCard = $this->buildMulticolumnContent($step);
            }
            else
            {
                $className  = 'card-options';
                $resultCard = $this->buildOptionsContent($step, $key);
            }

            $blockIndex = $key - 1;
            if($isPosition) $className .= ' overflow-y-auto hidden absolute ' . "in_area-{$blockIndex}";
            if(!empty($resultCard)) $questionList[] = div(setClass('w-64 bg-canvas scrollbar-thin p-2 shadow card ', $className), $resultCard);
        }
        return $questionList;
    }
}
