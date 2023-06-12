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
$fnGetTableFieldList = function() use ($config, $browseType, $lang, $product)
{
    $fieldList = $config->productplan->dtable->fieldList;

    if($browseType != 'all') unset($fieldList['status']);

    if($this->session->currentProductType == 'normal')
    {
        unset($fieldList['branch']);
    }
    else
    {
        $fieldList['branch']['title'] = $lang->product->branchName[$product->type];
    }

    // TODO: attach extend fields of Workflow module.

    return array_values($fieldList);
};

$totalParent = 0;
$totalChild  = 0;

$fnGenerateTableData = function($plans) use ($config, $lang, &$totalParent, &$totalChild, $browseType, $branchOption)
{
    $this->loadModel('file');

    $dataList = array();

    foreach($plans as $plan)
    {
        $plan = $this->file->replaceImgURL($plan, 'desc');
        if($plan->parent == '-1')
        {
          $parent   = $plan->id;
          $children = isset($plan->children) ? $plan->children : 0;

          $totalParent ++;
        }
        if($plan->parent == 0) $parent = 0;
        if(!empty($parent) and $plan->parent > 0 and $plan->parent != $parent) $parent = 0;
        if($plan->parent <= 0) $i = 0;
        if($plan->parent > 0) $totalChild ++;

        $class = '';
        if(!empty($parent) and $plan->parent == $parent)
        {
          $class  = "table-children parent-{$parent}";
          $class .= $i == 0 ? ' table-child-top' : '';
          $class .= ($i + 1 == $children) ? ' table-child-bottom' : '';
          $i++;
        }

        $data = new stdclass();
        $data->id = sprintf('%03d', $plan->id);

        $data->title = $plan->title;

        if($browseType == 'all') $data->status = $plan->status;

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
        $data->parent  = $plan->parent > 0 ? "$plan->parent" : '';

        $data->execution = null;
        if(!empty($plan->projects))
        {
            if(count($plan->projects) === 1)
            {
                $executionID = key($plan->projects);
                $data->execution = $plan->projects[$executionID]->name;
            }
            else
            {
                /* Associated to multi executions. */
                $comma = '';
                foreach($plan->projects as $executionID => $execution)
                {
                    $comma = ',';
                    $data->execution .= $comma . $execution->name;
                }
            }
        }

        $desc = trim(strip_tags(str_replace(array('</p>', '<br />', '<br>', '<br/>'), "\n", str_replace(array("\n", "\r"), '', $plan->desc)), '<img>'));
        $data->desc = nl2br($desc);

        // TODO: values of extend fields.

        $data->actions = $this->productplan->buildActionBtnList($plan, 'browse');

        $dataList[] = $data;
    }

    return $dataList;
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
            'recTotal'   => $recTotal,
            'recPerPage' => $recPerPage,
            'pageID'     => $pageID
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
        $lang->productplan->create
    ) : null
);

/* No plans. */
if(empty($plans))
{
    $btn = null;
    if(common::canModify('product', $product) && empty($productPlansNum))
    {
        if(common::hasPriv('projectplan', 'create') && $isProjectplan)
        {
          $btn = btn
          (
              setClass('secondary'),
              set::icon('plus'),
              set::url(createLink('projectplan', 'create', "productID=$product->id&branch=$branch")),
              $lang->productplan->create,
          );
        }
        elseif(common::hasPriv('productplan', 'create'))
        {
          $btn = btn
          (
              setClass('secondary'),
              set::icon('plus'),
              set::url(createLink('productplan', 'create', "productID=$product->id&branch=$branch")),
              $lang->productplan->create,
          );
        }
    }

    panel
    (
        setClass('flex justify-center'),
        $lang->productplan->noPlan,
        $btn
    );

    return render();
}

$canBatchEdit         = common::hasPriv('productplan', 'batchEdit');
$canBatchChangeStatus = common::hasPriv('productplan', 'batchChangeStatus');

/* Generate dropdown menu for the batch change status button on footbar. */
$fnGenerateDropdownMenu = function() use($lang, $canBatchChangeStatus)
{
    if(!$canBatchChangeStatus) return;

    $items = array();
    foreach($lang->productplan->statusList as $statKey => $statText)
    {
        if($statKey == 'closed') continue;

        $items[] = array
        (
            'text' => $statText,
            'onclick' => jsRaw("function(){console.log('$statText')}")
        );
    }

    zui::menu
    (
        set::id('footbarActionMenu'),
        set::class('menu dropdown-menu'),
        set::items($items)
    );
};
$fnGenerateDropdownMenu();

dtable
(
    set::cols($fnGetTableFieldList()),
    set::data($fnGenerateTableData($plans)),
    set::customCols(true),
    set::nested(true),
    set::checkable(true),
    set::sortLink(createLink('product', 'all', "browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$recTotal}&recPerPage={$recPerPage}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(
            $canBatchEdit ? array
            (
                'text'    => $lang->edit,
                'btnType' => 'primary',
                'url'     => inlink('batchEdit', "productID=$product->id&branch=$branch"),
            ) : null,
            $canBatchChangeStatus ? array
            (
                'text'           => $lang->statusAB,
                'btnType'        => 'primary',
                'type'           => 'dropdown',
                'caret'          => 'up',
                'url'            => '#footbarActionMenu',
                'data-placement' => 'top-start',
            ) : null
        )
    )),
    set::footPager
    (
        usePager(),
        set::page($pager->pageID),
        set::recPerPage($pager->recPerPage),
        set::recTotal($pager->recTotal),
        set::linkCreator(inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&queryID=$queryID&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={page}"))
    )
);

render();
