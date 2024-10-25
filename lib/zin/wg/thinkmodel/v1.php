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
    }
}
