<?php
declare(strict_types=1);
/**
 * The batch create view file of todo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('time', $time);
jsVar('times', array_keys($times));
jsVar('userID', $app->user->id);
jsVar('futureDate', FUTURE_TIME);
jsVar('noOptions', $lang->todo->noOptions);
jsVar('moduleList', $config->todo->moduleList);
jsVar('objectsMethod', $config->todo->getUserObjectsMethod);
jsVar('nameBoxLabel', array('custom' => $lang->todo->name, 'objectID' => $lang->todo->objectID));

div
(
    setID('dateCellData'),
    setClass('hidden'),
    inputGroup
    (
        setClass('inited'),
        control
        (
            setClass('time-input begin'),
            set::type('select'),
            set::name('begin'),
            set::required(true),
            set::items($times)
        ),
        control
        (
            setClass('time-input end'),
            set::type('select'),
            set::name('end'),
            set::required(true),
            set::items($times)
        ),
        span
        (
            setClass('input-group-addon'),
            checkBox
            (
                setClass('time-check'),
                set::name('switchTime'),
                $lang->todo->periods['future'],
            )
        )
    ),
);

div
(
    setID('nameInputBox'),
    setClass('hidden'),
    input
    (
        set::name('name'),
        setClass('form-batch-input')
    )
);

$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
formBatchPanel
(
    set::id('batchCreateTodoForm'),
    set::title($lang->todo->batchCreate . $lang->todo->common),
    set::onRenderRow(jsRaw('renderRowData')),

    on::change('[data-name="type"]', 'changeType'),
    on::change('.time-input', 'initTime'),
    on::click('.time-check', "$(event.target).closest('.input-group').find('.time-input').prop('disabled', !!event.target.checked)"),
    on::click('.form-batch-row-actions .btn', 'initTime'),

    set::headingClass('justify-start'),
    to::headingActions
    (
        inputGroup
        (
            span
            (
                setClass('input-group-addon'),
                $lang->todo->date
            ),
            input
            (
                setID('todoDate'),
                set::name('date'),
                set::type('date'),
                set::value($date),
                on::change('window.changFuture')
            ),
            span
            (
                setClass('input-group-addon'),
                checkBox
                (
                    setID('futureDate'),
                    set::name('futureDate'),
                    $lang->todo->periods['future'],
                    on::click('window.changFuture')
                )
            ),
        ),
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('30px'),
    ),
    formBatchItem
    (
        set::name('date'),
        set::label(''),
        set::control('hidden'),
        set::value(date('Y-m-d')),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('type'),
        set::label($lang->todo->type),
        set::width('100px'),
        set::control('select'),
        set::value('custom'),
        set::items($lang->todo->typeList),
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->todo->pri),
        set::width('60px'),
        set::control('select'),
        set::value('3'),
        set::hidden(!isset($visibleFields['pri'])),
        set::items($lang->todo->priList),
    ),
    formBatchItem
    (
        set::name('name'),
        set::label($lang->todo->name),
        set::minWidth('100px'),
    ),
    formBatchItem
    (
        set::name('desc'),
        set::label($lang->todo->desc),
        set::control('textarea'),
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->todo->assignedTo),
        set::value($app->user->account),
        set::ditto(true),
        set::width('140px'),
        set::control('select'),
        set::items($users),
    ),
    formBatchItem
    (
        set::label($lang->todo->beginAndEnd),
        set::width('232px'),
        set::control(false),
        set::name('beginAndEnd'),
        inputGroup
        (
            set::seg(true),
            control
            (
                setClass('time-input'),
                set::type('select'),
                set::name('begin'),
                set::items($times)
            ),
            control
            (
                setClass('time-input'),
                set::type('select'),
                set::name('end'),
                set::items($times)
            ),
            span
            (
                setClass('input-group-addon'),
                checkBox
                (
                    setClass('time-check'),
                    set::name('switchTime'),
                    $lang->todo->periods['future'],
                )
            )
        )
    ),
);

/* ====== Render page ====== */
if(isInModal()) set::size('xl');
render();
