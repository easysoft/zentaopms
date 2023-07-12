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

$releaseMonthData = array('9月', '10月', '11月', '12月', '1月', '2月');
$releaseData      = array(10, 30, 15, 35, 50, 15);

$releases = array('沃尔玛二次开发' => 320, '禅道管理集成系统' => 310, '禅道瀑布项目增强系统' => 250, '开发阿道环游记' => 260, '自动化测试框架' => 230, '禅道开源版' => 220);
$annualMaxReleases = max($releases);

$releaseProgressData = array();
$i = 1;
foreach($releases as $productName => $releaseCount)
{
    $progress = $releaseCount / $annualMaxReleases * 100 . '%';

    $nameClass  = '';
    $labelClass = 'primary';
    if($i == 1) $labelClass = $nameClass = 'danger';
    if($i == 2 || $i == 3) $labelClass = $nameClass = 'warning';

    $releaseProgressData[] = div
    (
        set::class('flex py-1.5 justify-between'),
        cell
        (
            set::width('30%'),
            set::class('ellipsis'),
            label
            (
                set::class($labelClass . '-pale px-1.5 circle'),
                $i
            ),
            span
            (
                set::class('pl-2 text-' . $nameClass),
                $productName,
            )
        ),
        cell
        (
            set::width('70%'),
            div
            (
                set::class('progress'),
                div
                (
                    set::class('progress-bar primary'),
                    setStyle('width', $progress),
                )
            )
        )
    );
    $i ++;
}

panel
(
    set::title($block->title),
    set::class('releasestatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    div
    (
        set::class('flex flex-wrap justify-between'),
        cell
        (
            set::width($longBlock ? '49%' : '100%'),
            set::class($longBlock ? '' : 'pb-2'),

            div
            (
                set::class('px-2 pb-2'),
                $lang->block->releasestatistic->monthly,
            ),
            echarts
            (
                set::color(array('#2B80FF')),
                set::grid(array('left' => '10px', 'top' => '30px', 'right' => '0', 'bottom' => '0',  'containLabel' => true)),
                set::xAxis(array('type' => 'category', 'data' => $releaseMonthData, 'axisTick' => array('alignWithLabel' => true))),
                set::yAxis(array('type' => 'value', 'name' => '（个）', 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'     => 'line',
                            'name'     => $lang->product->releases,
                            'data'     => $releaseData,
                            'emphasis' => array('label' => array('show' => true))
                        )
                    )
                )
            )->size('100%', $longBlock ? 200 : 175),
        ),
        cell
        (
            set::width($longBlock ? '49%' : '100%'),
            div
            (
                set::class('px-2 pb-2'),
                icon('chart-bar pr-1'),
                sprintf($lang->block->releasestatistic->annual, date('Y'))
            ),
            div
            (
                set::class('p-2 overflow-y-auto'),
                setStyle('height', $longBlock ? '200px' : '175px'),
                $releaseProgressData,
            )
        ),
    )
);

render();
