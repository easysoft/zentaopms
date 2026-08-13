<?php
declare(strict_types=1);
/**
 * The edit view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Claude
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;
global $app;

$app->loadLang('pipeline');

if($repo)
{
    dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));
}

jsVar('pipelineID', $pipeline->id);
jsVar('branchList', $branchList);
jsVar('defaultBranch', zget($pipeline, 'defaultBranch', ''));

/* 传递给JS使用的lang字符串。 */
jsVar('langParamName', $lang->pipeline->paramNamePlaceholder);
jsVar('langParamValue', $lang->pipeline->paramValue);
jsVar('langDelete', $lang->pipeline->flowApp->labels['delete']);
jsVar('addTriggerTitle', $lang->pipeline->addTrigger);
jsVar('editTriggerTitle', $lang->pipeline->flowApp->labels['edit'] . $lang->pipeline->trigger);

/* Build branch picker items. */
$branchItems = array();
foreach($branchList as $branch => $branchName)
{
    if(is_array($branchName)) $branchName = zget($branchName, 'name', $branch);
    $branchItems[] = array('text' => $branchName, 'value' => $branch);
}

/* Build trigger rows. */
$triggerRows = array();
$hasEvent   = false;
$hasComment = false;
$hasCron    = false;
foreach($triggers as $trigger)
{
    /* Event field. */
    if($trigger->event)
    {
        $hasEvent   = true;
        $events     = explode(',', $trigger->event);
        $eventNames = array();
        foreach($events as $evt)
        {
            if(isset($lang->pipeline->triggerFormEventList[$evt])) $eventNames[] = $lang->pipeline->triggerFormEventList[$evt];
        }
        $triggerRows[] = h::tr(
            setClass('trigger-row'),
            h::td($lang->pipeline->triggerFormTypeList['event']),
            h::td(implode(', ', $eventNames)),
            h::td(
                setClass('text-center'),
                btn(
                    set::icon('trash'),
                    set::size('sm'),
                    setClass('btn ghost text-primary del-trigger'),
                    set('data-trigger-id', $trigger->id),
                    set('data-field', 'event'),
                    set('title', $lang->pipeline->flowApp->labels['delete']),
                    on::click('deleteTrigger')
                )
            )
        );
    }

    /* Comment field. */
    if($trigger->comment)
    {
        $hasComment = true;
        $triggerRows[] = h::tr(
            setClass('trigger-row'),
            h::td($lang->pipeline->triggerFormTypeList['comment']),
            h::td($trigger->comment),
            h::td(
                setClass('text-center'),
                btn(
                    set::icon('trash'),
                    set::size('sm'),
                    setClass('btn ghost text-primary del-trigger'),
                    set('data-trigger-id', $trigger->id),
                    set('data-field', 'comment'),
                    set('title', $lang->pipeline->flowApp->labels['delete']),
                    on::click('deleteTrigger')
                )
            )
        );
    }

    /* Cron field. */
    if($trigger->cron)
    {
        $hasCron  = true;
        $cronParts = explode(' ', $trigger->cron);
        if(count($cronParts) === 5)
        {
            $minute  = $cronParts[0];
            $hour    = $cronParts[1];
            $day     = $cronParts[2];
            $month   = $cronParts[3];
            $weekDay = $cronParts[4];

            if($weekDay !== '*')
            {
                $cronType  = 'week';
                $dayName   = zget($lang->pipeline->triggerFormWeekList, $weekDay, $weekDay);
                $typeLabel = $lang->pipeline->triggerFormTypeList['week'];
                $condition = sprintf('%s %s:%s', $dayName, $hour, $minute);
            }
            else
            {
                $cronType  = 'month';
                $dayName   = zget($lang->pipeline->triggerFormMonthList, (int)$day, $day);
                $typeLabel = $lang->pipeline->triggerFormTypeList['month'];
                $condition = sprintf('%s %s:%s', $dayName, $hour, $minute);
            }

            $triggerRows[] = h::tr(
                setClass('trigger-row'),
                h::td($typeLabel),
                h::td($condition),
                h::td(
                    setClass('text-center'),
                    btn(
                        set::icon('trash'),
                        set::size('sm'),
                        setClass('btn ghost text-primary del-trigger'),
                        set('data-trigger-id', $trigger->id),
                        set('data-field', 'cron'),
                        set('title', $lang->pipeline->flowApp->labels['delete']),
                        on::click('deleteTrigger')
                    )
                )
            );
        }
    }
}

