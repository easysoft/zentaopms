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
    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

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
        $blocks       = $this->prop('blocks');
        $color        = array('#29AA93', '#FF9F46');
        $legendConfig = array(
            'data'       => $blocks['legend'],
            'icon'       => 'circle',
            'itemGap'    => 90,
            'itemWidth'  => 14,
            'itemHeight' => 14,
            'bottom'     => 20,
            'textStyle'  => array('color' => '#64758B', 'fontSize' => 20, 'padding' => array(0, 0, 0, 16))
        );

        if(isset($blocks['configureObjects']['enableCompetitor']) && $blocks['configureObjects']['enableCompetitor'] == 0)
        {
            $color        = array('#2294FB', '#22C98D', '#8166EE', '#EC4899', '#FF9F46', '#FBD34D');
            $legendConfig = array_merge($legendConfig, array(
                'itemWidth' => 400,
                'itemGap'   => 20,
                'textStyle' => array(
                    'color'    => '#64758B',
                    'fontSize' => 20,
                    'padding'  => array(0, 0, 0, -180),
                    'rich'     => array('bolder' => array('fontSize' => 20, 'fontWeight' => 'bold'))
                ),
                'formatter' => jsRaw('formatLegend')
            ));
        }

        return echarts
        (
            set::animationDuration(0),
            set::width('1600px'),
            set::height('1100px'),
            set::color($color),
            set::legend(array($legendConfig)),
            set::radar(array(
                'nameGap'   => 32,
                'splitArea' => array('areaStyle' => array('color' => array('#EAF5FF'))),
                'splitLine' => array('lineStyle' => array('color' => '#CFE7FE', 'width' => 3)),
                'axisLine'  => array('lineStyle' => array('color' => '#CFE7FE', 'width' => 3)),
                'axisLabel' => array('color' => '#9EA3B0', 'textStyle' => array('fontSize' => 24)),
                'name'      => array('textStyle' => array('fontSize' => 24)),
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
        global $lang;
        list($mode, $wizard) = $this->prop(array('mode', 'wizard'));
        $wizard->config   = is_string($wizard->config) ? json_decode($wizard->config, true) : (array) $wizard->config;
        $configureObjects = json_decode($wizard->config['configureObjects']);
        $enableCompetitor = !isset($configureObjects->enableCompetitor) || $configureObjects->enableCompetitor == '1';
        $objectName       = $configureObjects->type == 'product' ? $lang->thinkwizard->objects->product : $lang->thinkwizard->objects->typeList['RDA'][$configureObjects->type];

        if($mode == 'preview')
        {
            $count = $wizard->config['configureDimension']['count'];
            return div
            (
                setClass('flex justify-center relative'),
                img(set::src("data/thinmory/wizardsetting/rda/dimension$count.svg")),
                div(setClass('absolute flex object-green text-sm'), $enableCompetitor ?  ($lang->thinkwizard->dimension->actuallyObject[0] . $objectName) : $lang->thinkwizard->dimension->defaultObject[0]),
                div(setClass('absolute flex object-orange text-sm'), $enableCompetitor ? ($lang->thinkwizard->dimension->actuallyObject[1] . $objectName) : $lang->thinkwizard->dimension->defaultObject[1])
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
