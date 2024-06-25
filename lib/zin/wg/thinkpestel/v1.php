<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师PESTEL模型部件类。
 * thinmory PESTEL model widget class.
 */
class thinkPestel extends thinkModel
{
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildItem(object $block): array
    {
        $cards = array();
        foreach($block->steps as &$step) $cards[] = div(setClass('w-full bg-canvas p-2 shadow relative'), $this->buildQuestionItem($step));
        return $cards;
    }

    protected function buildBody(): array
    {
        global $lang, $config;

        list($blocks, $mode) = $this->prop(array('blocks', 'mode'));
        $modelItems   = array();
        $defaultTitle = $mode === 'preview' ? $lang->thinkwizard->unAssociated : '';

        foreach($blocks as $blockIndex => $block)
        {
            $blockColor = $config->thinkbackground->blockColor[$blockIndex];
            $modelItems[] = div
            (
                setClass('relative w-1/' . count($blocks), 'block-' . $blockIndex),
                setStyle(array('min-height' => '256px')),
                $mode === 'preview' ? div(setClass('w-full text-center text-sm leading-tight text-gray-400'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
                div
                (
                    setClass('h-full mt-1 mx-px model-block bg-' . $blockColor . '-100'),
                    div
                    (
                        setClass('h-16 px-2 py-3 w-full relative z-10 flex justify-center', 'text-' . $blockColor),
                        span
                        (
                            setClass('item-step-title overflow-y-hidden'),
                            setStyle(array('max-height' => '40px')),
                            set::title($block->text ? $block->text : null),
                            $block->text ? $block->text : $defaultTitle
                        )
                    ),
                    isset($block->steps) ? div(setClass('py-2 px-5 relative z-10 col gap-5'), $this->buildItem($block)) : null
                )
            );
        }
        return $modelItems;
    }

    protected function build()
    {
        return div
        (
            setClass('flex model-pestel'),
            $this->buildBody()
        );
    }
}
