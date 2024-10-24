<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class thinkAnsoff extends thinkModel
{
    protected function buildItem(int $order, string $block): node
    {
        global $app, $lang, $config;
        $app->loadLang('thinkwizard');
        $app->loadConfig('thinkbackground');

        $mode         = $this->prop('mode');
        $blockStyle   = $mode == 'preview' ? array('min-height' => '200px', 'width' => '50%') : array('min-height' => '200px', 'width' => '1078px');
        $blockColor   = $config->thinkbackground->blockColor[$order];

        return div
        (
            setClass('relative col justify-between py-2 px-2.5 bg-canvas model-block', "bg-$blockColor-100", "block-$order"),
            setStyle($blockStyle),
            div(setClass('h-full')),
            div(setClass('item-step-title text-center', "text-$blockColor"), $lang->thinkwizard->ansoff->blocks[$order])
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
            setClass('col items-center mt-1.5'),
            div
            (
                setClass('w-full flex items-stretch gap-1.5'),
                div
                (
                    setClass('pr-2.5 flex items-center justify-center text-gray-400 font-medium'),
                    setStyle(array('writing-mode' => 'vertical-rl')),
                    $lang->thinkwizard->ansoff->titles[$titleKey]
                ),
                $this->buildItem($key, $blocks[$key]),
                $this->buildItem($key + 1, $blocks[$key + 1])
            )
        );

        return $showTitle ? div
        (
            div
            (
                setClass('w-full flex mb-4'),
                setStyle(array('padding-left' => '46px')),
                div(setClass('flex-1 flex items-center justify-center text-gray-400 font-medium'), $lang->thinkwizard->ansoff->titles[0]),
                div(setClass('flex-1 flex items-center justify-center text-gray-400 font-medium'), $lang->thinkwizard->ansoff->titles[1]),
            ),
            $rowContent
        ) : $rowContent;
    }
}
