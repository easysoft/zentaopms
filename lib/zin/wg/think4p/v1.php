<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师4P模型部件类。
 * thinmory 4P model widget class.
 */
class think4p extends thinkModel
{
    protected function buildQuestion(array $steps): array
    {
        $questionList = array();
        foreach($steps as &$step) $questionList[] = div(setClass('w-64 bg-canvas p-2 shadow', "card-{$step->options->questionType}"), $this->buildQuestionItem($step));
        return $questionList;
    }

    protected function buildItem(int $order, object $block): node|array
    {
        global $app, $lang, $config;
        $app->loadLang('thinkwizard');

        $mode         = $this->prop('mode');
        $defaultTitle = $mode == 'preview' ? $lang->thinkwizard->unAssociated : '';
        $blockTitle   = $block->text ?: $defaultTitle;
        $blockStyle   = $mode == 'preview' ? array('min-height' => '200px', 'width' => '50%') : array('min-height' => '200px', 'width' => '1078px');
        $blockColor   = $config->thinkbackground->blockColor[$order];
        $descSize     = $mode === 'preview' ? 'text-sm' : 'text-2xl';

        return div
        (
            setClass('relative col justify-between py-2 px-2.5 bg-canvas border border-canvas border-2 model-block', "bg-$blockColor-100", "block-$order"),
            setStyle($blockStyle),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-clip', "text-$blockColor"), set::title($blockTitle), $blockTitle),
                !isset($block->steps) ? null : div(setClass('py-3 flex flex-wrap gap-2.5 relative z-10'), $this->buildQuestion($block->steps))
            ),
            div(setClass('item-desc text-center leading-tight text-canvas', $descSize), $lang->thinkwizard->blockDescOf4p[$order])
        );
    }

    protected function buildRow(int $key): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($blocks, $mode) = $this->prop(array('blocks', 'mode'));
        return div
        (
            setClass('col items-center'),
            $mode == 'preview' ? div(setClass('w-full flex items-center justify-between mt-1.5 mb-1 text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[$key]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[$key + 1])) : null,
            div
            (
                setClass('w-full flex items-stretch'),
                $this->buildItem($key, $blocks[$key]),
                $this->buildItem($key + 1, $blocks[$key + 1])
            )
        );
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block)
        {
            if($key % 2 == 0) $modelItems[] = $this->buildRow($key);
        }
        return $modelItems;
    }

    protected function build(): node
    {
        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'min-width' => '2156px'));

        return div
        (
            setClass('model-4p my-1 flex col flex-wrap justify-between'),
            $style,
            $this->buildBody()
        );
    }
}
