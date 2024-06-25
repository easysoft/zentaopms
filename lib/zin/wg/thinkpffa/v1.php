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
        foreach($steps as $step) $questionList[] = div(setClass('w-64 bg-canvas p-2 shadow'), $this->buildQuestionItem($step));
        return $questionList;
    }

    protected function buildCards($blockIndex)
    {
        global $lang, $config;
        $blocks       = $this->prop('blocks');
        $mode         = $this->prop('mode');
        $blockColor   = $config->thinkbackground->blockColor[$blockIndex];
        $defaultTitle = $mode === 'preview' ? $lang->thinkwizard->unAssociated : '';
        $descFontSize = $mode === 'preview' ? 'text-sm' : 'text-2xl';

        return div
        (
            setClass('w-full h-full p-2.5 outline overflow-auto col justify-between gap-2 bg-' . $blockColor . '-100', $blockColor . '-outline'),
            div
            (
                setClass('col items-center gap-2'),
                div
                (
                    setClass('item-step-title text-clip', 'text-' . $blockColor),
                    set::title(!empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : null),
                    !empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : $defaultTitle
                ),
                div(setClass('flex flex-wrap gap-2.5'), !isset($blocks[$blockIndex]->steps) ? null : $this->buildQuestion($blocks[$blockIndex]->steps)),
            ),
            div(setClass('item-desc text-center leading-tight text-canvas', $descFontSize), $lang->thinkwizard->pffaGroundText[$blockIndex])
        );
    }

    protected function buildLeftBlock()
    {
        global $lang;
        $mode       = $this->prop('mode');
        $blockIndex = 1;
        $blockClass = $mode === 'preview' ? 'w-1/3' : '';

        return div
        (
            setClass('col justify-stretch pr-3.5 block-' . $blockIndex, $blockClass),
            $mode === 'preview' ? null : setStyle(array('width' => '1108px')),
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
        $blockClass = $mode === 'preview' ? 'w-1/3' : '';

        return div
        (
            setClass('block-' . $blockIndex, $blockClass),
            $mode === 'preview' ? null : setStyle(array('width' => '1078px')),
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
        $blockClass = $mode === 'preview' ? 'w-1/3' : '';

        return div
        (
            setClass('col justify-stretch block-' . $blockIndex, $blockClass),
            $mode === 'preview' ? null : setStyle(array('width' => '1078px')),
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
        $blockClass = $mode === 'preview' ? 'w-1/3' : '';

        return div
        (
            setClass('relative block-' . $blockIndex, $blockClass),
            $mode === 'preview' ? null : setStyle(array('width' => '1078px')),
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
        $blockClass = $mode === 'preview' ? 'w-1/3' : '';

        return div
        (
            setClass('col justify-stretch pl-3.5 block-' . $blockIndex, $blockClass),
            $mode === 'preview' ? null : setStyle(array('width' => '1108px')),
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
            setClass('col justify-center items-center gap-3.5 model-pffa'),
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
