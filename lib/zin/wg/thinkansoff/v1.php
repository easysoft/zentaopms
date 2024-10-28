<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class thinkAnsoff extends thinkModel
{
    protected function buildAreaCard(int $order): array
    {
        $blocks = $this->prop('blocks');
        $area   = array();
        foreach($blocks as $block)
        {
            if($order == $block->id && !empty($block->steps)) $area[] = $this->buildResultCard($block->steps, $block->id + 1);
        }
        return $area;
    }

    protected function buildItem(int $order, string|object $block): node
    {
        global $app, $lang, $config;
        $app->loadLang('thinkwizard');
        $app->loadConfig('thinkbackground');

        $mode       = $this->prop('mode');
        $blockStyle = $mode == 'preview' ? array('min-height' => '200px', 'width' => '50%') : array('min-height' => '200px', 'width' => '1078px');
        $blockColor = $config->thinkbackground->blockColor[$order];
        $blockName  = is_string($block) ? $block : $block->text;

        return div
        (
            setClass('relative col justify-between py-2 px-2.5 bg-canvas model-block', "bg-$blockColor-100", "block-$order"),
            setStyle($blockStyle),
            div(setClass('h-full flex flex-wrap gap-2.5'), $mode == 'view' ? $this->buildAreaCard($order) : null),
            div(setClass('item-step-title text-center', "text-$blockColor"), $blockName)
        );
    }

    protected function buildRow(int $key, bool $showTitle = false): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        list($blocks, $mode) = $this->prop(array('blocks', 'mode'));
        $titleKey   = $key == 0 ? 2 : 3;
        $rowContent = div
        (
            setClass('col items-center mt-4'),
            div
            (
                setClass('w-full flex items-stretch gap-4'),
                div
                (
                    setClass('flex items-center justify-center text-gray-400 font-medium item-step-title item-vertical-title'),
                    setStyle(array('writing-mode' => 'vertical-rl')),
                    $lang->thinkwizard->ansoff->titles[$titleKey]
                ),
                $this->buildItem($key, $blocks[$key]),
                $this->buildItem($key + 1, $blocks[$key + 1])
            )
        );
        $paddingLeft = $mode == 'preview' ? '36px' : '60px';

        return $showTitle ? div
        (
            div
            (
                setClass('w-full flex mb-4 gap-4'),
                setStyle(array('padding-left' => $paddingLeft)),
                div(setClass('flex-1 flex items-center justify-center text-gray-400 font-medium item-step-title'), $lang->thinkwizard->ansoff->titles[0]),
                div(setClass('flex-1 flex items-center justify-center text-gray-400 font-medium item-step-title'), $lang->thinkwizard->ansoff->titles[1]),
            ),
            $rowContent
        ) : $rowContent;
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block)
        {
            if($key % 2 == 0) $modelItems[] = $this->buildRow($key, $key == 0);
        }
        return $modelItems;
    }

    protected function build(): node
    {
        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'min-width' => '2400px'));

        return div
        (
            setClass('model-ansoff my-1'),
            $style,
            $this->buildBody()
        );
    }
}
