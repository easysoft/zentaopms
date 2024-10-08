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
        foreach($steps as $step) $questionList[] = div(setClass('w-64 bg-canvas p-2 shadow', "card-{$step->options->questionType}"), $this->buildQuestionItem($step));
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
                setClass('block-content col items-center gap-2'),
                div
                (
                    setClass('item-step-title text-clip', 'text-' . $blockColor),
                    set::title(!empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : null),
                    !empty($blocks[$blockIndex]->text) ? $blocks[$blockIndex]->text : $defaultTitle
                ),
                div(setClass('w-full flex flex-wrap gap-2.5'), !isset($blocks[$blockIndex]->steps) ? null : $this->buildQuestion($blocks[$blockIndex]->steps)),
            ),
            div(setClass('item-desc text-center leading-tight text-canvas', $descFontSize), $lang->thinkwizard->pffaGroundText[$blockIndex])
        );
    }

    protected function buildBlock(int $blockIndex, string $blockClass, string $contentClass, string $direction = '')
    {
        global $lang;

        $mode       = $this->prop('mode');
        $blockWidth = in_array($blockIndex, array(1, 2)) ? '1108px' : '1078px';
        $blockStyle = $mode === 'preview' ? null : setStyle(array('width' => $blockWidth));
        $titleClass = 'text-gray-400 text-sm';
        if($blockIndex == 3) $titleClass .= ' absolute';
        if($blockIndex == 2) $titleClass .= ' ml-4';
        $blockTitle    = $mode === 'preview' ? span(setClass($titleClass), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null;
        $triangle      = $direction ? div(setClass('triangle triangle-' . $direction)) : null;
        $contentClass .= ' mt-1 flex';

        return div
        (
            setClass($blockClass, $mode === 'preview' ? 'w-1/3' : ''),
            $blockStyle,
            $blockTitle,
            div
            (
                setClass($contentClass),
                in_array($blockIndex, array(2, 3)) ? $triangle : null,
                $this->buildCards($blockIndex),
                in_array($blockIndex, array(2, 3)) ? null : $triangle
            )
        );
    }

    protected function build()
    {
        return div
        (
            setClass('col justify-center items-center gap-3.5 model-pffa'),
            $this->buildBlock(0, 'block-0', 'justify-center flex-wrap', 'down'),
            div
            (
                setClass('w-full h-full flex items-stretch justify-center pffa-middle'),
                $this->buildBlock(1, 'col justify-stretch pr-3.5 block-1', 'h-full items-center', 'right'),
                $this->buildBlock(4, 'col justify-stretch block-4', 'h-full justify-center flex-wrap'),
                $this->buildBlock(2, 'col justify-stretch pl-3.5 block-2', 'h-full items-center', 'left')
            ),
            $this->buildBlock(3, 'relative block-3', 'justify-center flex-wrap', 'up')
        );
    }
}
