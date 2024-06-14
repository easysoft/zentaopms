<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引波特五力模型部件类。
 * thinmory porter's five forces model widget class.
 */
class thinkPffa extends thinkModel
{
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildQuestion(array $steps): array
    {
        $questionList = array();
        foreach($steps as &$step) $questionList[] = div(setClass('w-60 bg-canvas p-2 shadow'), setStyle(array('width' => '240px')), $this->buildQuestionItem($step));
        return $questionList;
    }

    protected function buildCards($blockIndex)
    {
        global $lang, $config;
        $blocks       = $this->prop('blocks');
        $mode         = $this->prop('mode');
        $blockColor   = $config->thinkbackground->blockColor[$blockIndex];
        $defaultTitle = $mode === 'preview' ? $lang->thinkwizard->unAssociated : '';

        return div
        (
            setClass('w-full h-full px-2 py-2.5 border overflow-auto col justify-between gap-4 bg-opacity-20 bg-' . $blockColor, 'border-' . $blockColor),
            div
            (
                setClass('text-sm item-step-title text-clip', 'text-' . $blockColor),
                set::title(!empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : null),
                !empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : $defaultTitle
            ),
            div(setClass('flex flex-wrap gap-1.5'), !isset($blocks[$blockIndex]->steps) ? null : $this->buildQuestion($blocks[$blockIndex]->steps)),
            div(setClass('text-left text-sm leading-tight text-canvas'), $lang->thinkwizard->pffaGroundText[$blockIndex])
        );
    }

    protected function buildLeftBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 1;

        return div
        (
            setClass('w-1/3 col justify-stretch pr-3.5 block-' . $blockIndex),
            setStyle(array('min-width' => '504px')),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('h-full flex items-center mt-1'),
                $this->buildCards($blockIndex),
                div(setClass('triangle triangle-right'))
            )
        );
    }

    protected function buildTopBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 0;

        return div
        (
            setClass('w-1/3 block-' . $blockIndex),
            setStyle(array('min-width' => '504px')),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                $this->buildCards($blockIndex),
                div(setClass('triangle triangle-down'))
            )
        );
    }

    protected function buildCenterBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 4;

        return div
        (
            setClass('w-1/3 col justify-stretch block-' . $blockIndex),
            setStyle(array('min-width' => '504px')),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('h-full flex justify-center flex-wrap mt-1'),
                $this->buildCards($blockIndex)
            )
        );
    }

    protected function buildBottomBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 3;

        return div
        (
            setClass('w-1/3 relative pt-3.5 block-' . $blockIndex),
            setStyle(array('min-width' => '504px')),
            $mode === 'preview' ? span(setClass('absolute text-gray-400 text-sm'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('flex justify-center flex-wrap mt-1'),
                div(setClass('triangle triangle-up')),
                $this->buildCards($blockIndex)
            )
        );
    }

    protected function buildRightBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 2;

        return div
        (
            setClass('w-1/3 col justify-stretch pl-3.5 block-' . $blockIndex),
            setStyle(array('min-width' => '504px')),
            $mode === 'preview' ? span(setClass('text-gray-400 text-sm ml-4'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
            div
            (
                setClass('h-full flex items-center mt-1'),
                div(setClass('triangle triangle-left')),
                $this->buildCards($blockIndex)
            )
        );
    }
    protected function build()
    {
        return div
        (
            setClass('col justify-center items-center'),
            $this->buildTopBlock(),
            div
            (
                setClass('w-full h-full flex items-stretch justify-center pffa-middle'),
                $this->buildLeftBlock(),
                $this->buildCenterBlock(),
                $this->buildRightBlock(),
            ),
            $this->buildBottomBlock()
        );
    }
}
