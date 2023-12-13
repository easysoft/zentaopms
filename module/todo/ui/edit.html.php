<?php
declare(strict_types=1);
/**
 * The edit ui file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('moduleList', $config->todo->moduleList);
jsVar('objectsMethod', $config->todo->getUserObjectsMethod);
jsVar('defaultType', $todo->type);
jsVar('objectID', $todo->objectID);
jsVar('nameBoxLabel', array('custom' => $lang->todo->name, 'objectID' => $lang->todo->objectID));
jsVar('vision', $config->vision);
jsVar('noOptions', $lang->todo->noOptions);
jsVar('chosenType', $lang->todo->typeList);
jsVar('userID', $app->user->id);
jsVar('userAccount', $app->user->account);

if($todo->cycle && $todo->config)
{
    $todo->config = json_decode($todo->config);
    $type = '';
    if(isset($todo->config->type)) $type = $todo->config->type == 'day' && isset($todo->config->cycleYear) ? 'year' : $todo->config->type;
    jsVar('cycleType', $type);

    if(isset($todo->config->type) && $todo->config->type == 'day' && isset($todo->config->cycleYear)) $todo->date = '';
    if(isset($todo->config->month) || isset($todo->config->week)) $todo->date = '';
}
else
{
    jsVar('cycleType', '');
}

/**
 * 构建日期控件，用于非周期待办展示。
 * Build date control for off-cycle todo display.
 *
 * @param  object $todo
 * @return mixed
 */
$buildDateControl = function(object $todo): mixed
{
    global $lang;

    if($todo->cycle) return null;
    return formRow
    (
        setClass('items-center'),
        formGroup
        (
            set::label($lang->todo->date),
            set::width('1/3'),
            datepicker
            (
                setClass('date'),
                set::name('date'),
                set::value($todo->date == FUTURE_TIME ? '' : $todo->date),
                set::disabled($todo->date == FUTURE_TIME),
                on::change('changeDate')
            )
        ),
        formGroup
        (
            setClass('items-center ml-4'),
            checkbox
            (
                setID('switchDate'),
                set::name('switchDate'),
                set::text($lang->todo->periods['future']),
                set::checked($todo->date == FUTURE_TIME),
                on::change('togglePending')
            )
        )
    );
};

/**
 * 构建周期为天的设置。
 * Build setting with cycle of day.
 *
 * @param  object $todo
 * @return mixed
 */
$buildCycleOfDayConfig = function(object $todo): mixed
{
    global $lang;

    return formRow
    (
        setClass('cycle-config cycle-type-detail type-day hidden'),
        formGroup
        (
            set::label($lang->todo->cycleConfig),
            set::required(true),
            set::width('1/3'),
            inputGroup
            (
                setClass('have-fix'),
                span(setClass('input-group-addon justify-center'), $lang->todo->from),
                datePicker(setID('config_date'), set::name('config[date]'), set::value($todo->date ? $todo->date : date('Y-m-d')), on::change('verifyCycleDate'))
            )
        ),
        formGroup
        (
            set::label($lang->todo->every),
            set::required(true),
            setClass('config-day flex items-center highlight-suffix'),
            inputControl
            (
                set::suffix($lang->todo->cycleDay),
                set::suffixWidth('30'),
                input
                (
                    setID('spaceDay'),
                    set::name('config[day]'),
                    set::value(isset($todo->config->day) ? $todo->config->day : ''),
                    on::change('verifySpaceDay')
                )
            )
        )
    );
};

/**
 * 构建周期为周的设置。
 * Build setting with cycle of week.
 *
 * @param  object $todo
 * @return mixed
 */
$buildCycleOfWeekConfig = function(object $todo): mixed
{
    global $lang;

    return formRow
    (
        setClass('cycle-config cycle-type-detail type-week hidden'),
        formGroup
        (
            set::label($lang->todo->cycleConfig),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                setClass('have-fix'),
                span(setClass('input-group-addon'), $lang->todo->weekly),
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'name'     => 'config[week]',
                            'items'    => $lang->todo->dayNames,
                            'value'    => isset($todo->config->week) ? $todo->config->week : 1
                        )
                    )
                )
            )
        )
    );
};

/**
 * 构建周期为月的设置。
 * Build setting with cycle of month.
 *
 * @param  object $todo
 * @return mixed
 */
