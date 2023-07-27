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
jsVar('nameBoxLabel', array('custom' => $lang->todo->name, 'objectID' => $lang->todo->objectID));
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

$isInModal = isInModal();
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
                    'width' => $isInModal ? '3/5' : '1/3'
                )
            ),
            inputGroup
            (
                set::seg(true),
                control
                (
                    set::id('date'),
                    set::name('date'),
                    set::class('date'),
                    set::value('today'),
                    set::type('date'),
                    on::change('changeCreateDate')
                ),
                div(
                    setClass('input-group-addon'),
                    checkbox
                    (
                        set::id('switchDate'),
                        set::name('switchDate'),
                        set::text($lang->todo->periods['future']),
                        set::class($lang->todo->periods['future']),
                        on::change("zui.DatePicker.query('#date').render({disabled: e.target.checked})")
                    )
                ),
                div(
                    setClass('input-group-addon'),
                    checkbox
                    (
                        set::id('cycle'),
                        set::id('cycle'),
                        set::value('1'),
                        set::text($lang->todo->cycle),
                        on::change('toggleCycle')
                    )
                )
            )
        ),
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
            set::label($lang->todo->cycleConfig),
            set::required(true),
            set::width('9/24'),
            inputGroup
            (
                setClass('have-fix'),
                inputGroupAddon
                (
                    setClass('justify-center'),
                    $lang->todo->from
                ),
                control
                (
                    set::type('date'),
                    set::id('configDate'),
                    set::name('config[date]'),
                    set::class('cycle-date'),
                    on::change("e.target.closest('.input-group').classList.toggle('has-error', !e.target.value)")
                ),
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
                    'width'    => $isInModal ? '3/5' : '1/3'
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
                    'width'    => $isInModal ? '3/5' : '1/3'
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
                    'width'    => $isInModal ? '3/5' : '1/3'
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
                    on::change('setDays')
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
                    'width' => $isInModal ? '3/5' : '1/3'
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
            set::label($lang->todo->deadline),
            set::width($isInModal ? '3/5' : '1/3'),
            set::type('date'),
            set::name('config[end]'),
        )
    ),
    formGroup
    (
        set::label($lang->todo->type),
        set::width($isInModal ? '3/5' : '1/3'),
        set::items($lang->todo->typeList),
        set::name('type'),
        set::required(true),
        on::change("loadList(e.target.value, '');"),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->todo->assignTo),
            set::required(true),
            set::items($users),
            set::value($app->user->account),
            set::name('assignedTo'),
            on::change("$('#private').prop('disabled', e.target.value !== '{$app->user->account}');"),
        ),
        formGroup
        (
            setClass('items-center ml-4'),
            checkbox
            (
                set::name('private'),
                set::id('private'),
                set::text($lang->todo->private),
                set::value(1),
                on::change("zui.Picker.query('#assignedTo').render({disabled: e.target.checked})")
            ),
            btn
            (
                set::icon('help'),
                toggle::tooltip(array('placement' => 'top-start', 'title' => $lang->todo->privateTip)),
                set::square(true),
                set::class('ghost h-6 mt-0.5 tooltip-btn'),
            ),
        )
    ),
    formRow
    (
        formGroup
        (
            set::id('nameBox'),
            set::class('name-box'),
            set::required(true),
            set::label($lang->todo->name),
            set::name('name')
        ),
        formGroup
        (
            set::label($lang->todo->pri),
            set::labelWidth('80px'),
            set::name('pri'),
            set::width(40),
            set::required(true),
            set::value(3),
            set::control('pri')
        )
    ),
    formGroup
    (
        set::label($lang->todo->desc),
        control
        (
            set::name('desc'),
            set::type('editor'),
            set::value(isset($desc) ? $desc : ''),
            set::rows('5')
        )
    ),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->todo->status),
        set::id('status'),
        set::name('status'),
        set::items($lang->todo->statusList),
        set::required(true)
    ),
    formRow
    (
        setClass('items-center'),
        formGroup
        (
            set::label($lang->todo->beginAndEnd),
            set::width('1/2'),
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
                    $lang->todo->timespanTo
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
