<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class think3c extends thinkModel
{
    protected static array $defineProps = array
    (
        'key?: string="view"',
        'disabled?=false: bool',
    );
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function buildAreaCard(): array
    {
        global $app;

        $blocks = $this->prop('blocks');
        $area   = array();
        foreach($blocks as $block)
        {
            if(!empty($block->steps)) $area[] = $this->buildResultCard($block->steps, $block->id + 1, true);
        }
        return $area;
    }

    protected function buildBody(): node
    {
        global $lang, $app;
        $app->loadLang('thinkrun');

        list($blocks, $mode, $disabled, $key) = $this->prop(array('blocks', 'mode', 'disabled', 'key'));
        jsVar('blockName', $lang->thinkwizard->placeholder->blockName);
        jsVar('model3cKey', $key);
        jsVar('loadGraphTip', $lang->thinkrun->tips->loadGraph);

        return div
        (
            setData(array('clientLang' => $app->getClientLang(), 'model' => '3c', 'mode' => $mode, 'blocks' => $blocks, 'disabled' => $disabled)),
            setClass('model-canvas relative', "model-canvas-$key", $mode == 'view' ? '' : 'flex justify-center'),
            h::canvas(setID('canvas_' . $key)),
            on::blur('.model-canvas input')
            ->const('blockName', $lang->thinkwizard->block)
            ->const('blocksData', $blocks)
            ->const('repeatTips', $lang->thinkwizard->error->blockRepeat)
            ->do(
                'const $tatget = $(this);',
                'const index = $tatget.data("index");',
                'const block = $tatget.data("block");',
                'const value = $tatget.val() || block;',
                'const $blockTitle = $(`.block-title-${index}`);',
                'const currentValue = blocksData[index];',
                'const values = [];',
                'const inputs = $(`input[name="blocks[]"]`);',
                'inputs.each((index, ele) => {values.push($(ele).val());});',
                'if(value != currentValue && new Set(values).size != values.length)
                {
                    return zui.Modal.alert({message: repeatTips, icon: "icon-exclamation-sign", iconClass: "warning-pale rounded-full icon-2x"}).then(() => {
                        $tatget.val(currentValue);
                        $tatget.attr("title", currentValue);
                        if($blockTitle.length)
                        {
                            $blockTitle.text(currentValue);
                            $blockTitle.closest(".block-title").attr("title", currentValue + blockName);
                        }
                    });
                }',
                '$tatget.attr("title", value);',
                '$tatget.val(value);',
                'if($blockTitle.length) {$blockTitle.text(value); $blockTitle.closest(".block-title").attr("title", value + blockName);}'
            ),
            $mode == 'view' ? $this->buildAreaCard() : null
        );
    }

    protected function build(): node
    {
        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'width' => '2400px'));

        return div
        (
            setClass('model-3c my-1 flex col flex-wrap justify-between'),
            $style,
            $this->buildBody(),
            on::init()->call('initThinkCanvas')
        );
    }
}
