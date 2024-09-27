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
            setData(array('clientLang' => $app->getClientLang(), 'model' => '3c', 'mode' => $mode, 'blocks' => $blocks, 'disabled' => $disabled, 'key' => $key)),
            setClass('model-canvas relative flex justify-center', "model-canvas-$key"),
            h::canvas(setID('canvas_' . $key)),
            on::blur('.model-canvas input')->do('
                const index = $(this).data("index");
                const block = $(this).data("block");
                const value = $(this).val() || block;
                $(this).attr("title", value);
                $(this).val(value);
                if($(`.block-title-${index}`).length) $(`.block-title-${index}`).text(value);
            ')
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
