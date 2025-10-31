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

jsVar('childrenAB', $lang->productplan->childrenAB);
jsVar('expiredLang', $lang->productplan->expired);
jsVar('nextStep', $lang->productplan->nextStep);
jsVar('enterProjectList', $lang->productplan->enterProjectList);
jsVar('plans', $plans);

$isFromDoc = $from === 'doc';
$isFromAI  = $from === 'ai';

if($isFromDoc || $isFromAI)
{
    $this->app->loadLang('doc');
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
    $productChangeLink = createLink($app->rawModule, $app->rawMethod, "productID={productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}");
    $insertListLink = createLink($app->rawModule, $app->rawMethod, "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={blockID}");

    formPanel
    (
        setID('zentaolist'),
        setClass('mb-4-important'),
        set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['productPlan'])),
        set::actions(array()),
        set::showExtra(false),
        to::titleSuffix
        (
            span
            (
                setClass('text-muted text-sm text-gray-600 font-light'),
                span
                (
                    setClass('text-warning mr-1'),
                    icon('help'),
                ),
                $lang->doc->previewTip
            )
        ),
        formRow
        (
            formGroup
            (
                set::width('1/2'),
                set::name('product'),
                set::label($lang->doc->product),
                set::control(array('required' => false)),
                set::items($products),
                set::value($productID),
                set::required(),
                span
                (
                    setClass('error-tip text-danger hidden'),
                    $lang->doc->emptyError
                ),
                on::change('[name="product"]')->do("loadModal('$productChangeLink'.replace('{productID}', $(this).val()))")
            )
        )
    );
}

/* zin: Define the set::module('productplan') feature bar on main menu. */
featureBar
(
    set::current($browseType),
    set::module('productplan'),
    set::method('browse'),
    set::linkParams("productID={$productID}&branch={$branch}&browseType={key}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from={$from}&blockID={$blockID}"),
    set::isModal($isFromDoc || $isFromAI),
    li(searchToggle
    (
        set::simple($isFromDoc || $isFromAI),
        set::module('productplan'),
        set::open($browseType == 'bySearch'),
        ($isFromDoc || $isFromAI) ? set::target('#docSearchForm') : null,
        ($isFromDoc || $isFromAI) ? set::onSearch(jsRaw('function(){$(this.element).closest(".modal").find("#featureBar .nav-item>.active").removeClass("active").find(".label").hide()}')) : null
    ))
);

if($isFromDoc || $isFromAI)
{
    div(setID('docSearchForm'));
}