$buildCycleOfMonthConfig = function(object $todo): mixed
{
    global $lang;

    $days = array();
    for($day = 1; $day <= 31; $day ++) $days[$day] = $day . $lang->todo->day;

    return formRow
    (
        setClass('cycle-config cycle-type-detail type-month hidden'),
        formGroup
        (
            setClass('have-fix'),
            set::label($lang->todo->cycleConfig),
            set::required(true),
            set::width('1/2'),
            inputGroup
            (
                span(setClass('input-group-addon'), $lang->todo->monthly),
                picker(set(array('required' => true, 'id' => 'config_month', 'name' => 'config[month]', 'items' => $days, 'value' => isset($todo->config->month) ? $todo->config->month : '')))
            )
        )
    );
};

/**
 * 构建周期为年的设置。
 * Build setting with cycle of year.
 *
 * @param  object $todo
 * @return mixed
 */
$buildCycleOfYearConfig = function(object $todo): mixed
{
    global $lang;

    $days = array();
    for($day = 1; $day <= 31; $day ++) $days[$day] = $day . $lang->todo->day;

    return formRow
    (
        setClass('cycle-config cycle-type-detail type-year hidden'),
        formGroup
        (
            setClass('have-fix'),
            set::width('1/2'),
            set::label($lang->todo->cycleConfig),
            set::required(true),
            inputGroup
            (
                span(setClass('input-group-addon'), $lang->todo->specify),
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'config_specify_month',
                            'name'     => 'config[specify][month]',
                            'items'    => $lang->datepicker->monthNames,
                            'multiple' => false,
                            'value'    => isset($todo->config->specify->month) ? $todo->config->specify->month : 0
                        )
                    ),
                    on::change('setDays')
                ),
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'specifiedDay',
                            'name'     => 'config[specify][day]',
                            'items'    => $days,
                            'multiple' => false,
                            'value'    => isset($todo->config->specify->day) ? $todo->config->specify->day : 1
                        )
                    )
                )
            )
        )
    );
};

/**
 * 构建生成待办控件。
 * Build generating todo control.
 *
 * @param  object $todo
 * @return mixed
 */
$buildBeforeDays = function($todo): mixed
{
    global $lang;

    return formRow
    (
        setClass('cycle-config'),
        formGroup
        (
            set
            (
                array(
                    'label' => $lang->todo->generate,
                    'class' => 'have-fix highlight-suffix',
                    'width' => '1/3'
                )
            ),
            inputControl
            (
                set::prefix($lang->todo->advance),
                set::prefixWidth('42'),
                input
                (
                    set
                    (
                        array(
                            'class' => 'before-days',
                            'name'  => 'config[beforeDays]',
                            'value' => $todo->config->beforeDays
                        )
                    )
                ),
                to::suffix($lang->todo->cycleDay),
                set::suffixWidth('30')
            )
        )
    );
};

/**
 * 构建周期时间控件。
 * Build deadline control.
 *
 * @param  object $todo
 * @return mixed
 */
$buildDeadline = function($todo): mixed
{
    global $lang;
    return formRow
    (
        setClass('cycle-config'),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->todo->deadline),
            datePicker(setID('config_end'), set::name('config[end]'), set::value(isset($todo->config->end) ? $todo->config->end : ''))
        )
    );
};

/**
 * 构建周期类型。
 * Build cycle type.
 *
 * @param  object $todo
 * @return mixed
 */
$buildCycleType = function(object $todo)
{
    global $lang;

    $cycleTypeOptions = array(
        array('text' => $lang->todo->cycleDay,   'value' => 'day'),
        array('text' => $lang->todo->cycleWeek,  'value' => 'week'),
        array('text' => $lang->todo->cycleMonth, 'value' => 'month'),
        array('text' => $lang->todo->cycleYear,  'value' => 'year')
    );

    $type = '';
    if(isset($todo->config->type)) $type = $todo->config->type == 'day' && isset($todo->config->cycleYear) ? 'year' : $todo->config->type;

    return formRow
    (
        setClass('cycle-config'),
        formGroup
        (
            set::label($lang->todo->cycleType),
            set::required(true),
            radioList
            (
                set
                (
                    array(
                        'name'   => 'config[type]',
                        'id'     => 'cycleType',
                        'value'  => $type,
                        'inline' => true,
                        'items'  => $cycleTypeOptions
                    )
                ),
                on::change('changeCycleType')
            )
        )
    );
};

/**
 * 构建待办类型，用于非周期待办展示。
 * Build todo type for off-cycle todo display.
 *
 * @param  object $todo
 * @return mixed

 */
