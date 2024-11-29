<?php
declare(strict_types=1);
namespace zin;

requireWg('thinkModel');

/**
 * 思引师$APPEALS模型部件类。
 * thinmory $APPEALS model widget class.
 */
class thinkAppeals extends thinkModel
{
    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function getIndicator(): array
    {
        $blocks    = $this->prop('blocks');
        $indicator = array();

        foreach($blocks['steps'] as $key => $step)
        {
            $indicator[] = array('name' => $step->title, 'color'=> '#64758B', 'axisLabel' => $key == 0 ? array('show' => true) : null);
        }
        return $indicator;
    }

    protected function buildEcharts(): node
    {
        $blocks = $this->prop('blocks');
        return echarts
        (
            set::animationDuration(0),
            set::width('1000px'),
            set::height('1000px'),
            set::color(array('#29AA93', '#FF9F46')),
            set::legend(array(array(
                'data'       => $blocks['legend'],
                'icon'       => 'circle',
                'itemGap'    => 90,
                'itemWidth'  => 14,
                'itemHeight' => 14,
                'bottom'     => 20,
                'textStyle'  => array('color' => '#64758B', 'fontSize' => 20, 'padding' => array(0, 0, 0, 16))
            ))),
            set::radar(array(
                'nameGap'   => 32,
                'splitArea' => array('areaStyle' => array('color' => array('#EAF5FF'))),
                'splitLine' => array('lineStyle' => array('color' => '#CFE7FE', 'width' => 3)),
                'axisLine'  => array('lineStyle' => array('color' => '#CFE7FE', 'width' => 3)),
                'axisLabel' => array('color' => '#9EA3B0', 'textStyle' => array('fontSize' => 24)),
                'name'      => array('formatter' => jsRaw('formatIndicatorName'), 'textStyle' => array('fontSize' => 24)),
                'indicator' => $this->getIndicator()
            )),
            set::series
            (
                array(
                    array(
                        'symbolSize' => 10,
                        'type'       => 'radar',
                        'data'       => $blocks['seriesData'],
                        'label'      => array('normal' => array(
                            'show'      => true,
                            'formatter' => jsRaw('formatSeriesLabel'),
                            'textStyle' => array('fontSize' => 24)
                        ))
                    )
                )
            )
        );
    }

    protected function buildBody(): node
    {
        list($mode, $wizard) = $this->prop(array('mode', 'wizard'));

        if($mode == 'preview')
        {
            $count = $wizard->config['configureDimension']['count'];
            return div
            (
                setClass('flex justify-center'),
                img(set::src("data/thinmory/wizardsetting/appeals/dimension$count.svg")),
            );
        }

        return div
        (
            setClass('appleals-chart-content'),
            div(setClass('appleals-chart'), $this->buildEcharts()),
            on::inited()->call('initedAppealsChart')
        );
    }

    protected function build(): node
    {
        return div(setClass('model-appeals my-1 flex col flex-wrap justify-between'), $this->buildBody());
    }
}
