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

    protected function buildBody(): node
    {
        global $lang, $app;

        list($blocks, $mode, $disabled, $key) = $this->prop(array('blocks', 'mode', 'disabled', 'key'));
        jsVar('blockName', $lang->thinkwizard->placeholder->blockName);
        jsVar('model3cKey', $key);

        return div
        (
            setData(array('clientLang' => $app->getClientLang(), 'model' => '3c', 'mode' => $mode, 'blocks' => $blocks, 'disabled' => $disabled)),
            setClass('model-canvas relative flex justify-center', "model-canvas-$key"),
            h::canvas(setID('canvas_' . $key)),
            on::blur('.model-canvas input')
            ->const('blockName', $lang->thinkwizard->block)
            ->do(
                'const $tatget = $(this);',
                'const index = $tatget.data("index");',
                'const block = $tatget.data("block");',
                'const value = $tatget.val() || block;',
                'const $blockTitle = $(`.block-title-${index}`);',
                '$tatget.attr("title", value);',
                '$tatget.val(value);',
                'if($blockTitle.length) {$blockTitle.text(value); $blockTitle.closest(".block-title").attr("title", value + blockName);};'
            )
        );
    }

    protected function build(): node
    {
        $mode  = $this->prop('mode');
        $style = $mode == 'preview' ? setStyle(array('min-height' => '254px')) : setStyle(array('min-height' => '254px', 'width' => '2156px'));

        return div
        (
            setClass('model-3c my-1 flex col flex-wrap justify-between'),
            $style,
            $this->buildBody(),
            on::init()->call('initThinkCanvas')
        );
    }
}