$buildTodoType = function(object $todo)
{
    global $lang;

    if($todo->cycle) return null;

    return formGroup
    (
        set::width('1/3'),
        set::label($lang->todo->type),
        picker(set(array('required' => true, 'name' => 'type', 'items' => $lang->todo->typeList, 'value' => $todo->type, 'onchange' => 'changeType(this)')))
    );
};

formPanel
(
    set::title(''),
    div
    (
        setClass('flex items-center pb-2.5'),
        span($lang->todo->edit),
        span(setClass('text-lg font-bold ml-3'), $todo->name),
        label(setClass('circle ml-2 label-id px-2'), $todo->id)
    ),
    $buildDateControl($todo),
    $todo->cycle ? fragment
    (
        $buildCycleType($todo),
        $buildCycleOfDayConfig($todo),
        $buildCycleOfWeekConfig($todo),
        $buildCycleOfMonthConfig($todo),
        $buildCycleOfYearConfig($todo),
        $buildBeforeDays($todo),
        $buildDeadline($todo)
    ) : null,
    $buildTodoType($todo),
    formRow
    (
        formGroup
        (
            set(array('width' => '1/3', 'label' => $lang->todo->assignTo)),
            picker
            (
                set
                (
                    array(
                        'required' => true,
                        'items'    => $users,
                        'value'    => $todo->assignedTo,
                        'id'       => 'assignedTo',
                        'name'     => 'assignedTo',
                        'disabled' => $todo->private
                    )
                ),
                on::change('changeAssignedTo()')
            )
        ),
        formGroup
        (
            setClass('items-center ml-4'),
            checkbox
            (
                set
                (
                    array(
                        'id'      => 'private',
                        'name'    => 'private',
                        'text'    => $lang->todo->private,
                        'value'   => 1,
                        'checked' => $todo->private
                    )
                ),
                set::disabled($todo->assignedTo != $app->user->account),
                on::change('togglePrivate(e.target)')
            ),
            btn
            (
                set
                (
                    array(
                        'icon'           => 'help',
                        'data-toggle'    => 'tooltip',
                        'data-placement' => 'top-start',
                        'data-title'     => $lang->todo->privateTip,
                        'square'         => true,
                        'class'          => 'ghost h-6 tooltip-btn'
                    )
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('4/5'),
            set(array('id' => 'nameBox', 'required' => true, 'label' => (($todo->type == 'custom' || $config->vision == 'rnd') ? $lang->todo->name : $lang->todo->objectID), 'class' => 'name-box')),
            div
            (
                setClass('w-full'),
                setID('nameInputBox'),
                input(set(array('id' => 'name', 'name' => 'name', 'value' => $todo->name)))
            )
        ),
        formGroup
        (
            set::width('1/5'),
            setClass('priBox'),
            set::label($lang->todo->pri),
            priPicker(setID('pri'), set::name('pri'), set::items($lang->todo->priList), set::value($todo->pri))
        )
    ),
    formGroup
    (
        set::label($lang->todo->desc),
        setID('desc'),
        editor
        (
            set::name('desc'),
            html($todo->desc)
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->todo->status),
        set::control(array('type' => 'picker', 'id' => 'status', 'name' => 'status', 'items' => $lang->todo->statusList, 'value' => $todo->status))
    ),
    formRow
    (
        setClass('items-center'),
        formGroup
        (
            set::label($lang->todo->beginAndEnd),
            set::width('2/3'),
            inputGroup
            (
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'begin',
                            'name'     => 'begin',
                            'items'    => $times,
                            'value'    => $todo->begin,
                            'disabled' => $todo->begin == 2400
                        )
                    ),
                    on::change('selectNext')
                ),
                span
                (
                    setClass('input-group-addon ring-0'),
                    $lang->todo->timespanTo
                ),
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'end',
                            'name'     => 'end',
                            'items'    => $times,
                            'value'    => $todo->end,
                            'disabled' => $todo->begin == 2400
                        )
                    ),
                    on::change('verifyEndTime')
                )
            )
        ),
        div
        (
            setClass('ml-4 flex items-center'),
            checkbox
            (
                set
                (
                    array(
                        'id'      => 'dateSwitcher',
                        'name'    => 'dateSwitcher',
                        'checked' => $todo->begin == 2400,
                        'text'    => $lang->todo->periods['future']
                    )
                ),
                on::change('switchDateFeature')
            )
        )
    )
);

render();
