<?php
declare(strict_types=1);
/**
 * The todoedit view file of todo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */
namespace zin;

$nameCellData = array();
foreach($lang->todo->typeList as $type => $typeName)
{
    $itemName = $type == 'story' ? 'stories' : $type . 's';
    if(empty($$itemName)) continue;

    $nameCellData[] = div
    (
        setClass($type . '-data'),
        control
        (
            setClass('inited'),
            set::type('picker'),
            set::name($type),
            set::items($$itemName),
        )
    );
}

div
(
    setID('nameCellData'),
    setClass('hidden'),
    div
    (
        setClass('custom-data'),
        control
        (
            setClass('inited'),
            set::name('name'),
        )
    ),
    $nameCellData,
);

div
(
    setID('cycleCellData'),
    setClass('hidden'),
    control
    (
        set::type('hidden'),
        set::name('type'),
        set::value('cycle')
    ),
    span($lang->todo->cycle)
);

div
(
    setID('dateCellData'),
    setClass('hidden'),
    inputGroup
    (
        setClass('inited'),
        control
        (
            setClass('time-input'),
            set::type('time'),
            set::name('begin'),
        ),
        control
        (
            setClass('time-input'),
            set::type('time'),
            set::name('end'),
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

$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field) $visibleFields[$field] = '';
}
formBatchPanel
(
    set::url(createLink('todo', 'batchEdit', "from=todoBatchEdit&type={$type}&userID={$userID}&status={$status}")),
    set::mode('edit'),
    set::data(array_values($editedTodos)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="type"]', 'setNameCell'),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true),
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('64px'),
    ),
    formBatchItem
    (
        set::name('date'),
        set::label($lang->todo->date),
        set::width('120px'),
        set::control('date'),
    ),
    formBatchItem
    (
        set::name('type'),
        set::label($lang->todo->type),
        set::width('100px'),
        set::control('picker'),
        set::items($lang->todo->typeList),
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->todo->pri),
        set::width('60px'),
        set::control('picker'),
        set::hidden(!isset($visibleFields['pri'])),
        set::items($lang->todo->priList),
    ),
    formBatchItem
    (
        set::name('name'),
        set::label($lang->todo->name),
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->todo->assignedTo),
        set::width('120px'),
        set::control('picker'),
        set::items($users),
    ),
    formBatchItem
    (
        set::name('beginAndEnd'),
        set::label($lang->todo->beginAndEnd),
        set::width('232px'),
        set::hidden(!isset($visibleFields['beginAndEnd'])),
    ),
    formBatchItem
    (
        set::name('status'),
        set::label($lang->todo->status),
        set::width('100px'),
        set::control('picker'),
        set::hidden(!isset($visibleFields['status'])),
        set::items($lang->todo->statusList)
    ),
);
/* ====== Render page ====== */
render();
