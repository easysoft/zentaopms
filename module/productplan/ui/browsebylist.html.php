<?php
declare(strict_types=1);
/**
* The UI file of productplan module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     productplan
* @link        https://www.zentao.net
*/

namespace zin;

/* Get field list for data table. */
$cols = $this->loadModel('datatable')->getSetting('productplan');
$fnGetTableFieldList = function() use ($cols, $browseType, $lang, $product)
{
    if($browseType != 'all') unset($cols['status']);

    if($this->session->currentProductType == 'normal')
    {
        unset($cols['branch']);
    }
    else
    {
        $cols['branch']['title'] = $lang->product->branchName[$product->type];
    }

    // TODO: attach extend fields of Workflow module.

    return array_values($cols);
};

$totalParent      = 0;
$totalChild       = 0;
$totalIndependent = 0;

$fnGenerateTableData = function($plans) use ($config, $lang, &$totalParent, &$totalChild, &$totalIndependent, $browseType, $branchOption, $productID)
{
    $dataList = array();

    foreach($plans as $plan)
    {
        $data = new stdclass();

        $data->id    = sprintf('%03d', $plan->id);
        $data->title = $plan->title;

        $data->planID    = $plan->id;
        $data->productID = $productID;

        /* Parent. */
        $data->parent = '';
        if($plan->parent == '-1') $totalParent ++;
        if($plan->parent == 0) $totalIndependent++;
        if($plan->parent > 0)
        {
            $data->parent   = sprintf('%03d', $plan->parent);
            $totalChild ++;
        }

        if($browseType == 'all') $data->status = $plan->status;

        /* Branch. */
        if($this->session->currentProductType != 'normal')
        {
            $planBranches = '';
            foreach(explode(',', $plan->branch) as $branchID) $planBranches .= $branchOption[$branchID] . ',';
            $data->branch = trim($planBranches, ',');
        }

        $data->begin   = $plan->begin == $config->productplan->future ? $lang->productplan->future : $plan->begin;
        $data->end     = $plan->end == $config->productplan->future ? $lang->productplan->future : $plan->end;
        $data->stories = $plan->stories;
        $data->bugs    = $plan->bugs;
        $data->hour    = $plan->hour;

        /* Executions. */
        $data->execution = array();
        if(!empty($plan->projects))
        {
            /* Associated to multi executions. */
            foreach($plan->projects as $executionID => $execution)
            {
                $data->execution[]   = array('id' => $executionID, 'name' => $execution->name);
            }
        }

        /* Description. */
        $this->loadModel('file');
        $plan = $this->file->replaceImgURL($plan, 'desc');
        $desc = !empty($plan->desc) ? trim(strip_tags(str_replace(array('</p>', '<br />', '<br>', '<br/>'), "\n", str_replace(array("\n", "\r"), '', $plan->desc)), '<img>')) : '';
        $data->desc = nl2br($desc);

        // TODO: values of extend fields.

        /* Build action button list. */
        $data->actions = $this->productplan->buildActionBtnList($plan, 'browse');
        $data->actions = rearrangeActionBtns($data->actions);

        $dataList[] = $data;
    }

    return $dataList;
};

function rearrangeActionBtns($actions)
{
    $actionMap = array();
    foreach($actions as $action)
    {
        $action['text'] = '';
        $actionMap[$action['name']] = $action;
    }

    $result = array();

    if(empty($actionMap['start']['disabled']))
    {
        $result[] = $actionMap['start'];
        $result[] = array('type' => 'dropdown', 'items' => array
        (
            $actionMap['finish'],
            $actionMap['close'],
            $actionMap['activate'],
        ));
    }
    elseif(empty($actionMap['finish']['disabled']))
    {
        $result[] = $actionMap['finish'];
        $result[] = array('type' => 'dropdown', 'items' => array
        (
            $actionMap['start'],
            $actionMap['close'],
            $actionMap['activate'],
        ));
    }
    elseif(empty($actionMap['close']['disabled']))
    {
        $result[] = $actionMap['close'];
        $result[] = array('type' => 'dropdown', 'items' => array
        (
            $actionMap['start'],
            $actionMap['finish'],
            $actionMap['activate'],
        ));
    }
    elseif(empty($actionMap['activate']['disabled']))
    {
        $result[] = $actionMap['activate'];
        $result[] = array('type' => 'dropdown', 'items' => array
        (
            $actionMap['start'],
            $actionMap['finish'],
            $actionMap['close'],
        ));
    }
    else
    {
        $result[] = $actionMap['start'];
        $result[] = array('type' => 'dropdown', 'items' => array
        (
            $actionMap['finish'],
            $actionMap['close'],
            $actionMap['activate'],
        ));
    }

    $result[] = $actionMap['plus'];
    $result[] = $actionMap['divider'];
    $result[] = $actionMap['link'];
    $result[] = $actionMap['bug'];
    $result[] = $actionMap['edit'];
    $result[] = array('type' => 'dropdown', 'icon' => 'icon-ellipsis-v', 'caret' => false, 'items' => array
    (
        $actionMap['split'],
        $actionMap['trash'],
    ));

    return $result;
};

