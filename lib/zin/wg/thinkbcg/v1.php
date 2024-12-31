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
        list($mode, $wizard, $previewKey, $blocks) = $this->prop(array('mode', 'wizard', 'previewKey', 'blocks'));

        if($mode == 'preview')
        {
            $config    = $wizard->config;
            $xAxisName = $config['configureDimension']['xAxisName'] ?? '';
            $yAxisName = $config['configureDimension']['yAxisName'] ?? '';
            return div
            (
                setClass('col items-center pb-8'),
                div
                (
                    setClass('flex gap-1.5 mt-4 relative text-gray-600'),
                    div
                    (
                        setClass('pt-6 pb-3.5 flex justify-between'),
                        setStyle(array('writing-mode' => 'vertical-rl')),
                        span($config['configureDimension']['yAxisOrder'] == 0 ? $lang->thinkwizard->dimension->height : $lang->thinkwizard->dimension->low),
                        span($lang->thinkwizard->dimension->yAxisNameList[$yAxisName] ?? ''),
                        span($config['configureDimension']['yAxisOrder'] == 0 ? $lang->thinkwizard->dimension->low : $lang->thinkwizard->dimension->height)
                    ),
                    img(set::src("data/thinmory/wizardsetting/bcg/blockGroup$previewKey.svg")),
                    div
                    (
                        setClass('w-full flex justify-between absolute left-0'),
                        setStyle(array('padding' => '0 22px 0 40px', 'bottom' => '-26px')),
                        span($config['configureDimension']['xAxisOrder'] == 0 ? $lang->thinkwizard->dimension->low : $lang->thinkwizard->dimension->height),
                        span($lang->thinkwizard->dimension->xAxisNameList[$xAxisName] ?? ''),
                        span($config['configureDimension']['xAxisOrder'] == 0 ? $lang->thinkwizard->dimension->height : $lang->thinkwizard->dimension->low)
                    )
                )
            );
        }

        $xAxis = $blocks['runOptions']['configureDimension']['xAxisName'] ?? '';
        $yAxis = $blocks['runOptions']['configureDimension']['yAxisName'] ?? '';
        return div
        (
            setData(array('blocks' => $blocks)),
            setClass('relative echarts-content text-gray-600'),
            p(setClass('h-full axis-name text-lg absolute top-0 flex justify-center left-2'), setStyle(array('writing-mode' => 'vertical-rl')), $lang->thinkwizard->dimension->yAxisNameList[$yAxis] ?? ''),
            p(setClass('w-full axis-name text-lg text-center absolute left-0 bottom-2'), $lang->thinkwizard->dimension->xAxisNameList[$xAxis] ?? ''),
        );
    }

    protected function build(): node
    {
        return div(setClass('model-appeals my-1 flex col flex-wrap justify-between'), $this->buildBody());
    }
}
