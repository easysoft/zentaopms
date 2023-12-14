<?php
declare(strict_types=1);
/**
 * The cfd view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

if(!$features['story']) unset($lang->execution->cfdTypeList['story']);
if(!$features['qa'])    unset($lang->execution->cfdTypeList['bug']);

jsVar('executionID', $executionID);
jsVar('cfdTip', $lang->execution->charts->cfd->cfdTip);
featureBar
(
    (!in_array($execution->status, array('wait,doing')) && hasPriv('execution', 'computeCFD')) ? btn
    (
        setClass('primary mr-5 refresh-btn'),
        set::url(createLink('execution', 'computeCFD', "reload=yes&executionID=$executionID")),
        set::icon('refresh'),
        set('data-toggle', 'modal'),
        $lang->execution->computeCFD
    ) : null,
    li
    (
        setClass('nav-item mr-3'),
        a
        (
            set::id('weekend'),
            set::href(createLink('execution', 'cfd', "executionID={$executionID}&type={$type}&withWeekend=" . ($withWeekend == 'true' ? 'false' : 'true'))),
            $lang->execution->withweekend
        )
    ),
    li
    (
        setClass('nav-item mr-2 type-selecter'),
        control
        (
            set::type('picker'),
            set::name('type'),
            set::value($type),
            set::items($lang->execution->cfdTypeList),
            on::change('changeType'),
            set::required(true)
        )
    ),
    li
    (
        setClass('nav-item ml-3'),
        formBase
        (
            set::actions(array()),
            inputGroup
            (
                input
                (
                    set::type('date'),
                    set::name('begin'),
                    set::value($begin)
                ),
                span
                (
                    setClass('input-group-addon'),
                    $lang->project->to
                ),
                input
                (
                    set::type('date'),
                    set::name('end'),
                    set::value($end)
                ),
                btn
                (
                    setClass('primary ml-4'),
                    set::btnType('submit'),
                    $lang->preview
                )
            )
        )
    )
);

$index       = 0;
$chartColors = array('#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#FF8C5A', '#6C73FF');
$chartSeries = array();
foreach($chartData['line'] as $label => $set)
{
    $chartSeries[] = array(
        'name'      => $label,
        'type'      => 'line',
        'stack'     => 'Total',
        'data'      => array_values($set),
        'color'     => $chartColors[$index],
        'areaStyle' => array('color' => $chartColors[$index], 'opacity' => 0.2),
        'itemStyle' => array('normal' => array('lineStyle' => array('width' => 1))),
        'emphasis'  => array('focus' => 'series')
    );

    $index ++;
}

$cfdChart = null;
if(isset($chartData['labels']) and count($chartData['labels']) != 1)
{
    $cfdChart = echarts
    (
        set::series($chartSeries),
        set::tooltip(array(
            'trigger'     => 'axis',
            'axisPointer' => array('type' => 'cross', 'label' => array('backgroundColor' => '#6a7985')),
            'textStyle'   => array('fontWeight' => 100),
            'formatter'   => "RAWJS<function(rowDatas){return window.randTipInfo(rowDatas);}>RAWJS"
        )),
        set::legend(array(
            'data' => array_keys(array_reverse($chartData['line']))
        )),
        set::grid(array(
            'left'         => '3%',
            'right'        => '5%',
            'bottom'       => '3%',
            'containLabel' => true
        )),
        set::xAxis(array(array(
            'type' => 'category',
            'boundaryGap' => false,
            'data' => $chartData['labels'],
            'name' => $lang->execution->burnXUnit,
            'axisLine' => array('show' => true, 'lineStyle' =>array('color' => '#999', 'width' =>1))
        ))),
        set::yAxis(array(array(
            'type'          => 'value',
            'name'          => $lang->execution->count,
            'minInterval'   => 1,
            'nameTextStyle' => array('fontWeight' => 'normal'),
            'axisPointer'   => array('label' => array('show' => true, 'precision' => 0)),
            'axisLine'      => array('show' => true, 'lineStyle' => array('color' => '#999', 'width' => 1))
        )))
    )->size('100%', 500);

}
else
{
    $cfdChart = div
    (
        setClass('table-empty-tip text-center'),
        span
        (
            setClass('text-gray'),
            $lang->execution->noPrintData
        )
    );
}

panel
(
    set::headingClass('justify-center'),
    set::title(
        $executionName . ' - ' . zget($lang->execution->cfdTypeList, $type) . $lang->execution->CFD
    ),
    to::heading
    (
        icon
        (
            'help',
            setClass('mt-2 cfd-help'),
            set('data-toggle', 'tooltip'),
            set('id', 'cfdHover')
        )
    ),
    set::titleClass('text-lg font-bold mt-2'),
    $cfdChart
);

render();
