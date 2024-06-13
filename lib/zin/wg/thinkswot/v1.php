<?php
declare(strict_types=1);
namespace zin;

class thinkSwot extends wg
{
    protected static array $defineProps = array(
        'mode?: string', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array', // 模型节点
        'steps?: array', // 所有问题步骤数据
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildQuestionItem(object $step): wg|array
    {
        if($step->options->questionType === 'input')      return thinkInput(set::step($step), set::questionType('input'), set::mode('detail'));
        if($step->options->questionType === 'radio')      return thinkRadio(set::step($step), set::questionType('radio'), set::mode('detail'));
        if($step->options->questionType === 'checkbox')   return thinkCheckbox(set::step($step), set::questionType('checkbox'), set::mode('detail'));
        if($step->options->questionType === 'tableInput') return thinkTableInput(set::step($step), set::questionType('tableInput'), set::mode('detail'));
        return array();
    }

    protected function buildQuestion(int $stepID, int $originalID): array
    {
        $steps      = $this->prop('steps');
        $blockSteps = array();
        foreach($steps as $stepItem)
        {
            $path = explode(',', trim($stepItem->path, ','));
            if(in_array($stepID, $path) || in_array($originalID, $path)) $blockSteps[] = $stepItem;
        }

        $questionList = array();
        foreach($blockSteps as &$step)
        {
            if(is_string($step->options)) $step->options = json_decode($step->options);
            if(is_string($step->answer))  $step->answer  = json_decode($step->answer);
            $questionList[] = div(setClass('w-80 bg-canvas p-2 shadow'), $this->buildQuestionItem($step));
        }
        return $questionList;
    }

    protected function buildItem(int $order, string $blockTitle, int $blockID): node|array
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($mode, $steps) = $this->prop(array('mode', 'steps'));
        $defaultTitle = $mode == 'preview' ? $lang->thinkwizard->unAssociated : '';
        $blockTitle   = $blockTitle ?: $defaultTitle;
        $urlParams    = !empty($app->params) ? $app->params : array();

        $stepID = 0;
        if(isset($urlParams['runID']))
        {
            $stepID = $app->control->loadModel('thinkstep')->getRunStepIDByID($blockID, $urlParams['runID']);
            if(!$stepID) return array();
        }

        return div
        (
            setClass('relative p-1 bg-canvas border border-canvas border-2 model-block', "block-$order"),
            setStyle(array('width' => '50%', 'min-height' => '294px')),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-clip'), set::title($blockTitle), $blockTitle),
                empty($steps) ? null : div(setClass('px-5 py-3 flex flex-wrap gap-5 relative z-10'), $this->buildQuestion($stepID, $blockID))
            )
        );
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block) $modelItems[] = $this->buildItem($key, $block->text ?? '', (int)$block->id);
        return $modelItems;
    }

    protected function build(): array
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode  = $this->prop('mode');
        $model = array(
            div
            (
                setClass('model-swot my-1 flex flex-wrap justify-between'),
                setStyle(array('min-height' => '254px')),
                $this->buildBody()
            )
        );
        if($mode == 'preview')
        {
            array_unshift($model, div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[0]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[1])));
            $model[] = div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[2]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[3]));
        }
        return $model;
    }
}
