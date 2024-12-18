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

        if($mode == 'preview')
        {
            $config    = $wizard->config;
            $xAxisName = $config['configureDimension']['xAxisName'];
            $yAxisName = $config['configureDimension']['yAxisName'];
            return div
            (
                setClass('col items-center'),
                div
                (
                    setClass('flex gap-1 mt-4'),
                    div(setClass('m-auto text-gray-600'), setStyle(array('writing-mode' => 'vertical-rl')), $lang->thinkwizard->dimension->yAxisNameList[$yAxisName]),
                    img(set::src("data/thinmory/wizardsetting/bcg/blockGroup$previewKey.svg")),
                ),
                div(setClass('pl-6 text-center text-gray-600'), $lang->thinkwizard->dimension->xAxisNameList[$xAxisName]),
            );
        }
        return div();
    }

    protected function build(): node
    {
        return div(setClass('model-appeals my-1 flex col flex-wrap justify-between'), $this->buildBody());
    }
}
