<?php
declare(strict_types=1);
/**
 * The ajaxGetIssueTrends view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$btnLabels = array();
foreach($this->lang->codescan->dayLabels as $key => $label)
{
    $btnLabels[] = btn
    (
        setClass('default w-16 p-0 open-url', $key == $day ? 'text-primary' : ''),
        setData('target', '#issueTrendStatistic'),
        setData('load', 'target'),
        setData('partial', 'true'),
        setData('url', inLink('ajaxGetIssueTrends', "repoID=$repoID&day=$key")),
        set::key($key),
        $label
    );
}


$dayList = array_keys($added);
div
(
    setID('issueTrendStatistic'),
    formGroup
    (
        setClass('mb-4'),
        btnGroup($btnLabels)
    ),
    div
    (
        setClass('flex justify-between'),
        div
        (
            setClass('w-1/2'),
            echarts
            (
                set::height(260),
                set::title(array(
                    'text'    => $lang->codescan->addIssueStatistic,
                    'left'    => '6%',
                    'subtext' => $lang->codescan->pcs
                )),
                set::tooltip(array('trigger' => 'axis')),
                set::xAxis(array(
                    'type'        => 'category',
                    'boundaryGap' => false,
                    'axisLabel'   => array('rotate' => 40),
                    'data'        => $dayList
                )),
                set::yAxis(array('type' => 'value')),
                set::series(array(array('name' => $lang->codescan->added, 'type' => 'line', 'data' => array_values($added))))
            )
        ),
        div
        (
            setClass('w-1/2'),
            echarts
            (
                set::height(260),
                set::color(array('#91cc75')),
                set::title(array(
                    'text'    => $lang->codescan->fixIssueStatistic,
                    'left'    => '6%',
                    'subtext' => $lang->codescan->pcs
                )),
                set::tooltip(array('trigger' => 'axis')),
                set::xAxis(array(
                    'type'        => 'category',
                    'boundaryGap' => false,
                    'axisLabel'   => array('rotate' => 40),
                    'data'        => $dayList
                )),
                set::yAxis(array('type' => 'value')),
                set::series(array(array('name' => $lang->codescan->fixed, 'type' => 'line', 'data' => array_values($fixed))))
            )
        )
    )
);
