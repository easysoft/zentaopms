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

$tableData = initTableData($efforts, $config->task->effortTable->fieldList['actions'], $this->task);
dtable
(
    set::cols(array_values($config->task->effortTable->fieldList)),
    set::data($tableData),
);

formBatch
(
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
        set::control('input')
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