jsVar('hasEvent', $hasEvent);
jsVar('hasComment', $hasComment);
jsVar('hasCron', $hasCron);
jsVar('pipelineEngine', $pipeline->engine);

$isJenkins = ($pipeline->engine == 'jenkins');
$canAddTrigger = $isJenkins ? !$hasCron : !($hasEvent && $hasComment && $hasCron);

/* 根据已有触发器动态构建可用的触发类型列表 */
/* 原则：只显示还没有配置的类型（每种类型只能配置一次） */
/* Jenkins 只支持定时触发（按周/按月），不支持事件和关键字 */
$availableTriggerTypes = array();

/* cron类型：如果还没配置，就显示"按周"和"按月" */
if(!$hasCron)
{
    $availableTriggerTypes['week'] = $lang->pipeline->triggerFormTypeList['week'];
    $availableTriggerTypes['month'] = $lang->pipeline->triggerFormTypeList['month'];
}

/* event类型：非Jenkins且还没配置，显示"事件" */
if(!$isJenkins && !$hasEvent)
{
    $availableTriggerTypes['event'] = $lang->pipeline->triggerFormTypeList['event'];
}

/* comment类型：非Jenkins且还没配置，显示"关键字" */
if(!$isJenkins && !$hasComment)
{
    $availableTriggerTypes['comment'] = $lang->pipeline->triggerFormTypeList['comment'];
}

/* 获取第一个可用的触发器类型作为默认值 */
$defaultTriggerType = !empty($availableTriggerTypes) ? key($availableTriggerTypes) : ($isJenkins ? 'week' : 'event');

/* Build custom param rows. */
$paramRows = array();
if(!empty($customParam) && is_array($customParam))
{
    foreach($customParam as $key => $value)
    {
        $paramRows[] = h::tr(
            setClass('param-row'),
            h::td(input(set::name('paramKey[]'), set::value($key), set::placeholder($lang->pipeline->paramNamePlaceholder))),
            h::td(input(set::name('paramValue[]'), set::value($value), set::placeholder($lang->pipeline->paramValue))),
            h::td(
                setClass('text-center'),
                btn(
                    set::icon('trash'),
                    set::size('sm'),
                    setClass('btn ghost text-primary del-param'),
                    set('title', $lang->pipeline->flowApp->labels['delete']),
                    on::click('deleteParam')
                )
            )
        );
    }
}

formPanel
(
    set::id('pipelineEditForm'),
    set::title($lang->pipeline->edit),
    set::submitBtnText($lang->pipeline->flowApp->labels['save']),
    set::actions(array('submit', array('text' => $lang->pipeline->cancelBtn, 'data-type' => 'cancel', 'btnType' => 'default', 'url' => $cancelUrl))),

    /* 第一列：流水线名称 */
    formRow
    (
        formGroup
        (
            set::width('2/3'),
            setClass('mx-auto'),
            set::name('name'),
            set::label($lang->pipeline->name),
            set::required(true),
            set::value($pipeline->name),
            set::placeholder($lang->pipeline->flowApp->labels['flow-name-required'])
        )
    ),

    /* 第二列：备注 */
    formRow
    (
        formGroup
        (
            set::width('2/3'),
            setClass('mx-auto'),
            set::name('desc'),
            set::label($lang->pipeline->desc),
            set::control(array('type' => 'textarea', 'rows' => 4)),
            set::value($pipeline->desc),
            set::placeholder($lang->pipeline->flowApp->labels['flow-desc-placeholder'])
        )
    ),

    /* 第三列：执行分支（Jenkins 流水线不需要分支配置，直接使用 null 跳过） */
    $isJenkins ? null : formRow
    (
        formGroup
        (
            set::name('defaultBranch'),
            set::width('2/3'),
            setClass('mx-auto'),
            set::label($lang->pipeline->branch),
            set::required(true),
            set::control('picker'),
            set::items($branchItems),
            set::value(zget($pipeline, 'defaultBranch', ''))
        )
    ),

    /* 第四列：自定义构建参数 */
    formRow
    (
        formGroup
        (
            set::width('2/3'),
            setClass('mx-auto'),
            set::name(''),
            set::label($lang->pipeline->customParam),
            set::control('static'),
            div
            (
                setClass('w-full'),
                setID('customParamContainer'),
                h::table
                (
                    setClass('table bordered'),
                    setID('paramTable'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th($lang->pipeline->paramName),
                            h::th($lang->pipeline->paramValue),
                            h::th(
                                set::width(80),
                                $lang->pipeline->flowApp->labels['actions']
                            )
                        )
                    ),
                    h::tbody
                    (
                        setID('paramTbody'),
                        $paramRows
                    )
                ),
                btn
                (
                    set::icon('plus'),
                    set::size('sm'),
                    setClass('primary mt-2'),
                    $lang->pipeline->flowApp->labels['env-add'],
                    on::click('addParam')
                )
            )
        )
    ),

    /* 第五列：触发器 */
    formRow
    (
        formGroup
        (
            set::id('triggerGroup'),
            set::width('2/3'),
            setClass('mx-auto'),
            set::name(''),
            set::label($lang->pipeline->trigger),
            set::control('static'),
            div
            (
                setClass('w-full'),
                h::table
                (
                    setClass('table bordered'),
                    setID('triggerTable'),
                    h::thead
                    (
                        h::tr
                        (
                            h::th($lang->pipeline->flowApp->labels['trigger-type']),
                            h::th($lang->pipeline->flowApp->labels['trigger-rule']),
                            h::th(
                                set::width(80),
                                $lang->pipeline->flowApp->labels['actions']
                            )
                        )
                    ),
                    h::tbody
                    (
                        setID('triggerTbody'),
                        $triggerRows
                    )
                ),
                modalTrigger
                (
                    set::target('#triggerModal'),
                    btn
                    (
                        set::icon('plus'),
                        set::size('sm'),
                        setID('addTriggerBtn'),
                        setClass('primary mt-2 ' . ($canAddTrigger ? '' : 'hidden')),
                        $lang->pipeline->addTrigger
                    )
                )
            )
        )
    )

);

