<?php
declare(strict_types=1);
/**
 * The browse view file of branch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */
namespace zin;
/* zin: Define the set::module('branch') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("productID={$product->id}&browseType={key}"),
);

/* zin: Define the toolbar on main menu. */
$canCreate    = hasPriv('branch', 'create');
$canBatchEdit = hasPriv('branch', 'batchEdit');
$canMerge     = hasPriv('branch', 'mergeBranch');
if($canCreate) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->branch->create, 'url' => $this->createLink('branch', 'create', "productID={$product->id}", '', true), 'data-toggle' => 'modal');
toolbar
(
    !empty($createItem) ? item(set($createItem)) : null,
);

jsVar('confirmclose',    $lang->branch->confirmClose);
jsVar('confirmactivate', $lang->branch->confirmActivate);

modal
(
    setID('mergeModal'),
    set::modalProps(array('title' => $lang->branch->mergeBranch)),
    div
    (
        setClass('alert light-pale flex items-center'),
        icon('info-sign', setClass('icon icon-2x alert-icon')),
        div
        (
            h4($lang->branch->mergedMain, setClass('font-bold')),
            p($lang->branch->mergeTips),
            p($lang->branch->targetBranchTips)
        )
    ),
    form
    (
        setID('mergeForm'),
        set::ajax(array('beforeSubmit' => jsRaw("() => zui.Modal.confirm('{$lang->branch->confirmMerge}')"))),
        setClass('text-center', 'py-4'),
        set::actions(array('submit')),
        set::url(createLink('branch', 'mergeBranch', "productID={$product->id}")),
        formGroup
        (
            set::label($lang->branch->mergeTo),
            inputGroup
            (
                setClass('input-control has-suffix text-left'),
                picker
                (
                    set::name('targetBranch'),
                    set::items($branchPairs),
                    set::required(true)
                ),
                div
                (
                    setClass('input-control-suffix opacity-100 bg-white z-10 form-batch-ditto-btn mr-1'),
                    checkbox
                    (
                        setID('createBranch'),
                        set::name('createBranch'),
                        set::text($lang->branch->create),
                        set::value(1),
                        on::change('createBranch')
                    )
                )
            ),
            input
            (
                set::type('hidden'),
                set::name('mergedBranchIDList')
            )
        ),
        div
        (
            setClass('hidden form-grid'),
            setID('createForm'),
            formGroup
            (
                set::label(sprintf($lang->branch->name, $lang->product->branchName[$product->type])),
                set::required(true),
                input(set::name('name'))
            ),
            formGroup
            (
                set::label(sprintf($lang->branch->desc, $lang->product->branchName[$product->type])),
                textarea
                (
                    set::name('desc'),
                    set::rows('5')
                )
            )
        )
    )
);

$tableData = initTableData($branchList, $config->branch->dtable->fieldList, $this->branch);
array_map(function($data){if($data->id == 0) $data->actions = array();}, $tableData); //Remove main branch actions.
if(count($tableData) === 1) unset($config->branch->dtable->fieldList['id']);
if(count($tableData) === 1) unset($config->branch->dtable->fieldList['actions']);

$footToolbar  = array();
if($canBatchEdit)
{
    $footToolbar['items'][] = array(
        'text'      => $lang->edit,
        'className' => 'btn batch-btn secondary size-sm',
        'btnType'   => 'primary',
        'data-url'  => createLink('branch', 'batchEdit', "productID={$product->id}")
    );
}

if($canMerge && $browseType != 'closed')
{
    $footToolbar['items'][] = array(
        'attrs'       => array('id' => 'mergeBranch'),
        'text'        => $lang->branch->merge,
        'className'   => 'btn secondary size-sm',
        'data-target' => '#mergeModal',
        'data-toggle' => 'modal'
    );
}

dtable
(
    set::cols($config->branch->dtable->fieldList),
    set::data($tableData),
    set::checkable(count($tableData) > 1 ? true : false),
    set::canRowCheckable(jsRaw("(rowID) => {return rowID == '0' ? false : true}")),
    set::orderBy($orderBy),
    set::sortLink(createLink('branch', 'manage', "productID={$product->id}&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::onCheckChange(jsRaw('checkedChange')),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);

render();
