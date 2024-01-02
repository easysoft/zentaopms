<?php
declare(strict_types=1);
/**
 * The edit view file of testreport module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testreport
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('goalTip',         $lang->testreport->goalTip);
jsVar('foundBugTip',     $lang->testreport->foundBugTip);
jsVar('legacyBugTip',    $lang->testreport->legacyBugTip);
jsVar('activatedBugTip', $lang->testreport->activatedBugTip);
jsVar('fromCaseBugTip',  $lang->testreport->fromCaseBugTip);

$caseCharts = array();
foreach($charts as $chartType => $chartOption)
{
    $chartData   = $datas[$chartType];
    $chartOption = array();
    $tableTR     = array();
    $colorList   = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
    foreach($chartData as $key => $data)
    {
        $color = current($colorList);
        $chartOption[] = array('name' => $data->name, 'value' => $data->value);
        $tableTR[] = h::tr
        (
            h::td(label(set::className('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $data->name),
            h::td($data->value),
            h::td(($data->percent * 100) . '%')
        );
        if(!next($colorList)) reset($colorList);
    }

    $caseCharts[] = div
    (
        set::className('mt-6'),
        div
        (
            set::className('flex border'),
            cell
            (
                set::width('50%'),
                set::className('border-r'),
                div(set::className('center text-base font-bold py-2'), $lang->testtask->report->charts[$chartType]),
                echarts
                (
                    set::color($colorList),
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'data' => $chartOption,
                                'type' => 'pie'
                            )
                        )
                    )
                )->size('100%', 300)
            ),
            cell
            (
                set::width('50%'),
                h::table
                (
                    set::className('table'),
                    h::tr
                    (
                        h::th($lang->report->item),
                        h::th(set::width('100px'), $lang->report->value),
                        h::th(set::width('120px'), $lang->report->percent)
                    ),
                    $tableTR
                )
            )
        )
    );
}

$tableTR     = array();
$colorList   = array('#D5D9DF', '#D50000', '#FF9800', '#2098EE', '#009688');
$chartOption = array();
foreach($lang->bug->priList as $key => $value)
{
    $color = current($colorList);
    $label = $key == 0 ? $lang->null : $value;

    $generated = !empty($infoValue[$key]['generated']) ? $infoValue[$key]['generated'] : 0;
    $legacy    = !empty($infoValue[$key]['legacy'])    ? $infoValue[$key]['legacy']    : 0;
    $resolved  = !empty($infoValue[$key]['resolved'])  ? $infoValue[$key]['resolved']  : 0;

    $chartOption[] = array('name' => $label, 'type' => 'bar', 'data' => array($generated, $legacy, $resolved));
    $tableTR[] = h::tr
    (
        h::td(label(set::className('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $label),
        h::td($generated),
        h::td($legacy),
        h::td($resolved)
    );
    if(!next($colorList)) reset($colorList);
}

$bugStageChart = div
(
    set::className('mt-6'),
    div
    (
        set::className('flex border'),
        cell
        (
            set::width('50%'),
            set::className('border-r'),
            div(set::className('center text-base font-bold py-2'), $lang->testreport->bugStageGroups),
            echarts
            (
                set::color($colorList),
                set::xAxis
                (
                    array
                    (
                        'type' => 'category',
                        'data' => array($lang->testreport->bugStageList['generated'], $lang->testreport->bugStageList['legacy'], $lang->testreport->bugStageList['resolved'])
                    )
                ),
                set::yAxis(array('type' => 'value')),
                set::series($chartOption)
            )->size('100%', 300)
        ),
        cell
        (
            set::width('50%'),
            h::table
            (
                set::className('table'),
                h::tr
                (
                    h::th($lang->bug->pri),
                    h::th($lang->testreport->bugStageList['generated']),
                    h::th($lang->testreport->bugStageList['legacy']),
                    h::th($lang->testreport->bugStageList['resolved'])
                ),
                $tableTR
            )
        )
    )
);

$tableTR     = array();
$chartOption = array();
$xAxisData   = array();
$beginTime   = isset($report->begin) ? strtotime($report->begin) : strtotime($begin);
$endTime     = isset($report->end)   ? strtotime($report->end)   : strtotime($end);
foreach(array('generated', 'legacy', 'resolved') as $field)
{
    $chartOption[$field] = array('name' => $lang->testreport->bugStageList[$field], 'type' => 'line', 'data' => array());
}

for($time = $beginTime; $time <= $endTime; $time += 86400)
{
    $date      = date('m-d', $time);
    $generated = !empty($infoValue['generated'][$date]) ? $infoValue['generated'][$date] : 0;
    $legacy    = !empty($infoValue['legacy'][$date])    ? $infoValue['legacy'][$date]    : 0;
    $resolved  = !empty($infoValue['resolved'][$date])  ? $infoValue['resolved'][$date]  : 0;

    $chartOption['generated']['data'][] = $generated;
    $chartOption['legacy']['data'][]    = $legacy;
    $chartOption['resolved']['data'][]  = $resolved;
    $xAxisData[]  = $date;
    $tableTR[]    = h::tr
    (
        h::td($date),
        h::td($generated),
        h::td($legacy),
        h::td($resolved)
    );
}
$chartOption    = array_values($chartOption);
$bugHandleChart = div
(
    set::className('mt-6'),
    div
    (
        set::className('flex border'),
        cell
        (
            set::width('50%'),
            set::className('border-r'),
            div(set::className('center text-base font-bold py-2'), $lang->testreport->bugHandleGroups),
            echarts
            (
                set::color(array('#FF9800', '#2098EE', '#009688')),
                set::xAxis
                (
                    array
                    (
                        'type' => 'category',
                        'data' => $xAxisData
                    )
                ),
                set::yAxis(array('type' => 'value')),
                set::series($chartOption)
            )->size('100%', 300)
        ),
        cell
        (
            set::width('50%'),
            h::table
            (
                set::className('table'),
                h::tr
                (
                    h::th($lang->testreport->date),
                    h::th(label(set::className('label-dot mr-2'), set::style(array('background-color' => '#FF9800', '--tw-ring-color' => '#FF9800'))), $lang->testreport->bugStageList['generated']),
                    h::th(label(set::className('label-dot mr-2'), set::style(array('background-color' => '#2098EE', '--tw-ring-color' => '#2098EE'))), $lang->testreport->bugStageList['legacy']),
                    h::th(label(set::className('label-dot mr-2'), set::style(array('background-color' => '#009688', '--tw-ring-color' => '#009688'))), $lang->testreport->bugStageList['resolved'])
                ),
                $tableTR
            )
        )
    )
);

$bugCharts = array();
foreach($bugInfo as $infoKey => $infoValue)
{
    if($infoKey == 'bugStageGroups' || $infoKey == 'bugHandleGroups') continue;
    $list = $infoValue;
    if($infoKey == 'bugSeverityGroups')   $list = $lang->bug->severityList;
    if($infoKey == 'bugStatusGroups')     $list = $lang->bug->statusList;
    if($infoKey == 'bugResolutionGroups') $list = $lang->bug->resolutionList;

    $chartOption = array();
    $tableTR     = array();
    $colorList   = array('#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272', '#FC8452', '#9A60B4', '#EA7CCC');
    $sum         = 0;
    foreach($infoValue as $value) $sum += $value->value;
    foreach($list as $listKey => $listValue)
    {
        $color = current($colorList);
        $name  = $listValue;
        $value = 0;
        if(isset($infoValue[$listKey]))
        {
            $name  = $infoValue[$listKey]->name;
            $value = $infoValue[$listKey]->value;
        }
        if(empty($name) && empty($value)) continue;

        $chartOption[] = array('name' => $name, 'value' => $value);
        $tableTR[]     = h::tr
        (
            h::td(label(set::className('label-dot mr-2'), set::style(array('background-color' => $color, '--tw-ring-color' => $color))), $name),
            h::td($value),
            h::td(($sum ? round(($value / $sum * 100), 2) : '0') . '%')
        );
        if(!next($colorList)) reset($colorList);
    }

    $bugCharts[] = div
    (
        set::className('mt-6'),
        div
        (
            set::className('flex border'),
            cell
            (
                set::width('50%'),
                set::className('border-r'),
                div(set::className('center text-base font-bold py-2'), $lang->testreport->{$infoKey}),
                echarts
                (
                    set::color($colorList),
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'data' => $chartOption,
                                'type' => 'pie'
                            )
                        )
                    )
                )->size('100%', 300)
            ),
            cell
            (
                set::width('50%'),
                h::table
                (
                    set::className('table'),
                    h::tr
                    (
                        h::th($lang->report->item),
                        h::th(set::width('100px'), $lang->report->value),
                        h::th(set::width('120px'), $lang->report->percent)
                    ),
                    $tableTR
                )
            )
        )
    );
}


panel
(
    formPanel
    (
        set::title($lang->testreport->edit),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::label($lang->testreport->startEnd),
                set::required(strpos(",{$this->config->testreport->edit->requiredFields},", ",begin,") !== false || strpos(",{$this->config->testreport->edit->requiredFields},", ",end,") !== false),
                inputGroup
                (
                    datePicker
                    (
                        set::name('begin'),
                        set::value($begin)
                    ),
                    $lang->testtask->to,
                    datePicker
                    (
                        set::name('end'),
                        set::value($end)
                    )
                ),
                input
                (
                    set::className('hidden'),
                    set::name('product'),
                    set::value($productIdList)
                ),
                input
                (
                    set::className('hidden'),
                    set::name('execution'),
                    set::value($execution->id)
                ),
                input
                (
                    set::className('hidden'),
                    set::name('tasks'),
                    set::value($report->tasks)
                ),
                input
                (
                    set::className('hidden'),
                    set::name('bugs'),
                    set::value(implode(',', array_keys($bugs)))
                ),
                input
                (
                    set::className('hidden'),
                    set::name('builds'),
                    set::value(implode(',', array_keys($builds)))
                ),
                input
                (
                    set::className('hidden'),
                    set::name('cases'),
                    set::value(implode(',', array_keys($caseList)))
                ),
                input
                (
                    set::className('hidden'),
                    set::name('stories'),
                    set::value(implode(',', array_keys($stories)))
                )
            ),
            formGroup
            (
                set::width('1/2'),
                set::label($lang->testreport->owner),
                set::control('picker'),
                set::name('owner'),
                set::items($users),
                set::value($report->owner)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->testreport->members),
                set::required(strpos(",{$this->config->testreport->edit->requiredFields},", ",members,") !== false),
                picker
                (
                    set::multiple(true),
                    set::name('members[]'),
                    set::items($users),
                    set::value($report->members)
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->testreport->title),
                set::name('title'),
                set::value($report->title)
            )
        ),
        !empty($execution->desc) ? formRow
        (
            formGroup
            (
                set::className('items-center'),
                set::label($lang->testreport->goal),
                span
                (
                    icon
                    (
                        'help',
                        set('data-toggle', 'tooltip'),
                        set('id', 'goalTip'),
                        set('class', 'text-light')
                    ),
                    $execution->desc
                )
            )
        ) : null,
        formRow
        (
            formGroup
            (
                set::className('items-center'),
                set::label($lang->testreport->profile),
                div
                (
                    div(html($storySummary)),
                    div(html(sprintf($lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary)),
                    div(html(sprintf($lang->testreport->bugSummary, $bugSummary['foundBugs'], count($legacyBugs), $bugSummary['activatedBugs'],  $bugSummary['countBugByTask'], $bugSummary['bugConfirmedRate'] . '%', $bugSummary['bugCreateByCaseRate'] . '%')))
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->testreport->report),
                set::required(strpos(",{$this->config->testreport->edit->requiredFields},", ",report,") !== false),
                editor
                (
                    set::name('report'),
                    html($report->report)
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->files),
                $report->files ? fileList
                (
                    set::files($report->files),
                    set::fieldset(false)
                ) : null,
                upload()
            )
        )
    ),
    formPanel
    (
        set::title($lang->testreport->legendStoryAndBug),
        set::actions(array()),
        dtable
        (
            set::cols($config->testreport->story->dtable->fieldList),
            set::data(array_values($stories)),
            set::emptyTip($lang->story->noStory),
            set::userMap($users)
        ),
        div(set::className('my-6')),
        dtable
        (
            set::cols($config->testreport->bug->dtable->fieldList),
            set::data(array_values($bugs)),
            set::emptyTip($lang->bug->notice->noBug),
            set::userMap($users)
        )
    ),
    formPanel
    (
        set::className('mt-6'),
        set::title($lang->testreport->legendBuild),
        set::actions(array()),
        dtable
        (
            set::cols($config->testreport->build->dtable->fieldList),
            set::data(array_values($builds)),
            set::userMap($users)
        )
    ),
    formPanel
    (
        set::className('mt-6'),
        set::title($lang->testreport->legendCase),
        set::actions(array()),
        dtable
        (
            set::cols($config->testreport->testcase->dtable->fieldList),
            set::data(array_values($caseList)),
            set::userMap($users)
        )
    ),
    formPanel
    (
        set::className('mt-6'),
        set::title($lang->testreport->legendLegacyBugs),
        set::actions(array()),
        dtable
        (
            set::cols($config->testreport->bug->dtable->fieldList),
            set::data(array_values($legacyBugs)),
            set::userMap($users)
        )
    ),
    formPanel
    (
        set::className('mt-6'),
        set::title($lang->testreport->legendReport),
        set::actions(array()),
        $caseCharts,
        $bugStageChart,
        $bugHandleChart,
        $bugCharts
    )
);

render();
