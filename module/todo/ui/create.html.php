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

$timesKeys  = array_keys($times);
$defaultEnd = $timesKeys[(array_search($time, $timesKeys) + 3)];
$isInModal  = isInModal();
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
    set::title($lang->todo->create),
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
                    setID('date'),
                    setClass('date'),
                    set::name('date'),
                    set::value('today'),
                    set::type('datePicker'),
                    on::change('changeCreateDate')
                ),
                div(
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setID('switchDate'),
                        setClass($lang->todo->periods['future']),
                        set::name('switchDate'),
                        set::text($lang->todo->periods['future']),
                        on::change("zui.DatePicker.query('#date').render({disabled: e.target.checked})")
                    )
                ),
                common::hasPriv('todo', 'createcycle') ? div(
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setID('cycle'),
                        set::name('cycle'),
                        set::value('1'),
                        set::text($lang->todo->cycle),
                        on::change('toggleCycle')
                    )
                ) : null
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
                    setID('configDate'),
                    set::type('datePicker'),
                    set::name('config[date]'),
                    setClass('cycle-date'),
                    on::change("e.target.closest('.input-group').classList.toggle('has-error', !e.target.value)")
                )
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
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'config_week',
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
                picker
                (
                    set
                    (
                        array(
                            'required' => true,
                            'id'       => 'config_month',
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
                picker
                (
                    set
                    (
                        array(
                            'id'       => 'config_specify_month',
                            'name'     => 'config[specify][month]',
                            'items'    => $lang->datepicker->monthNames,
                            'multiple' => false,
                            'required' => true,
                            'value'    => 0
                        )
                    ),
                    on::change('setDays')
                ),
                picker
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
            set::label($lang->todo->generate),
            set::className('have-fix highlight-suffix'),
            set::width($isInModal ? '3/5' : '1/3'),
            inputControl
            (
                set::prefix($lang->todo->advance),
                set::prefixWidth('42'),
                input
                (
                    setClass('before-days'),
                    set::name('config[beforeDays]')
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
            datePicker(set::name('config[end]')),
        )
    ),
    formGroup
    (
        set::label($lang->todo->type),
        set::width($isInModal ? '3/5' : '1/3'),
        set::items($lang->todo->typeList),
        set::name('type'),
        set::required(true),
        on::change("loadList(e.target.value, '');")
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
            on::change("$('#private').prop('disabled', e.target.value !== '{$app->user->account}');")
        ),
        formGroup
        (
            setClass('items-center ml-4'),
            checkbox
            (
                setID('private'),
                set::name('private'),
                set::text($lang->todo->private),
                set::value(1),
                on::change("zui.Picker.query('#assignedTo').render({disabled: e.target.checked})")
            ),
            btn
            (
                set::icon('help'),
                toggle::tooltip(array('placement' => 'right', 'title' => $lang->todo->privateTip, 'type' => 'white', 'class-name' => 'text-gray border border-light')),
                set::square(true),
                setClass('ghost h-6 mt-0.5 tooltip-btn')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('5/6'),
            set(array('id' => 'nameBox', 'required' => true, 'label' => $lang->todo->name, 'class' => 'name-box')),
            div
            (
                setID('nameInputBox'),
                setClass('w-full'),
                input(set(array('id' => 'name', 'name' => 'name')))
            )
        ),
        formGroup
        (
            set::width('1/5'),
            setClass('priBox'),
            set::label($lang->todo->pri),
            priPicker
            (
                setID('pri'),
                set::name('pri'),
                set::items($lang->todo->priList),
                set::value(3),
                set::required(true)
            )
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
        setID('status'),
        set::width('1/3'),
        set::label($lang->todo->status),
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
            set::width('1/3'),
            inputGroup
            (
                picker
                (
                    set::id('begin'),
                    set::name('begin'),
                    set::required(true),
                    set::items($times),
                    set::value(date('Y-m-d') != $date ? $timesKeys[0] : $time),
                    on::change('selectNext()')
                ),
                inputGroupAddon($lang->todo->timespanTo),
                picker
                (
                    set::id('end'),
                    set::name('end'),
                    set::required(true),
                    set::items($times),
                    set::value(date('Y-m-d') != $date ? $timesKeys[3] : $defaultEnd),
                    on::change('verifyEndTime')
                )
            )
        ),
        div
        (
            setClass('ml-4 flex items-center'),
            checkbox
            (
                set(array('id' => 'switchTime', 'name' => 'switchTime', 'text' => $lang->todo->periods['future'])),
                on::change('switchDateFeature')
            )
        )
    )
);

render();
