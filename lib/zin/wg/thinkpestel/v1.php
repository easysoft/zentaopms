<?php
declare(strict_types=1);
namespace zin;

/**
 * 思引师PESTEL模型部件类。
 * thinmory PESTEL model widget class.
 */
class thinkPestel extends wg
{
    protected static array $defineProps = array(
        'mode?: string', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array', // 模型节点
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildItem($questions, $blockIndex): array
    {
        global $lang;
        $cards = array();

        foreach($questions as $item)
        {
            $cards[] = div(setClass('h-4 mt-2 w-full bg-opacity-20 rounded-sm bg-' . $lang->thinkbackground->blockColor[$blockIndex]));
        }
        return $cards;
    }

    protected function buildBody(): array
    {
        global $lang;
        $blocks           = $this->prop('blocks');
        $mode             = $this->prop('mode');
        $blockIndex       = 0;
        $modelItems       = array();
        $defaultQuestions = array_pad(array(), 6, null);
        $defaultTitle     = $mode === 'preview' ? $lang->thinkwizard->unAssociated : '';

        foreach($blocks as $block)
        {
            $blockColor = $lang->thinkbackground->blockColor[$blockIndex];
            $modelItems[] = div
            (
                setClass('h-full w-1/' . count($blocks), 'block-' . $blockIndex),
                $mode === 'preview' ? div(setClass('w-full text-center text-sm leading-tight text-gray-400'), $lang->thinkwizard->block . $lang->thinkwizard->blockList[$blockIndex]) : null,
                div
                (
                    setClass('border border-opacity-80 rounded-lg bg-white mt-1 mx-1'),
                    div
                    (
                        setClass('bg-opacity-20 h-16 rounded-lg px-2 py-3 w-full flex items-center justify-center bg-' . $blockColor, 'text-' . $blockColor),
                        span(
                            setClass('item-step-title overflow-y-hidden'),
                            setStyle(array('max-height' => '40px')),
                            set::title($block->text ? $block->text : null),
                             $block->text ? $block->text : $defaultTitle
                        )
                    ),
                    div(setClass('px-2 pb-2 h-52 model-block relative'), $this->buildItem($defaultQuestions, $blockIndex))
                ),
            );
            $blockIndex ++;
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
