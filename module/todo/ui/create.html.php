<?php
declare(strict_types=1);
/**
 * The create ui file of todo module of ZenTaoPMS.
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
jsVar('userAccount', $app->user->account);
jsVar('defaultDate', date('Y-m-d'));

$cycleTypeOptions = array(
    array('text' => $lang->todo->cycleDay,   'value' => 'day'),
    array('text' => $lang->todo->cycleWeek,  'value' => 'week'),
    array('text' => $lang->todo->cycleMonth, 'value' => 'month'),
    array('text' => $lang->todo->cycleYear,  'value' => 'year')
);
$days = array();
for($day = 1; $day <= 31; $day ++) $days[$day] = $day . $lang->todo->day;

formPanel
(
    set::title(''),
    div
    (
        setClass('text-lg pb-2.5'),
        $lang->todo->create
    ),
    formRow
    (
        setClass('items-center'),
        formGroup
        (
            set
            (
                array(
                    'label' => $lang->todo->date,
                    'class' => 'items-center',
                    'width' => '1/3'
                )
            ),
            inputGroup
            (
                setClass('input-control has-suffix'),
                control
                (
                    set
                    (
                        array(
                            'name'  => 'date',
                            'class' => 'date',
                            'value' => date('Y-m-d'),
                            'type'  => 'date',
                            'width' => '1/4'
                        )
                    ),
                    on::change('changeCreateDate(this)')
                ),
                div
                (
                    setClass('input-control-suffix opacity-100 bg-white z-10'),
                    checkbox
                    (
                        set
                        (
                            array(
                                'id'    => 'switchDate',
                                'name'  => 'switchDate',
                                'text'  => $lang->todo->periods['future'],
                                'width' => '100px'
                            )
                        ),
                        on::change('togglePending(this)')
                    )
                )
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
                        'id'    => 'cycle',
                        'name'  => 'cycle',
                        'value' => 1,
                        'text'  => $lang->todo->cycle
                    )
                ),
                on::change('toggleCycle(this)')
            )
        )
    ),
    formRow
    (
        setClass('cycle-config hidden'),
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
                        'value'  => 'day',
                        'inline' => true,
                        'items'  => $cycleTypeOptions
                    )
                ),
                on::change('changeCycleType')
            )
        )
    ),
    formRow
    (
        setClass('cycle-config cycle-type-detail type-day hidden'),
        formGroup
        (
            set
            (
                array(
                    'label'    => $lang->todo->cycleConfig,
                    'required' => true,
                    'width'    => '1/3'
                )
            ),
            inputGroup
            (
                setClass('have-fix'),
                span
                (
                    setClass('input-group-addon justify-center'),
                    $lang->todo->from
                ),
                input
                (
                    set
                    (
                        array(
                            'class' => 'cycle-date',
                            'name'  => 'config[date]',
                            'type'  => 'date'
                        )
                    ),
                    on::blur('verifyCycleDate(this)')
                )
            )
        ),
        div
        (
            setClass('config-day flex items-center highlight-suffix'),
            span
            (
                setClass('input-group-addon pl-4 ring-0 bg-white'),
                $lang->todo->every
            ),
            inputControl
            (
                set::suffix($lang->todo->cycleDay),
                set::suffixWidth('30'),
                input
                (
                    setID('spaceDay'),
                    set::name('config[day]'),
                    on::blur('verifySpaceDay(this)')
                )
            )
        )
    ),
    formRow
    (
        setClass('cycle-config cycle-type-detail type-week hidden'),
        formGroup
        (
            set
            (
                array(
                    'label'    => $lang->todo->cycleConfig,
                    'required' => true,
                    'width'    => '1/2'
                )
            ),
            inputGroup
            (
                setClass('have-fix'),
                span
                (
                    setClass('input-group-addon'),
                    $lang->todo->weekly
                ),
                select
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'config[week]',
                            'name'     => 'config[week]',
                            'items'    => $lang->todo->dayNames,
                            'value'    => 1
                        )
                    )
                )
            )
        )
    ),
    formRow
    (
        setClass('cycle-config cycle-type-detail type-month hidden'),
        formGroup
        (
            set
            (
                array(
                    'label'    => $lang->todo->cycleConfig,
                    'required' => true,
                    'class'    => 'have-fix',
                    'width'    => '1/2'
                )
            ),
            inputGroup
            (
                span
                (
                    setClass('input-group-addon'),
                    $lang->todo->monthly
                ),
                select
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'config[month]',
                            'name'     => 'config[month]',
                            'items'    => $days,
                            'value'    => 1
                        )
                    )
                )
            )
        )
    ),
    formRow
    (
        setClass('cycle-config cycle-type-detail type-year hidden'),
        formGroup
        (
            set
            (
                array(
                    'label'    => $lang->todo->cycleConfig,
                    'required' => true,
                    'class'    => 'have-fix',
                    'width'    => '1/2'
                )
            ),
            inputGroup
            (
                span
                (
                    setClass('input-group-addon'),
                    $lang->todo->specify
                ),
                select
                (
                    set
                    (
                        array(
                            'id'       => 'config[specify][month]',
                            'name'     => 'config[specify][month]',
                            'items'    => $lang->datepicker->monthNames,
                            'multiple' => false,
                            'required' => true,
                            'value'    => 0
                        )
                    ),
                    on::change('setDays(this.value)')
                ),
                select
                (
                    set
                    (
                        array(
                            'id'       => 'specifiedDay',
                            'name'     => 'config[specify][day]',
                            'items'    => $days,
                            'multiple' => false,
                            'required' => true,
                            'value'    => 1
                        )
                    )
                )
            )
        )
    ),
    formRow
    (
        setClass('cycle-config hidden'),
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
                            'name'  => 'config[beforeDays]'
                        )
                    )
                ),
                to::suffix($lang->todo->cycleDay),
                set::suffixWidth('30')
            )
        )
    ),
    formRow
    (
        setClass('cycle-config hidden'),
        formGroup
        (
            set
            (
                array(
                    'label'  => $lang->todo->deadline,
                    'width'  => '1/3'
                )
            ),
            input
            (
                set
                (
                    array(
                        'type' => 'date',
                        'name' => 'config[end]'
                    )
                )
            )
        )
    ),
    formGroup
    (
        set
        (
            array(
                'label' => $lang->todo->type,
                'width' => '1/3',
            )
        ),
        select
        (
            set
            (
                array(
                    'name'     => 'type',
                    'items'    => $lang->todo->typeList,
                    'required' => true
                )
            )
        ),
        on::change('changeType(this)'),
    ),
    formRow
    (
        formGroup
        (
            set
            (
                array(
                    'width' => '1/3',
                    'label' => $lang->todo->assignTo
                )
            ),
            select
            (
                set
                (
                    array(
                        'required' => true,
                        'items'    => $users,
                        'value'    => $app->user->account,
                        'id'       => 'assignedTo',
                        'name'     => 'assignedTo'
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
                        'id'    => 'private',
                        'name'  => 'private',
                        'text'  => $lang->todo->private,
                        'value' => 1
                    )
                ),
                on::change('togglePrivate(this)')
            ),
            btn
            (
                set
                (
                    array(
                        'icon'           => 'help',
                        'data-toggle'    => 'tooltip',
                        'data-placement' => 'top-start',
                        'href'           => 'privateTip',
                        'square'         => true,
                        'class'          => 'ghost h-6 tooltip-btn'
                    )
                )
            ),
            div
            (
                setID('privateTip'),
                setClass('tooltip darker'),
                $lang->todo->privateTip
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set
            (
                array(
                    'id'       => 'nameBox',
                    'class'    => 'name-box',
                    'required' => true,
                    'label'    => $lang->todo->name
                )
            ),
            inputGroup
            (
                setClass('title-group'),
                div
                (
                    setID('nameInputBox'),
                    input
                    (
                        setID('name'),
                        set::name('name')
                    )
                ),
                div
                (
                    setClass('input-group-addon pl-4 bg-white fix-border br-0'),
                    $lang->todo->pri
                ),
                select
                (
                    set
                    (
                        array(
                            'class'    => 'w-20',
                            'id'       => 'pri',
                            'name'     => 'pri',
                            'items'    => $lang->todo->priList,
                            'required' => true,
                            'value'    => 3
                        )
                    )
                )
            )
        )
    ),
    formGroup
    (
        set
        (
            array(
                'name'  => 'desc',
                'type'  => 'editor',
                'label' => $lang->todo->desc,
                'value' => isset($desc) ? $desc : ''
            )
        )
    ),
    formGroup
    (
        set
        (
            array(
                'width' => '1/3',
                'label' => $lang->todo->status,
            )
        ),
        select
        (
            set
            (
                array(
                    'id'       => 'status',
                    'name'     => 'status',
                    'items'    => $lang->todo->statusList,
                    'required' => true
                )
            )
        )
    ),
    formRow
    (
        setClass('items-center'),
        formGroup
        (
            set::label($lang->todo->beginAndEnd),
            set::width('1/3'),
            inputGroup
            (
                select
                (
                    set
                    (
                        array(
                            'id'       => 'begin',
                            'name'     => 'begin',
                            'required' => true,
                            'items'    => $times,
                            'value'    => date('Y-m-d') != $date ? key($times) : $time
                        )
                    ),
                    on::change('selectNext()')
                ),
                span
                (
                    setClass('input-group-addon ring-0'),
                    $lang->todo->to
                ),
                select
                (
                    set
                    (
                        array(
                            'id'       => 'end',
                            'name'     => 'end',
                            'required' => true,
                            'items'    => $times
                        )
                    ),
                    on::blur('verifyEndTime(this)')
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
                        'id'   => 'switchTime',
                        'name' => 'switchTime',
                        'text' => $lang->todo->periods['future']
                    )
                ),
                on::change('switchDateFeature(this)')
            )
        )
    )
);

render();
