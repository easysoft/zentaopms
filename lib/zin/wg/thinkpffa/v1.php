<?php
declare(strict_types=1);
namespace zin;

use block;

/**
 * 思引波特五力模型部件类。
 * thinmory porter's five forces model widget class.
 */
class thinkPffa extends wg
{
    protected static array $defineProps = array(
        'mode?: string', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array', // 模型节点
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildCards($questions, $blockIndex)
    {
        global $lang;
        $blocks       = $this->prop('blocks');
        $mode         = $this->prop('mode');
        $block        = array_slice($blocks, $blockIndex, 1);
        $cards        = array();
        $blockColor   = $lang->thinkbackground->blockColor[$blockIndex];
        $defaultTitle = $mode === 'preview' ? $lang->thinkwizard->unAssociated : '';

        foreach($questions as $item)
        {
            $cards[] = div(setClass('w-8 h-8 bg-opacity-20 mt-1 mr-2', 'bg-' . $blockColor));
        }
        return div
        (
            setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
            div
            (
                setClass('text-sm item-step-title text-clip', 'text-' . $blockColor),
                set::title(!empty($block[0]) ? $block[0] : null),
                !empty($block[0]) ? $block[0] : $defaultTitle
            ),
            div(setClass('flex flex-wrap'), $cards),
            div(setClass('text-center text-sm leading-tight text-gray-400 mt-1'), $lang->thinkwizard->pffaGroundText[$blockIndex])
        );
    }

    protected function buildLeftBlock()
    {
        global $lang;
        $mode             = $this->prop('mode');
        $defaultQuestions = array_pad(array(), 2, null);
        $blockIndex       = 1;

        return div
        (
            setClass('pr-3.5 block-' . $blockIndex),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex items-center mt-1'),
                $this->buildCards($defaultQuestions, $blockIndex),
                div(setClass('triangle triangle-right'))
            )
        );
    }

    protected function buildTopBlock()
    {
        global $lang;
        $mode             = $this->prop('mode');
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 0;

        return div
        (
            setClass('block-' . $blockIndex),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                $this->buildCards($defaultQuestions, $blockIndex),
                div(setClass('triangle triangle-down'))
            )
        );
    }

    protected function buildCenterBlock()
    {
        global $lang;
        $mode             = $this->prop('mode');
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 4;

        return div
        (
            setClass('block-' . $blockIndex),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                $this->buildCards($defaultQuestions, $blockIndex),
            )
        );
    }

    protected function buildBottomBlock()
    {
        global $lang;
        $mode             = $this->prop('mode');
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 3;

        return div
        (
            setClass('relative pt-3.5 block-' . $blockIndex),
            $mode === 'preview' ? span(setClass('absolute text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                div(setClass('triangle triangle-up')),
                $this->buildCards($defaultQuestions, $blockIndex)
            )
        );
    }

    protected function buildRightBlock()
    {
        global $lang;
        $mode             = $this->prop('mode');
        $defaultQuestions = array_pad(array(), 2, null);
        $blockIndex       = 2;

        return div
        (
            setClass('pl-3.5 block-' . $blockIndex),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm ml-4'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex items-center mt-1'),
                div(setClass('triangle triangle-left')),
                $this->buildCards($defaultQuestions, $blockIndex)
            )
        );
    }
    protected function build()
    {
        return div
        (
            setClass('flex items-center'),
            div(setClass('w-2/7'), $this->buildLeftBlock()),
            div(
                setClass('w-3/7'),
                $this->buildTopBlock(),
                $this->buildCenterBlock(),
                $this->buildBottomBlock()
            ),
            div(setClass('w-2/7'), $this->buildRightBlock())
        );
    }
}
