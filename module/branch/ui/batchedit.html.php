<?php
declare(strict_types=1);
/**
 * The batchedit view file of branch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('main_branch', BRANCH_MAIN);
/* ====== Define the page structure with zin widgets ====== */
foreach($branchList as $index => &$branch) $branch->key = $index + 1;
formBatchPanel
(
    set::mode('edit'),
    set::data($branchList),
    set::onRenderRow(jsRaw('renderRowData')),
    formBatchItem
    (
        set::name('branchID'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('64px'),
        set::disabled('{branchID} == 0')
    ),
    formBatchItem
    (
        set::name("name"),
        set::label(sprintf($lang->branch->name, $lang->product->branchName[$product->type])),
        set::width('240px')
    ),
    formBatchItem
    (
        set::name("desc"),
        set::label(sprintf($lang->branch->desc, $lang->product->branchName[$product->type]))
    ),
    formBatchItem
    (
        set::name("status"),
        set::label($lang->branch->status),
        set::control('select'),
        set::items($lang->branch->statusList)
    )
);
/* ====== Render page ====== */
render();
