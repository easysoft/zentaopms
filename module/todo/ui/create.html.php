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

jsVar('noTodo', $lang->todo->noTodo);
jsVar('moduleList', $config->todo->moduleList);
jsVar('objectsMethod', $config->todo->getUserObjectsMethod);
jsVar('nameBoxLabel', array('custom' => $lang->todo->name, 'idvalue' => isset($lang->todo->idvalue) ? $lang->todo->idvalue : null));
jsVar('vision', $config->vision);
jsVar('noOptions', $lang->todo->noOptions);
jsVar('chosenType', $lang->todo->typeList);
jsVar('today', date('Y-m-d'));
jsVar('nowTime', $time);
jsVar('start', key($times));
jsVar('userID', $app->user->id);
jsVar('defaultType', '');

/**
 * 构建周期设置的天的标签内容，待办为周期类型进行展示。
 * Build tab-pane content of day.
 *
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildDayPane(): mixed
{
    global $lang, $app;

    return fragment
    (
        inputGroup
        (
            set::class('every'),
            span
            (
                set::class('input-group-addon'),
                $lang->todo->every
            ),

            input
            (
                set::id('everyInput'),
                set::name('config[day]')
            ),
            span
            (
                set::class('input-group-addon'),
                $lang->todo->cycleDay
            ),
            div
            (
                set::class('pl-3 flex items-center input-group-addon every-checkbox'),
                checkbox
                (
                    set::id('configSpecify'),
                    set::name('config[specifiedDate]'),
                    set::text($lang->todo->specify),
                    set::value(1),
                    on::change('showSpecifiedDate(this)')
                )
            )
        ),

        inputGroup
        (
            set::class('specify hidden'),
            span
            (
                set::class('input-group-addon'),
                $lang->todo->specify
            ),
            select
            (
                set::id('config[specify][month]'),
                set::name('config[specify][month]'),
                set::items($lang->datepicker->monthNames),
                set::value(0),
                set::multiple(false),
                on::change('setDays(this.value)')
            ),
            select
            (
                set::id('specifiedDay'),
                set::name('config[specify][day]'),
                set::items($lang->todo->specifiedDay),
                set::multiple(false),
                set::value(1)
            ),
            span
            (
                set::class('input-group-addon', strpos($app->getClientLang(), 'zh') !== false ? '' : 'hidden'),
                $lang->todo->day
            ),
            div
            (
                set::class('w-36 pl-3 flex items-center gap-3 input-group-addon'),
                checkbox
                (
                    set::id('cycleYear'),
                    set::name('config[cycleYear]'),
                    set::value(1),
                    set::text($lang->todo->everyYear)
                ),
                checkbox
                (
                    set::id('configEvery'),
                    set::name('configEvery'),
                    set::value(1),
                    set::text($lang->todo->every),
                    on::change('showEvery(this)')
                )
            )
        )
    );
}

/**
 * 构建月的标签页，用于周期设置。
 * Build tab-pane content of month.
 *
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */

function buildMonthDays(): mixed
{
    $days = array_combine(range(1,31), range(1,31));

    return checkList
    (
        set::class('flex-wrap gap-4'),
        set::name('config[month]'),
        set::inline(true),
        set::items($days),
    );
}

/**
 * 构建周期设置的标签导航，待办为周期类型进行展示。
 * Build navTabs header for cycle.
 *
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */

function buildNavTabsBar(): mixed
{
    global $lang;

    $navTabs = [
        [
            'type'  => 'day',
            'text'  => $lang->todo->cycleDay,
            'class' => ' active'
        ],
        [
            'type'  => 'week',
            'text'  => $lang->todo->cycleWeek,
            'class' => ''
        ],
        [
            'type'  => 'month',
            'text'  => $lang->todo->cycleMonth,
            'class' => '',
        ],
    ];

    $nav = ul(set::class('nav nav-tabs'));
    foreach($navTabs as $tab)
    {
        $nav->add(
            li
            (
                set::class('nav-item'. $tab['class']),
                a
                (
                    set::href('#' . $tab['type']),
                    set::class($tab['class']),
                    set('data-toggle', 'tab'),
                    $tab['text'],
                    on::click('toggleNavTabs(this)')
                )
            ),
        );
    }

    return $nav;
}

/**
 * 构建周期设置的标签页，当待办为周期时进行展示。
 * Build navTabs for cycle.
 *
 * @return mixed Any type supported by zin widget function 任何 zin 部件函数参数支持的类型。
 */
