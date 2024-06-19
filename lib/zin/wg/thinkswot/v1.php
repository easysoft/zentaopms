<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class thinkSwot extends thinkModel
{
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildQuestion(array $steps): array
    {
        $questionList = array();
        foreach($steps as &$step) $questionList[] = div(setClass('w-64 bg-canvas p-2 shadow'), $this->buildQuestionItem($step));
        return $questionList;
    }

    protected function buildItem(int $order, object $block): node|array
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode         = $this->prop('mode');
        $defaultTitle = $mode == 'preview' ? $lang->thinkwizard->unAssociated : '';
        $blockTitle   = $block->text ?: $defaultTitle;

        return div
        (
            setClass('relative p-1 bg-canvas border border-canvas border-2 model-block', "block-$order"),
            setStyle(array('width' => '50%', 'min-height' => '294px')),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-clip'), set::title($blockTitle), $blockTitle),
                !isset($block->steps) ? null : div(setClass('px-4 py-3 flex flex-wrap gap-5 relative z-10'), $this->buildQuestion($block->steps))
            )
        );
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block) $modelItems[] = $this->buildItem($key, $block);
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
                setStyle(array('min-height' => '254px', 'min-width' => '1160px')),
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
