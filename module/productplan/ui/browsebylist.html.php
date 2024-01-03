<?php
declare(strict_types=1);
/**
 * The browses view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('checkedSummary', $lang->productplan->checkedSummary);
jsVar('childrenAB', $lang->productplan->childrenAB);
jsVar('expiredLang', $lang->productplan->expired);

/* zin: Define the set::module('productplan') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::linkParams("productID={$productID}&branch={$branch}&browseType={key}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    li(searchToggle(set::module('productplan'), set::open($browseType == 'bySearch')))
);

$canCreatePlan = common::canModify('product', $product) && common::hasPriv($app->rawModule, 'create');
toolbar
(
    btnGroup
    (
        btn(setClass($viewType == 'list'   ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('format-list-bulleted'), setData('type', 'list'), setClass('switchButton')),
        btn(setClass($viewType == 'kanban' ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('kanban'), setData('type', 'kanban'), setClass('switchButton'))
    ),
    $canCreatePlan ? item(set(array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->productplan->create, 'url' => createLink($app->rawModule, 'create', "productID={$productID}&branch={$branch}")))) : null
);

$cols      = $this->loadModel('datatable')->getSetting('productplan');
$tableData = initTableData($plans, $cols, $this->productplan);

$canBatchEdit         = common::hasPriv('productplan', 'batchEdit');
$canBatchChangeStatus = common::hasPriv('productplan', 'batchChangeStatus');
$canBatchAction       = $canBatchEdit || $canBatchChangeStatus;

$footToolbar = array();
if($canBatchAction)
{
    if($canBatchEdit)
    {
        $footToolbar['items'][] = array(
            'text'      => $lang->edit,
            'className' => 'btn batch-btn size-sm secondary',
            'data-url'  => $this->createLink('productplan', 'batchEdit', "productID={$productID}&branch={$branch}")
        );
    }

    if($canBatchChangeStatus)
    {
        $items = array();
        foreach($lang->productplan->statusList as $statusKey => $statusText)
        {
            $items[$statusKey] = array
                (
                    'text'     => $statusText,
                    'class'    => 'batch-btn',
                    'data-url' => createLink('productplan', 'batchChangeStatus', "status={$statusKey}&productID={$productID}")
                );
            if($statusKey == 'closed') $items[$statusKey]['data-page'] = 'batch';
        }

        menu
        (
            setID('navStatus'),
            setClass('dropdown-menu'),
            set::items(array_values($items))
        );

        $footToolbar['items'][] = array(
            'text'           => $lang->statusAB,
            'btnType'        => 'secondary',
            'caret'          => 'up',
            'url'            => '#navStatus',
            'data-toggle'    => 'dropdown',
            'data-placement' => 'top-start'
        );
    }
}

dtable
(
    set::cols($cols),
    set::data($tableData),
    set::customCols(true),
    set::orderBy($orderBy),
    set::sortLink(createLink('productplan', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footToolbar($footToolbar),
    set::emptyTip($lang->productplan->noPlan),
    set::createTip($lang->productplan->create),
    set::createLink($canCreatePlan ? createLink($app->rawModule, 'create', "productID={$productID}&branch={$branch}") : ''),
    set::checkInfo(jsRaw("function(checkedIDList){return window.setStatistics(this, checkedIDList, '{$summary}');}")),
    set::footPager(
        usePager(array('linkCreator' => createLink($app->rawModule, 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}")))
    )
);

modal
(
    setID('createExecutionModal'),
    set::modalProps(array('title' => $lang->productplan->selectProjects)),
    form
    (
        setID('createExecutionForm'),
        setClass('py-4'),
        set::actions
        (
            array(
                array
                (
                    'text' => !empty($projects) ? $lang->productplan->nextStep : $lang->productplan->enterProjectList,
                    'id'   => !empty($projects) ? 'createExecutionButton' : '',
                    'type' => 'primary',
                    'url'  => !empty($projects) ? '###' : createLink('product', 'project', "status=all&productID={$productID}&branch={$branch}")
                ),
                array
                (
                    'text' => $lang->cancel,
                    'data-dismiss' => 'modal'
                )
            )
        ),
        formGroup
        (
            set::label($lang->productplan->project),
            picker
            (
                set::name('project'),
                set::items($projects),
                set::required(true),
                set::disabled(empty($projects))
            )
        ),
        formRow
        (
            !empty($projects) ? setClass('hidden') : null,
            setClass('projectTips'),
            formGroup
            (
                set::label(''),
                span
                (
                    setClass('text-danger'),
                    $lang->productplan->noLinkedProject
                ),
                formHidden('planID', '')
            )
        )
    )
);
