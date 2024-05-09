<?php
declare(strict_types = 1);
/**
 * The preview view file of chart module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     chart
 * @link        https://www.zentao.net
 */
namespace zin;

$generateCharts = function() use($charts, $lang)
{
    if(empty($charts)) return div(setClass('bg-canvas center text-gray w-full h-40'), $lang->chart->noChart);

    $chartList = array();
    foreach($charts as $chart)
    {
        $options = array();
        $filters = array();
        foreach($chart->filters as $filter)
        {
            $name  = $filter['name'];
            $type  = $filter['type'];
            $field = $filter['field'];
            $value = zget($filter, 'default', '');

            if($type == 'select' && !isset($options[$field]))
            {
                $fieldSetting = $chart->fieldSettings[$field];
                $options[$field] = $this->chart->getSysOptions($fieldSetting['type'], $fieldSetting['object'], $fieldSetting['field'], $chart->sql, zget($filter, 'saveAs', ''));
            }
            $filters[] = resultFilter(set(array('title' => $name, 'type' => $type, 'name' => $field, 'value' => $value, 'items' => zget($options, $field, array()))));
        }

        $chartID      = $chart->currentGroup . '_' . $chart->id;
        $chartOptions = $this->chart->getEchartOptions($chart);

        $chartList[] = panel
        (
            setID('chartPanel_' . $chartID),
            set::title($chart->name),
            set::shadow(false),
            set::headingClass('h-14'),
            set::bodyClass('pt-0'),
            $filters ? div
            (
                setID('filter_' . $chartID),
                setClass('flex justify-between bg-canvas'),
                div
                (
                    setClass('flex flex-wrap w-full'),
                    $filters
                ),
                button
                (
                    setClass('btn primary'),
                    setData(array('on' => 'click', 'call' => "loadChart('{$chartID}')")),
                    $lang->chart->query
                )
            ) : null,
            div
            (
                setID('chart_' . $chartID),
                setClass('echart-content'),
                set('data-group', $chart->currentGroup),
                set('data-id', $chart->id),
                $this->chart->isChartHaveData($chartOptions, $chart->type) ? echarts
                (
                    set($chartOptions),
                    set::width('100%'),
                    set::height(400),
                    $chart->type == 'waterpolo' ? set::exts('liquidfill') : null,
                ) : div
                (
                    setClass('no-data'),
                    div
                    (
                        setClass('no-data-dom'),
                        span(setClass('text-muted'), $this->lang->chart->noData)
                    )
                )
            )
        );
    }

    return $chartList;
};
