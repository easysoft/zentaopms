<?php
declare(strict_types=1);
/**
 * The ajaxgetrepoissuestatistics view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$monthAdded = end($addList);
$monthFixed = end($fixedList);

$blockHeight = 300;
$progress    = $totalNum ? round($closedNum / $totalNum, 2) * 100 : 0;
$unClosedNum = $totalNum - $closedNum;

h::css('.repo-issue-statistics .fixed-rate {top: 30%; left: 25%;}');
div
(
    setID('repoStatisticBlock'),
    setClass('flex repo-issue-statistics w-full'),
    div
    (
        setClass('text-center w-1/3 flex col justify-center items-center'),
        div
        (
            setClass('chart pie-chart relative'),
            echarts
            (
                set::color(array('#2B80FF', '#E3E4E9')),
                set::width(120),
                set::height(120),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'      => 'pie',
                            'radius'    => array('80%', '90%'),
                            'itemStyle' => array('borderRadius' => '40'),
                            'label'     => array('show' => false),
                            'data'      => array($progress, 100 - $progress)
                        )
                    )
                )
            ),
            div
            (
                set::className('pie-chart-title text-center absolute fixed-rate'),
                div(span(set::className('text-2xl font-bold'), $progress . '%')),
                div
                (
                    span
                    (
                        setClass('text-sm text-gray'),
                        $lang->codescan->fixedRate
                    )
                )
            )
        ),
        div
        (
            setClass('border w-3/4 flex justify-center items-center pl-4 py-2 cursor-pointer'),
            div
            (
                setClass('w-1/3'),
                set::title("{$lang->codescan->issueCount}: {$totalNum}"),
                div
                (
                    setClass('text-lg font-bold'),
                    $this->codescan->formatNumberToW($totalNum)
                ),
                $lang->codescan->issueCount
            ),
            div
            (
                setClass('w-1/3'),
                set::title("{$lang->codescan->closed}: {$closedNum}"),
                div
                (
                    setClass('text-lg font-bold'),
                    $this->codescan->formatNumberToW($closedNum)
                ),
                $lang->codescan->closed
            ),
            div
            (
                setClass('w-1/3'),
                set::title("{$lang->codescan->unClosed}: {$unClosedNum}"),
                div
                (
                    setClass('text-lg font-bold'),
                    $this->codescan->formatNumberToW($unClosedNum)
                ),
                $lang->codescan->unClosed
            )
        )
    ),
    echarts
    (
        set::height($blockHeight),
        set::title(array(
            'text'      => "{title|{$lang->codescan->issueStatistics}} {content|{$lang->codescan->monthAdded}} {add|$monthAdded}{content| {$lang->codescan->monthFixed}} {fixed|$monthFixed}",
            'textStyle' => array(
                'rich'  => array(
                    'title'   => array('fontSize' => 18, 'fontWeight' => 'bold'),
                    'content' => array('fontSize' => 14),
                    'add'     => array('fontSize' => 14, 'padding' => array(2, 0, 0), 'color' => '#409eff'),
                    'fixed'   => array('fontSize' => 14, 'padding' => array(2, 0, 0), 'color' => '#67c23a'),
                )
            ),
            'left'    => '6%',
            'subtext' => $lang->codescan->pcs
        )),
        set::tooltip(array('trigger' => 'axis')),
        set::legend(array(
            'left'    => 'right',
            'padding' => array(10, 60, 0, 0),
            'data'    => array(
                array('name' => $lang->codescan->added, 'icon' => 'circle'),
                array('name' => $lang->codescan->fixed, 'icon' => 'circle')
            )
        )),
        set::xAxis(array('type' => 'category', 'boundaryGap' => false, 'data' => $monthList)),
        set::yAxis(array('type' => 'value')),
        set::series(array(
            array('name' => $lang->codescan->added, 'type' => 'line', 'data' => $addList),
            array('name' => $lang->codescan->fixed, 'type' => 'line', 'data' => $fixedList)
        ))
    )
);