modal
(
    setID('triggerModal'),
    set::modalProps(array('title' => $lang->pipeline->addTrigger)),
    formPanel
    (
        setID('triggerFormPanel'),
        set::size('sm'),
        set::labelWidth('8em'),
        set::url(createLink('pipeline', 'ajaxSaveTrigger', "pipelineID={$pipeline->id}")),
        set::submitBtnText($lang->pipeline->triggerForm->submit),
        set::actions(array('submit', array('text' => $lang->pipeline->cancelBtn, 'data-dismiss' => 'modal'))),
        on::change('[name="type"]', 'changeTriggerType'),

        /* 触发方式 */
        formRow
        (
            formGroup
            (
                set::name('type'),
                set::label($lang->pipeline->triggerForm->type),
                set::width('full'),
                set::control(array('control' => 'radioList', 'inline' => true)),
                set::items($availableTriggerTypes),
                set::value($defaultTriggerType)
            )
        ),

        /* 触发事件 */
        formRow
        (
            setID('eventRow'),
            formGroup
            (
                set::name('event'),
                set::label($lang->pipeline->triggerForm->event),
                set::required(true),
                set::width('1/2'),
                set::control('picker'),
                set::items($lang->pipeline->triggerFormEventList),
                set::multiple(true)
            )
        ),

        /* 每周 */
        formRow
        (
            setID('weekDayRow'),
            setClass('hidden'),
            formGroup
            (
                set::name('weekDay'),
                set::label($lang->pipeline->triggerForm->weekDay),
                set::required(true),
                set::width('1/2'),
                set::control('picker'),
                set::items($lang->pipeline->triggerFormWeekList)
            )
        ),

        /* 时间 */
        formRow
        (
            setID('timeRow'),
            setClass('hidden'),
            formGroup
            (
                set::name('time'),
                set::label($lang->pipeline->triggerForm->time),
                set::required(true),
                set::width('1/2'),
                set::control('timePicker')
            )
        ),

        /* 每月 */
        formRow
        (
            setID('monthDayRow'),
            setClass('hidden'),
            formGroup
            (
                set::name('monthDay'),
                set::label($lang->pipeline->triggerForm->monthDay),
                set::required(true),
                set::width('1/2'),
                set::control('picker'),
                set::items($lang->pipeline->triggerFormMonthList)
            )
        ),

        /* 关键字 */
        formRow
        (
            setID('commentRow'),
            setClass('hidden'),
            formGroup
            (
                set::name('comment'),
                set::label($lang->pipeline->triggerForm->comment),
                set::required(true),
                set::width('1/2'),
                set::control('input')
            )
        )
    )
);

render();
