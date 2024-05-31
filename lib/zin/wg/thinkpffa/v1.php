<?php
declare(strict_types=1);
namespace zin;

/**
 * 思引波特五力模型部件类。
 * thinmory porter's five forces model widget class.
 */
class thinkPffa extends wg
{
    protected static array $defineProps = array(
        'item: object'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildCards($cardColor, $questions, $blockIndex)
    {
        global $lang;
        $cards = array();
        foreach($questions as $item)
        {
            $cards[] = div(setClass('w-8 h-8 bg-opacity-20 mt-1 mr-2', 'bg-' . $cardColor));
        }
        return div
        (
            setClass('bg-white w-full px-2 py-2.5 border border-gray-200 h-28 overflow-auto'),
            span(setClass('text-sm', 'text-' . $cardColor), $lang->thinkwizard->unAssociated),
            div
            (
                setClass('flex flex-wrap'),
                $cards
            ),
            div(setClass('text-center text-sm leading-tight text-gray-400 mt-1'), $lang->thinkwizard->pffaGroundText[$blockIndex])
        );
    }

    protected function buildLeftBlock()
    {
        global $lang;
        $defaultQuestions = array_pad(array(), 2, null);
        $blockIndex       = 2;

        return div
        (
            setClass('pr-3.5'),
            span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]),
            div
            (
                setClass('flex items-center mt-1'),
                $this->buildCards('success', $defaultQuestions, $blockIndex),
                div(setClass('triangle triangle-right'))
            )
        );
    }

    protected function buildTopBlock()
    {
        global $lang;
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 1;

        return div
        (
            span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]),
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                $this->buildCards('blue', $defaultQuestions, $blockIndex),
                div(setClass('triangle triangle-down'))
            )
        );
    }

    protected function buildCenterBlock()
    {
        global $lang;
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 5;

        return div
        (
            span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]),
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                $this->buildCards('warning', $defaultQuestions, $blockIndex),
            )
        );
    }

    protected function buildBottomBlock()
    {
        global $lang;
        $defaultQuestions = array_pad(array(), 4, null);
        $blockIndex       = 4;

        return div
        (
            setClass('relative pt-3.5'),
            span(setClass('absolute text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]),
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                div(setClass('triangle triangle-up')),
                $this->buildCards('important', $defaultQuestions, $blockIndex)
            )
        );
    }

    protected function buildRightBlock()
    {
        global $lang;
        $defaultQuestions = array_pad(array(), 2, null);
        $blockIndex       = 3;

        return div
        (
            setClass('pl-3.5'),
            span(setClass('text-gray-400 text-sm ml-4'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]),
            div
            (
                setClass('flex items-center mt-1'),
                div(setClass('triangle triangle-left')),
                $this->buildCards('special', $defaultQuestions, $blockIndex)
            )
        );
    }
    protected function build()
    {
        global $lang;
        return div
        (
            div
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
            )
        );
    }
}
