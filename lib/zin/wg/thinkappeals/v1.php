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
        global $app;

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
            set::width('100%'),
            set::height('900px'),
            set::color(array('#29AA93', '#FF9F46')),
            set::legend(array(array(
                'data'       => $blocks['legend'],
                'icon'       => 'circle',
                'itemGap'    => 90,
                'itemWidth'  => 14,
                'itemHeight' => 14,
                'bottom'     => 40,
                'textStyle'  => array('color' => '#64758B', 'padding' => array(0, 0, 0, 16))
            ))),
            set::radar(array('nameGap' => 32, 'indicator' => $this->getIndicator())),
            set::series
            (
                array(
                    array(
                        'type'  => 'radar',
                        'data'  => $blocks['seriesData'],
                        'label' => array('normal' => array('show' => true, 'formatter' => jsRaw('formatSeriesLabel')))
                    )
                )
            )
        );
    }
}
