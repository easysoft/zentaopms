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

$chartItems = function() use($charts, $lang)
{
    if(empty($charts)) return div(setClass('bg-white'), div(setClass('empty-tip text-gray'), $lang->chart->noChart));

    $chartDoms = array();
    foreach($charts as $chart)
    {
        $chartDoms[] = div
        (
            setClass('p-2 panel bg-white'),
            div
            (
                setClass('panel-body'),
                div
                (
                    setClass('panel-title'),
                    $chart->name,
                ),
                div
                (
                    setID('filterItems' . $chart->group . '_' . $chart->id),
                    setClass('filterBox'),
                ),
                div
                (
                    setClass('echart-content'),
                    echarts
                    (
                        set($chart->echartOption),
                    )->size('100%', 400),
                )
            )
        );
    }

    return $chartDoms;
};
