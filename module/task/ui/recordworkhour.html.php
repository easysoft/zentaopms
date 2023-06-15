<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmRecord', $lang->task->confirmRecord);

entityLabel
(
    setClass('my-3 gap-x-3'),
    set::level(1),
    set::text($task->name),
    set::entityID($task->id),
    set::reverse(true),
);

if($efforts)
{
    $tableData = initTableData($efforts, $config->task->effortTable->fieldList, $this->task);
    dtable
    (
        set::cols(array_values($config->task->effortTable->fieldList)),
        set::data($tableData),
    );
}

formBatchPanel
(
    set::title($lang->task->addEffort),
    set::shadow(!isonlybody()),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('32px'),
    ),
    formBatchItem
    (
        set::name('date'),
        set::label($lang->task->date),
        set::width('120px'),
        set::control('date'),
        set::value(helper::today())
    ),
    formBatchItem
    (
        set::name('work'),
        set::label($lang->task->work),
        set::width('auto'),
        set::control('textarea')
    ),
    formBatchItem
    (
        set::name('consumed'),
        set::label($lang->task->consumed),
        set::width('80px'),
        set::control
        (
            array(
                'type' => 'inputControl',
                'suffix' => $lang->task->suffixHour,
                'suffixWidth' => 20
            )
        )
    ),
    formBatchItem
    (
        set::name('left'),
        set::label($lang->task->left),
        set::width('80px'),
        set::control
        (
            array(
                'type' => 'inputControl',
                'suffix' => $lang->task->suffixHour,
                'suffixWidth' => 20
            )
        )
    )
);

render(isonlybody() ? 'modalDialog' : 'page');
