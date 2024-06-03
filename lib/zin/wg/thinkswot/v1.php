<?php
declare(strict_types=1);
namespace zin;

class thinkSwot extends wg
{
    protected static array $defineProps = array(
        'mode?: string', // 模型展示模式。 preview 后台设计预览 | view 前台结果展示
        'blocks: array', // 模型节点
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildItem(int $order, string $blockTitle): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode         = $this->prop('mode');
        $defaultTitle = $mode == 'preview' ? $lang->thinkwizard->unAssociated : '';
        $blockTitle   = $blockTitle ?: $defaultTitle;
        return div
        (
            setClass('relative p-1 bg-canvas border border-gray-200 model-block', "block-$order"),
            setStyle(array('width' => '50%', 'height' => '127px')),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-sm text-clip'), set::title($blockTitle), $blockTitle),
                div(setClass('item-step-answer h-5/6'))
            )
        );
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $modelItems = array();
        foreach($blocks as $key => $block)
        {
            $modelItems[] = $this->buildItem($key, $block->text ?? '');
        }
        return $modelItems;
    }

    protected function build(): array
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');

        $mode  = $this->prop('mode');
        $model = array(
            div
            (
                setClass('model-swot my-1 flex flex-wrap justify-between'),
                setStyle(array('min-height' => '254px')),
                $this->buildBody()
            )
        );
        if($mode == 'preview')
        {
            array_unshift($model, div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[0]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[1])));
            $model[] = div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[2]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[3]));
        }
        return $model;
    }
}
