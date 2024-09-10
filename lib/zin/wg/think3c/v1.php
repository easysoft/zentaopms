<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

class think3c extends thinkModel
{
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
        global $lang;

        jsVar('blockName', $lang->thinkwizard->placeholder->blockName);

        return div
        (
            setData('model', '3c'),
            setClass('model-canvas relative flex justify-center'),
            h::canvas(setID('canvas')),
            on::blur('.model-canvas input')->do('$(this).attr("title", $(this).val());')
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