function buildNavTabs(): mixed
{
    global $lang;

    return div
    (
        set::class('w-full'),
        buildNavTabsBar(),
        div
        (
            set::class('tab-content'),
            div
            (
                set::class('tab-pane active'),
                set::id('day'),
                buildDayPane(),
            ),
            div
            (
                set::class('tab-pane'),
                set::id('week'),
                checkList
                (
                    set::primary(true),
                    set::id('config[week]'),
                    set::name('config[week]'),
                    set::inline(true),
                    set::items($lang->todo->dayNames),
                ),
            ),
            div
            (
                set::class('tab-pane h-28'),
                set::id('month'),
                buildMonthDays(),
            ),
            inputGroup
            (
                div
                (
                    set::class('input-group-addon'),
                    '提前',
                ),
                input
                (
                    set::id('name'),
                    set::name('config[beforeDays]'),
                    set::value(0),
                ),
                div
                (
                    set::class('input-group-addon'),
                    '天生成待办',
                ),
            ),

        ),
    );
}

formPanel
(
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->todo->date),
            set::strong(true),
            set::class('align-center'),
            inputGroup
            (
                input
                (
                    set::class('date'),
                    set::name('date'),
                    set::value(date('Y-m-d')),
                    set::type('date'),
                    set::width('1/3'),
                    on::change('changeCreateDate(this)')
                ),
                div
                (
                    set::class('flex items-center gap-3 pl-3 input-group-addon'),
                    checkbox
                    (
                        set::id('switchDate'),
                        set::name('switchDate'),
                        set::text($lang->todo->periods['future']),
                        set::width('100px'),
                        on::change('toggleDateTodo(this)')
                    ),
                    checkbox
                    (
                        set::id('cycle'),
                        set::name('cycle'),
                        set::value(1),
                        set::text($lang->todo->cycle),
                        on::change('toggleCycle(this)')
                    )
                )

            )

        )
    ),
    formRow
    (
        set::class('cycle-config hidden'),
        formGroup
        (
            set::label($lang->todo->cycleConfig),
            set::strong(true),
            buildNavTabs()
        )
    ),
    formRow
    (
        set::class('cycle-config hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->todo->deadline),
            set::strong(true),
            input
            (
                set::type('date'),
                set::name('config[end]')
            )
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('type'),
        set::strong(true),
        set::label($lang->todo->type),
        set::items($lang->todo->typeList),
        on::change('changeType(this)'),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('assignedTo'),
        set::strong(true),
        set::label($lang->todo->assignTo),
        set::items($users),
        set::value($app->user->account)
    ),
    formRow
    (
        formGroup
        (
            set::id('nameBox'),
            set::class('name-box'),
            set::label($lang->todo->name),
            set::strong(true),
            set::required(true),
            inputGroup
            (
                set::class('title-group'),
                div
                (
                    set::id('nameInputBox'),
                    input
                    (
                        set::id('name'),
                        set::name('name')
                    )
                ),
                div
                (
                    set::class('input-group-addon fix-border br-0'),
                    $lang->todo->pri
                ),
                select
                (
                    set::class('w-20'),
                    set::id('pri'),
                    set::name('pri'),
                    set::items($lang->todo->priList),
                    set::value(3)
                )
            )
        )
    ),

    formGroup
    (
        set::name('desc'),
        set::strong(true),
        set::type('textarea'),
        set::label($lang->todo->desc)
    ),
    formGroup
    (
        set::width('1/2'),
        set::id('status'),
        set::name('status'),
        set::items($lang->todo->statusList),
        set::label($lang->todo->status),
        set::strong(true)
    ),
    formRow
    (
        set::class('items-center'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->todo->beginAndEnd),
            set::strong(true),
            inputGroup
            (
                select
                (
                    set::id('begin'),
                    set::name('begin'),
                    set::items($times),
                    set::value(date('Y-m-d') != $date ? key($times) : $time),
                    on::change('selectNext()')
                ),
                select
                (
                    set::id('end'),
                    set::name('end'),
                    set::items($times)
                )
            ),
            div
            (
                set::class('ml-3 flex items-center switch-time'),
                checkbox
                (
                    set::id('switchTime'),
                    set::name('switchTime'),
                    set::text($lang->todo->lblDisableDate),
                    on::change('switchDateFeature(this)')
                )
            )

        )
    ),
    formGroup
    (
        set::label($lang->todo->private),
        set::strong(true),
        set::class('private-row'),
        checkbox
        (
            set::id('private'),
            set::name('private'),
            set::value(1)
        )
    )
);

render();