$canCreatePlan = common::canModify('product', $product) && common::hasPriv($app->rawModule, 'create');
toolbar
(
    setClass(array('hidden' => $isFromDoc || $isFromAI)),
    btnGroup
    (
        btn(setClass($viewType == 'list'   ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('format-list-bulleted'), setData('type', 'list'), setClass('switchButton'), setData('app', $app->tab)),
        btn(setClass($viewType == 'kanban' ? 'text-primary font-bold shadow-inner bg-canvas' : ''), set::icon('kanban'), setData('type', 'kanban'), setClass('switchButton'), setData('app', $app->tab)),
    ),
    $canCreatePlan ? item(set(array('icon' => 'plus', 'class' => 'primary plan-create-btn', 'text' => $lang->productplan->create, 'url' => createLink($app->rawModule, 'create', "productID={$productID}&branch={$branch}")))) : null
);

$cols = $this->loadModel('datatable')->getSetting('productplan');
$cols['title']['data-app'] = $app->tab;
if($app->rawModule == 'projectplan') $cols['actions']['list']['createExecution']['url']['params'] = "projectID={$projectID}&executionID=0&copyExecutionID=0&planID={id}";

if($isFromDoc || $isFromAI)
{
    if(isset($cols['actions'])) unset($cols['actions']);
    foreach($cols as $key => $col)
    {
        $cols[$key]['sortType'] = false;
        if(isset($col['link'])) unset($cols[$key]['link']);
        if($key == 'title') $cols[$key]['link'] = array('url' => createLink('productplan', 'view', 'planID={id}'), 'data-toggle' => 'modal', 'data-size' => 'lg');
    }
}

$tableData = initTableData($plans, $cols, $this->productplan);
foreach($tableData as $plan)
{
    $otherActions = array();
    foreach($plan->actions as $i => $action)
    {
        if(is_string($action) && strpos($action, 'other:') !== false)
        {
            $otherActions = explode(',', str_replace('other:', '', $action));
            break;
        }
    }

    $otherActions = array_filter(array_map(function($action) use($plan)
    {
        if($plan->status == 'doing' && (strpos($action, 'close') !== false || strpos($action, 'activate') !== false)) return $action;
        if($plan->status == 'done' && strpos($action, 'activate') !== false) return $action;
        if($plan->status == 'closed' && strpos($action, 'close') !== false) return $action;
        if($plan->status == 'wait') return $action;
        return null;
    }, $otherActions));
    if($otherActions) $plan->actions[$i] = 'other:' . implode(',', $otherActions);
}

$canBatchEdit         = common::hasPriv('productplan', 'batchEdit');
$canBatchChangeStatus = common::hasPriv('productplan', 'batchChangeStatus');
$canBatchAction       = $canBatchEdit || $canBatchChangeStatus;

$footToolbar = array();
if($canBatchAction && !$isFromDoc && !$isFromAI)
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
        $footToolbar['items'][] = array(
            'text'      => $lang->close,
            'className' => 'btn batch-btn size-sm secondary',
            'data-url'  => $this->createLink('productplan', 'batchChangeStatus', "status=closed&productID={$productID}")
        );
    }

    if($canBatchChangeStatus)
    {
        $items = array();
        foreach($lang->productplan->statusList as $statusKey => $statusText)
        {
            if($statusKey == 'closed') continue;
            $items[$statusKey] = array
            (
                'text'     => $statusText,
                'class'    => 'batch-btn ajax-btn not-open-url',
                'data-url' => createLink('productplan', 'batchChangeStatus', "status={$statusKey}&productID={$productID}")
            );
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

if($isFromDoc) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#productPlans', 'productPlan', $blockID, '$insertListLink')"));
if($isFromAI)  $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#productPlans', 'plan')"));
$sortLink = createLink('productplan', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");

dtable
(
    setID('productPlans'),
    set::cols($cols),
    set::data($tableData),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footToolbar($footToolbar),
    set::emptyTip($lang->productplan->noPlan),
    set::footPager(
        usePager(array('linkCreator' => createLink($app->rawModule, 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={recPerPage}&pageID={page}&from=$from&blockID=$blockID"))),
    ),
    !$isFromDoc ? null : set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    !$isFromDoc ? null : set::onCheckChange(jsRaw('window.checkedChange')),
    !$isFromDoc ? null : set::height(400),
    !$isFromDoc ? null : set::noNestedCheck(),
    $isFromDoc ? null : set::customCols(true),
    $isFromDoc ? null : set::sortLink($sortLink),
    $isFromDoc ? null : set::checkInfo(jsRaw("function(checkedIDList){return window.setStatistics(this, checkedIDList, '{$summary}');}")),
    $isFromDoc ? null : set::createTip($lang->productplan->create),
    $isFromDoc ? null : set::createLink($canCreatePlan ? createLink($app->rawModule, 'create', "productID={$productID}&branch={$branch}") : '')
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
                    'class' => 'createExecutionBtn',
                    'text'  => !empty($projects) ? $lang->productplan->nextStep : $lang->productplan->enterProjectList,
                    'id'    => !empty($projects) ? 'createExecutionButton' : '',
                    'type'  => 'primary',
                    'url'   => !empty($projects) ? '###' : createLink('product', 'project', "status=all&productID={$productID}&branch={$branch}")
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
