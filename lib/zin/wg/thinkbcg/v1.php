<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师波士顿矩阵模型部件类。
 * thinmory bcg model widget class.
 */
class thinkBcg extends thinkModel
{
    protected function buildBody(): node
    {
        global $app, $lang;
        $app->loadLang('thinkwizard');
        list($mode, $wizard, $previewKey) = $this->prop(array('mode', 'wizard', 'previewKey'));

        return div();
    }

    protected function build(): node
    {
        return div(setClass('model-appeals my-1 flex col flex-wrap justify-between'), $this->buildBody());
    }
}
