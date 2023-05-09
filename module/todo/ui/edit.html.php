<?php
declare(strict_types=1);
/**
 * The ui file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('idvalue', isset($todo->idvalue) ? $todo->idvalue : null);
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
    jsVar('cycleType', isset($todo->config->type) ? $todo->config->type : '');

    if(isset($todo->config->month))
    {
        $todo->config->month = explode(',', $todo->config->month);
        $todo->config->month = array_map('intval', $todo->config->month);
    }
    if(isset($todo->config->week))
    {
        $todo->config->week = explode(',', $todo->config->week);
        $todo->config->week = array_map('intval', $todo->config->week);
    }
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
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildDateControl(object $todo): mixed
{
    global $lang;

    if($todo->cycle) return null;

    return formRow
    (
        set::class('items-center'),
        formGroup
        (
            set(array
            (
                'label'  => $lang->todo->date,
                'width'  => '1/3'
            )),
            control
            (
                set(array
                (
                    'name'  => 'date',
                    'class' => 'date',
                    'value' => $todo->date,
                    'type'  => 'date',
                    'width' => '1/4'
                )),
                on::change('changeDate(this)')
            ),
        ),
        formGroup
        (
            set::class('items-center ml-3'),

            checkbox
            (
                set(array
                (
                    'id'      => 'switchDate',
                    'name'    => 'switchDate',
                    'text'    => $lang->todo->periods['future'],
                    'width'   => '100px',
                    'checked' => !isset($todo->date)
                )),
                on::change('togglePending(this)')
            )
        )

    );
}

/**
 * 构建周期为天的设置。
 * Build setting with cycle of day.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleOfDayConfig(object $todo): mixed
{
    global $lang;
    return formRow
    (
        set::class('cycle-config cycle-type-detail type-day hidden'),

        formGroup
        (
            set(array
            (
                'label'    => $lang->todo->cycleConfig,
                'required' => true,
                'width'    => '1/3'
            )),
            inputGroup
            (

                set::class('have-fix'),
                span
                (
                    set::class('input-group-addon justify-center'),
                    $lang->todo->from
                ),
                input
                (
                    set(array
                    (
                        'id'    => 'date',
                        'name'  => 'date',
                        'type'  => 'date',
                        'value' => $todo->date
                    )),
                    on::blur('dateBlur(this)')
                )
            )
        ),
        div
        (
            set::class('config-day flex items-center highlight-suffix'),
            span
            (
                set::class('input-group-addon ring-0 bg-white'),
                $lang->todo->every
            ),

            inputControl
            (

                set::suffix($lang->todo->cycleDay),
                set::suffixWidth('30'),
                input
                (
                    set::id('everyInput'),
                    set::name('config[day]'),
                    set::value(isset($todo->config->day) ? $todo->config->day : ''),
                    on::blur('everyInputBlur(this)')
                )
            )
        )
    );
}

/**
 * 构建周期为周的设置。
 * Build setting with cycle of week.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleOfWeekConfig(object $todo): mixed
{
    global $lang;

    return formRow
    (
        set::class('cycle-config cycle-type-detail type-week hidden'),

        formGroup
        (
            set(array
            (
                'label'    => $lang->todo->cycleConfig,
                'required' => true,
                'width'    => '1/2'
            )),
            inputGroup
            (
                set::class('have-fix'),
                span
                (
                    set::class('input-group-addon'),
                    $lang->todo->weekly
                ),
                select(set(array
                (
                    'name'  => 'config[week]',
                    'items' => $lang->todo->dayNames,
                    'value' => isset($todo->config->week) ? $todo->config->week : 1
                )))
            )
        )
    );
}

/**
 * 构建周期为月的设置。
 * Build setting with cycle of month.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleOfMonthConfig(object $todo): mixed
{
    global $lang;

    $days = array();
    for($day = 1; $day <= 31; $day ++) $days[$day] = $day . $lang->todo->day;

    return formRow
    (
        set::class('cycle-config cycle-type-detail type-month hidden'),

        formGroup
        (
            set(array
            (
                'label'    => $lang->todo->cycleConfig,
                'required' => true,
                'class'    => 'have-fix',
                'width'    => '1/2'
            )),
            inputGroup
            (

                span
                (
                    set::class('input-group-addon'),
                    $lang->todo->monthly
                ),
                select(set(array
                (
                    'id'    => 'config[month]',
                    'name'  => 'config[month]',
                    'items' => $days,
                    'value' => isset($todo->config->month) ? $todo->config->month : ''
                )))
            )
        )
    );
}

/**
 * 构建周期为年的设置。
 * Build setting with cycle of year.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleOfYearConfig(object $todo): mixed
{
    global $lang;

    $days = array();
    for($day = 1; $day <= 31; $day ++) $days[$day] = $day . $lang->todo->day;

    return formRow
    (
        set::class('cycle-config cycle-type-detail type-year hidden'),

        formGroup
        (
            set(array
            (
                'label'    => $lang->todo->cycleConfig,
                'required' => true,
                'class'    => 'have-fix',
                'width'    => '1/2'
            )),
            inputGroup
            (
                span
                (
                    set::class('input-group-addon'),
                    $lang->todo->specify
                ),
                select
                (
                    set(array
                    (
                        'id'       => 'config[specify][month]',
                        'name'     => 'config[specify][month]',
                        'items'    => $lang->datepicker->monthNames,
                        'multiple' => false,
                        'value'    => isset($todo->config->specify->month) ? $todo->config->specify->month : 0
                    )),
                    on::change('setDays(this.value)')
                ),
                select(set(array
                (
                    'id'       => 'specifiedDay',
                    'name'     => 'config[specify][day]',
                    'items'    => $days,
                    'multiple' => false,
                    'value'    => isset($todo->config->specify->day) ? $todo->config->specify->day : 1
                )))
            )
        )
    );

}

/**
 * 构建生成待办控件。
 * Build generating todo control.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildBeforeDays($todo): mixed
{
    global $lang;

    return formRow
    (
        set::class('cycle-config'),
        formGroup
        (
            set(array
            (
                'label'  => $lang->todo->generate,
                'class'  => 'have-fix highlight-suffix',
                'width'  => '1/3'
            )),
            inputControl
            (
                set::prefix($lang->todo->advance),
                set::prefixWidth('42'),
                input(set(array
                (
                    'class' => 'before-days',
                    'name'  => 'config[beforeDays]',
                    'value' => $todo->config->beforeDays
                ))),
                to::suffix($lang->todo->cycleDay),
                set::suffixWidth('30')
            )
        )
    );

}

/**
 * 构建周期时间控件。
 * Build deadline control.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildDeadline($todo): mixed
{
    global $lang;
    return formRow
    (
        set::class('cycle-config'),
        formGroup
        (
            set(array
            (
                'label'  => $lang->todo->deadline,
                'width'  => '1/3',
            )),
            input(set(array
            (
                'type'  => 'date',
                'name'  => 'config[end]',
                'value' => isset($todo->config->end) ? $todo->config->end : 0
            )))
        )

    );
}

/**
 * 构建周期类型。
 * Build cycle type.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleType(object $todo)
{
    global $lang;

    $cycleTypeOptions = array
    (
        array('text' => $lang->todo->cycleDay,   'value' => 'day'),
        array('text' => $lang->todo->cycleWeek,  'value' => 'week'),
        array('text' => $lang->todo->cycleMonth, 'value' => 'month'),
        array('text' => $lang->todo->cycleYear,  'value' => 'year')
    );
    return formRow
    (
        set::class('cycle-config'),
        formGroup
        (
            set::label($lang->todo->cycleType),
            set::required(true),
            radioList
            (
                set(array
                (
                    'name'   => 'config[type]',
                    'id'     => 'cycleType',
                    'value'  => isset($todo->config->type) ? $todo->config->type : '',
                    'inline' => true,
                    'items'  => $cycleTypeOptions
                )),
                on::change('changeCycleType')
            )
        )
    );

}

/**
 * 构建周期类型和设置。
 * Build cycle types and settings.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildCycleConfig(object $todo): mixed
{
    global $lang;

    if(!$todo->cycle) return null;

    return fragment
    (
        buildCycleType($todo),
        buildCycleOfDayConfig($todo),
        buildCycleOfWeekConfig($todo),
        buildCycleOfMonthConfig($todo),
        buildCycleOfYearConfig($todo),
        buildDeadline($todo)
    );
}

/**
 * 构建待办类型，用于非周期待办展示。
 * Build todo type for off-cycle todo display.
 *
 * @param  object $todo
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildTodoType(object $todo)
{
    global $lang;

    if($todo->cycle) return null;

    return formGroup
    (
        set(array
        (
            'label'  => $lang->todo->type,
            'name'   => 'type',
            'width'  => '1/3',
            'items'  => $lang->todo->typeList,
            'value'  => $todo->type
        )),
        on::change('changeType(this)')
    );
}
formPanel
(
    set::title(''),
    div(
        set::class('flex items-center pb-2.5'),
        span($lang->todo->edit),
        span
        (
            set::class('text-lg font-bold ml-3'),
            $todo->name,
        ),
        label
        (
            $todo->id,
            setClass('circle ml-2 label-id px-2')
        )
    ),
    buildDateControl($todo),
    buildCycleConfig($todo),
    buildTodoType($todo),
    formRow
    (
        formGroup
        (
            set(array
            (
                'width' => '1/3',
                'label' => $lang->todo->assignTo,
            )),
            select
            (
                set(array
                (
                    'items'    => $users,
                    'value'    => $todo->assignedTo,
                    'id'       => 'assignedTo',
                    'name'     => 'assignedTo',
                    'disabled' => $todo->private
                )),
                on::change('changeAssignedTo()')
            )
        ),

        formGroup
        (
            set::class('items-center ml-3'),
            checkbox
            (
                set(array
                (
                    'id'      => 'private',
                    'name'    => 'private',
                    'text'    => $lang->todo->private,
                    'value'   => 1,
                    'checked' => $todo->private,
                )),
                on::change('togglePrivate(this)')
            ),
            btn(set(array
            (
                'icon'           => 'help',
                'data-toggle'    => 'tooltip',
                'data-placement' => 'top-start',
                'href'           => 'privateTip',
                'square'         => true,
                'class'          => 'ghost h-6 tooltip-btn'
            ))),
            div
            (
                set::id('privateTip'),
                set::class('tooltip darker'),
                $lang->todo->privateTip
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set(array
            (
                'id'       => 'nameBox',
                'required' => true,
                'label'    => $lang->todo->name,
                'class'    => 'name-box'
            )),
            inputGroup
            (
                set::class('title-group'),
                div
                (
                    set::id('nameInputBox'),
                    input(set(array
                    (
                        'id'    => 'name',
                        'name'  => 'name',
                        'value' => $todo->name
                    )))
                ),
                div
                (
                    set::class('input-group-addon fix-border br-0'),
                    $lang->todo->pri
                ),
                select(set(array
                (
                    'class' => 'w-20',
                    'id'    => 'pri',
                    'name'  => 'pri',
                    'items' => $lang->todo->priList,
                    'value' => $todo->pri
                )))
            )
        )
    ),

    formGroup(set(array
    (
        'name'   => 'desc',
        'type'   => 'editor',
        'label'  => $lang->todo->desc,
        'value'  => htmlSpecialString($todo->desc)
    ))),
    formGroup(set(array
    (
        'width' => '1/3',
        'id'    => 'status',
        'name'  => 'status',
        'items' => $lang->todo->statusList,
        'label' => $lang->todo->status,
        'value' => $todo->status,
    ))),
    formRow
    (
        set::class('items-center'),
        formGroup
        (
            set::label($lang->todo->beginAndEnd),
            set::width('1/3'),
            inputGroup
            (
                select
                (
                    set(array
                    (
                        'id'       => 'begin',
                        'name'     => 'begin',
                        'items'    => $times,
                        'value'    => $todo->begin,
                        'disabled' => $todo->begin == 2400
                    )),
                    on::change('selectNext()')
                ),
                span
                (
                    set::class('input-group-addon ring-0'),
                    $lang->todo->to
                ),
                select
                (
                    set(array
                    (
                        'id'       => 'end',
                        'name'     => 'end',
                        'items'    => $times,
                        'value'    => $todo->end,
                        'disabled' => $todo->begin == 2400
                    )),
                    on::blur('selectEndTime(this)')
                )
            ),

        ),
        div
        (
            set::class('ml-3 flex items-center'),
            checkbox
            (
                set(array
                (
                    'id'      => 'dateSwitcher',
                    'name'    => 'dateSwitcher',
                    'checked' => $todo->begin == 2400,
                    'text'    => $lang->todo->lblDisableDate
                )),
                on::change('switchDateFeature(this)')
            )
        )

    )
);

render();