$canBatchEdit         = common::hasPriv('productplan', 'batchEdit');
$canBatchChangeStatus = common::hasPriv('productplan', 'batchChangeStatus');

/* Generate dropdown menu for the batch change status button on footbar. */
if($canBatchChangeStatus)
{
    $items = array();
    foreach($lang->productplan->statusList as $statusKey => $statusText)
    {
        $items[$statusKey] = array
        (
            'text' => $statusText,
            'class' => 'batch-btn',
            'data-formaction' => $this->createLink('productplan', 'batchChangeStatus', "status={$statusKey}&productID={$productID}"),
        );
        if($statusKey == 'closed') $items[$statusKey]['data-page'] = 'batch';
    }

    menu
    (
        set::id('footbarActionMenu'),
        set::className('dropdown-menu'),
        set::items(array_values($items))
    );
};

/* ZIN: layout. */
featureBar
(
    set::link(createLink
    (
        $this->app->rawModule,
        $this->app->rawMethod,
        array
        (
            'productID'  => $productID,
            'branch'     => $branch,
            'browseType' => '{key}',
            'queryID'    => $queryID,
            'orderBy'    => $orderBy,
            'recTotal'   => $pager->recTotal,
            'recPerPage' => $pager->recPerPage,
            'pageID'     => $pager->pageID
        )
    )),
    li(searchToggle(set::open($browseType == 'bySearch'))),
);

toolbar
(
    div
    (
        btn(setClass($viewType == 'list'   ? 'text-primary' : 'text-darker'), set::icon('format-list-bulleted')),
        btn(setClass($viewType == 'kanban' ? 'text-primary' : 'text-darker'), set::icon('kanban'))
    ),
    common::canModify('product', $product) ? btn
    (
        set::icon('plus'),
        setClass('primary'),
        set::url(createLink('productplan', 'create', "productID=$productID&branch=$branch")),
        $lang->productplan->create
    ) : null
);

dtable
(
    setID('planList'),
    set::cols($fnGetTableFieldList()),
    set::data($fnGenerateTableData($plans)),
    set::emptyTip($lang->productplan->noPlan),
    set::customCols(true),
    set::nested(true),
    set::checkable(true),
    set::onRenderCell(jsRaw('window.renderProductPlanList')),
    set::sortLink(createLink('productplan', 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&orderBy={name}_{sortType}&queryID={$queryID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footToolbar(array
    (
        'items' => array(
            $canBatchEdit ? array
            (
                'text'    => $lang->edit,
                'btnType' => 'secondary',
                'className' => 'batch-btn',
                'data-page' => 'batch',
                'data-formaction' => inlink('batchEdit', "productID=$productID&branch=$branch"),
            ) : null,
            $canBatchChangeStatus ? array
            (
                'text'           => $lang->statusAB,
                'btnType'        => 'secondary',
                'caret'          => 'up',
                'url'            => '#footbarActionMenu',
                'data-toggle'    => 'dropdown',
                'data-placement' => 'top-start',
            ) : null
        )
    )),
    set::footPager
    (
        usePager(array('linkCreator' => createLink($app->rawModule, 'browse', "productID={$productID}&branch={$branch}&browseType={$browseType}&queryID={$queryID}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}"))),
    ),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList);}"))
);

jsVar('pageSummary',      $summary);
jsVar('checkedSummary',   $this->lang->productplan->checkedSummary);
jsVar('totalParent',      $totalParent);
jsVar('totalChild',       $totalChild);
jsVar('totalIndependent', $totalIndependent);
jsVar('confirmStart',     $this->lang->productplan->confirmStart);
jsVar('confirmFinish',    $this->lang->productplan->confirmFinish);
jsVar('confirmActivate',  $this->lang->productplan->confirmActivate);
jsVar('confirmDelete',    $this->lang->productplan->confirmDelete);

render();
