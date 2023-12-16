<?php
declare(strict_types=1);
/**
* The releasestatistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$annualMaxReleases   = !empty($releases) ? max($releases) : 0;
$releaseProgressData = array();
$i = 1;
foreach($releases as $productName => $releaseCount)
{
    $progress = ($annualMaxReleases ? ($releaseCount / $annualMaxReleases * 100) : 0) . '%';

    $nameClass  = '';
    $labelClass = 'primary';
    if($i == 1) $labelClass = $nameClass = 'danger';
    if($i == 2 || $i == 3) $labelClass = $nameClass = 'warning';

    $releaseProgressData[] = div
    (
        set::className('flex py-1.5 justify-between'),
        cell
        (
            set::width('calc(30% - 0.5rem)'),
            set::className('clip'),
            label
            (
                set::className($labelClass . '-pale px-1.5 circle'),
                $i
            ),
            span
            (
                set::className('pl-2 text-' . $nameClass),
                $productName
            )
        ),
        cell
        (
            set::width('70%'),
            div
            (
                set::className('progress'),
                div
                (
                    set::className('progress-bar primary'),
                    setStyle('width', $progress)
                )
            )
        )
    );
    $i ++;
}

blockPanel
(
    div
    (
        set::className('flex flex-wrap justify-between'),
        cell
        (
            set::width($longBlock ? '49%' : '100%'),
            set::className($longBlock ? '' : 'pb-2'),

            div
            (
                set::className('px-2 pb-2'),
                $lang->block->releasestatistic->monthly
            ),
            echarts
            (
                set::color(array('#2B80FF')),
                set::grid(array('left' => '10px', 'top' => '30px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::xAxis(array('type' => 'category', 'data' => array_keys($releaseData), 'axisTick' => array('alignWithLabel' => true))),
                set::yAxis(array('type' => 'value', 'name' => $lang->number, 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'     => 'line',
                            'name'     => $lang->product->releases,
                            'data'     => array_values($releaseData),
                            'emphasis' => array('label' => array('show' => true))
                        )
                    )
                )
            )->size('100%', $longBlock ? 200 : 175)
        ),
        cell
        (
            set::width($longBlock ? '49%' : '100%'),
            div
            (
                set::className('px-2 pb-2'),
                icon('chart-bar pr-1'),
                sprintf($lang->block->releasestatistic->annual, date('Y'))
            ),
            div
            (
                set::className('p-2 overflow-y-auto'),
                setStyle('height', $longBlock ? '200px' : '175px'),
                $releaseProgressData
            )
        )
    )
);

render();
