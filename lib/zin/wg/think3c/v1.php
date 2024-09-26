<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class think3c extends thinkModel
{
    protected static array $defineProps = array
    (
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

        jsVar('blockName', $lang->thinkwizard->placeholder->blockName);
        list($blocks, $mode, $disabled) = $this->prop(array('blocks', 'mode', 'disabled'));

        return div
        (
            setData('clientLang', $app->getClientLang()),
            setData('model', '3c'),
            setData('mode', $mode),
            setData('blocks', $blocks),
            setData('disabled', $disabled),
            setClass('model-canvas relative flex justify-center'),
            h::canvas(setID('canvas')),
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
