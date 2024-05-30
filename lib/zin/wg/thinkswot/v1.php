<?php
declare(strict_types=1);
namespace zin;

class thinkSwot extends wg
{
    protected static array $defineProps = array(
        'mode?: string',  // 模型展示模式 preview|view
        'wizard: object', // 模型数据
        'blocks: array',  // 模型节点
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function buildItem(int $order, $step): wg|node
    {
        list($wizard, $mode, $blocks) = $this->prop(array('wizard', 'mode', 'blocks'));
        return div
        (
            setClass('relative p-1 bg-canvas border border-gray-200 item-model', "model-$order"),
            setStyle(array('width' => '50%', 'height' => '127px')),
            div
            (
                setClass('h-full'),
                div(setClass('item-step-title text-center text-sm'), $blocks[$step] ?? ''),
                div(setClass('item-step-answer h-5/6'))
            )
        );
    }

    protected function buildBody(): array
    {
        $blocks     = $this->prop('blocks');
        $blocks     = array_keys($blocks);
        $modelItems = array();
        for($i = 0; $i < 4; $i++)
        {
            $modelItems[] = $this->buildItem($i, $blocks[$i] ?? '');
        }
        return $modelItems;
    }

    protected function build(): array
    {
        global $lang;
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
            array_unshift($model, div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[1]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[2])));
            $model[] = div(setClass('flex justify-between text-gray-400'), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[3]), span($lang->thinkwizard->block . $lang->thinkwizard->blockList[4]));
        }
        return $model;
    }
}
