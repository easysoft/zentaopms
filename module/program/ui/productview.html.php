<?php
declare(strict_types=1);
/**
* The UI file of program module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     program
* @link        https://www.zentao.net
*/

namespace zin;

$hasProduct = false;

/* Closure creating program buttons. */
$fnGenerateCreateProgramBtns = function() use ($lang, $browseType)
{
    $items = array();
    hasPriv('program', 'create')     && $items[] = array('text' => $lang->program->create,        'icon' => 'plus', 'url'      => createLink('program', 'create'));
    hasPriv('product', 'create')     && $items[] = array('text' => $lang->program->createProduct, 'icon' => 'plus', 'url'      => createLink('product', 'create'), 'data-app' => 'product');
    hasPriv('product', 'manageLine') && $items[] = array('text' => $lang->program->manageLine,    'icon' => 'edit', 'data-url' => createLink('product', 'manageLine', $browseType), 'data-toggle' => 'modal', 'data-id' => 'manageLineModal');

    if(empty($items)) return null;

    return count($items) > 1 ? btnGroup
    (
        btn
        (
            setClass('btn primary'),
            set(reset($items))
        ),
        dropdown
        (
            setClass('btn primary'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0')),
            set::placement('bottom-end'),
            set::items($items),
            span(setClass('caret'))
        )
    ) : btn
    (
        setClass('btn primary'),
        set(reset($items))
    );
};

/* Closure for generating program row data. */
$fnGenerateProgramRowData = function($programID, $program) use ($config, $users)
{
    if(!isset($program['programName']) || $config->systemMode != 'ALM') return null;

    /* ALM mode with more data. */
    $totalStories = $program['totalStories'];
    $pmName       = '';
    if(!empty($program['programPM']))
    {
        $programPM = $program['programPM'];
        $userName  = zget($users, $programPM);
        $pmName    = $userName;
    }

    $item = new stdClass();
    $item->type                 = 'program';
    $item->id                   = 'program-' . $programID;
    $item->parent               = 0;
    $item->isParent             = true;
    $item->name                 = $program['programName'];
    $item->PM                   = $pmName;
    $item->createdDate          = '';
    $item->createdBy            = '';
    $item->totalUnclosedStories = $totalStories - $program['closedStories'];
    $item->totalStories         = $totalStories;
    $item->closedStoryRate      = ($totalStories == 0 ? 0 : round($program['closedStories'] / $totalStories, 3) * 100);
    $item->totalPlans           = $program['plans'];
    $item->totalProjects        = 0;
    $item->totalExecutions      = 0;
    $item->testCaseCoverage     = $program['coverage'] ?? 0;
    $item->totalActivatedBugs   = 0;
    $item->totalBugs            = 0;
    $item->fixedRate            = $item->totalBugs == 0 ? 0 : round($program['fixedBugs'] / $item->totalBugs, 3) * 100;
    $item->totalReleases        = $program['releases'];
    $item->latestReleaseDate    = '';
    $item->latestRelease        = '';

    return $item;
};

/* Closure for generating product line row data. */
$fnGenerateLineRowData = function($programID, $lineID, $line) use ($config, &$linesCount)
{
    if(!isset($line['lineName']) || !isset($line['products']) || !is_array($line['products']) || $config->systemMode != 'ALM' || $config->systemMode != 'PLM') return null;

    /* ALM mode with Product Line. */
    $linesCount++;

    $item = new stdClass();
    $item->type                 = 'productLine';
    $item->id                   = 'productLine-' . $lineID;
    $item->parent               = 'program-' . $programID;
    $item->isParent             = true;
    $item->name                 = $line['lineName'];
    $item->PM                   = '';
    $item->createdDate          = '';
    $item->createdBy            = '';
    $item->totalUnclosedStories = $line['totalStories'] - $line['closedStories'];
    $item->totalStories         = $line['totalStories'];
    $item->closedStoryRate      = ($line['totalStories'] == 0 ? 0 : round(zget($line, 'finishClosedStories', 0) / $line['totalStories'], 3) * 100);
    $item->totalPlans           = $line['plans'];
    $item->totalProjects        = 0;
    $item->totalExecutions      = 0;
    $item->testCaseCoverage     = $line['coverage'] ?? 0;
    $item->totalActivatedBugs   = 0;
    $item->totalBugs            = 0;
    $item->fixedRate            = !empty($item->totalBugs) ? round($line['fixedBugs'] / $item->totalBugs, 3) * 100 : 0;
    $item->totalReleases        = isset($line['releases']) ? $line['releases'] : 0;
    $item->latestReleaseDate    = '';
    $item->latestRelease        = '';

    return $item;
};

/* Closure for generating product row data. */
$fnGenerateProductRowData = function($lineID, $product) use ($users)
{
    $item = new stdClass();
    $item->type                 = 'product';
    $item->id                   = $product->id;
    $item->parent               = $product->line ? "productLine-$lineID" : ($product->program ? "program-$product->program" : 0);
    $item->name                 = $product->name;
    $item->PM                   = !empty($product->PO) ? zget($users, $product->PO) : '';
    $item->createdDate          = $product->createdDate;
    $item->createdBy            = $product->createdBy;
    $item->totalUnclosedStories = $product->totalStories - $product->closedStories;
    $item->totalStories         = $product->totalStories;
    $item->closedStoryRate      = empty($product->totalStories) ? 0 : round($product->closedStories / $product->totalStories, 3) * 100;
    $item->totalPlans           = $product->plans;
    $item->totalProjects        = $product->projects;
    $item->totalExecutions      = $product->executions;
    $item->testCaseCoverage     = $product->coverage;
    $item->totalActivatedBugs   = $product->unresolvedBugs;
    $item->totalBugs            = $product->totalBugs;
    $item->fixedRate            = empty($item->totalBugs) ? 0 : round($product->fixedBugs / $item->totalBugs, 3) * 100;
    $item->totalReleases        = $product->releases;
    $item->latestReleaseDate    = $product->latestReleaseDate;
    $item->latestRelease        = $product->latestRelease;

    return $item;
};

$linesCount = 0;
$data       = array();
foreach($productStructure as $programID => $program)
{
    if(isset($programLines[$programID]))
    {
        foreach($programLines[$programID] as $lineID => $lineName)
        {
            if(!isset($program[$lineID]))
            {
                $program[$lineID] = array();
                $program[$lineID]['product']  = '';
                $program[$lineID]['lineName'] = $lineName;
            }
        }
    }

    $totalExecutions = 0;
    $totalBugs       = 0;
    $totalActiveBugs = 0;
    $totalProjects   = 0;
    foreach($program as $lineID => $line)
    {
        $totalProductExecutions = 0;
        $totalProductBugs       = 0;
        $totalProductActiveBugs = 0;
        $totalProductProjects   = 0;
        if(isset($line['products']) && is_array($line['products']))
        {
            /* Generate product row data. */
            foreach($line['products'] as $productID => $product)
            {
                $productRow = $fnGenerateProductRowData($lineID, $product);

                $totalProductExecutions += $productRow->totalExecutions;
                $totalProductBugs       += $productRow->totalBugs;
                $totalProductActiveBugs += $productRow->totalActivatedBugs;
                $totalProductProjects   += $productRow->totalProjects;

                $data[] = $productRow;

                /* Set flag variable. */
                $hasProduct = true;
            }
        }

        /* Generate product line row data. */
        $lineRow = $fnGenerateLineRowData($programID, $lineID, $line);
        if(!empty($lineRow))
        {
            $lineRow->totalExecutions    = $totalProductExecutions;
            $lineRow->totalBugs          = $totalProductBugs;
            $lineRow->totalActivatedBugs = $totalProductActiveBugs;
            $lineRow->totalProjects      = $totalProductProjects;

            $data[] = $lineRow;
        }

        $totalExecutions += $totalProductExecutions;
        $totalBugs       += $totalProductBugs;
        $totalActiveBugs += $totalProductActiveBugs;
        $totalProjects   += $totalProductProjects;
    }

    /* Generate program row data. */
    $programRow = $fnGenerateProgramRowData($programID, $program);
    if(!empty($programRow))
    {
        $programRow->totalExecutions    = $totalExecutions;
        $programRow->totalBugs          = $totalBugs;
        $programRow->totalActivatedBugs = $totalActiveBugs;
        $programRow->totalProjects      = $totalProjects;

        $data[] = $programRow;
    }
}

$canBatchEdit = (common::hasPriv('product', 'batchEdit') && $hasProduct === true);

/* Generate cols for the data table. */
$fnGenerateCols = function() use ($canBatchEdit)
{
    $cols = $this->loadModel('datatable') ->getSetting('program');

    foreach($cols as $colName => &$setting)
    {
        if($colName == 'name')
        {
            $setting['checkbox'] = $canBatchEdit;
            break;
        }
    }

    return $cols;
};

/* ZIN: layout. */
featureBar
(
    set::current($browseType),
    set::linkParams("status={key}&orderBy=$orderBy&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::open($browseType == 'bySearch'), set::module('program')))
);
toolbar($fnGenerateCreateProgramBtns());

dtable
(
    setID('productviews'),
    set::cols($fnGenerateCols()),
    set::data($data),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::nested(true),
    set::className('shadow rounded'),
    set::footPager(usePager()),
    set::canRowCheckable(jsRaw("function(rowID){return this.getRowInfo(rowID).data.type == 'product';}")),
    set::onRenderCell(jsRaw('window.renderCellProductView')),
    set::orderBy($orderBy),
    set::sortLink(createLink('program', 'productview', "browseType={$browseType}&orderBy={name}_{sortType}&param={$param}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(
            $canBatchEdit ? array
            (
                'text'      => $lang->edit,
                'className' => 'secondary batch-btn',
                'data-page' => 'batch',
                'data-formaction' => $this->createLink('product', 'batchEdit')
            ) : null
        )
    )),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList);}")),
    set::emptyTip($lang->product->noProduct),
    set::createTip($lang->product->create),
    set::createLink(hasPriv('product', 'create') ? createLink('product', 'create') : null)
);

jsVar('pageSummary', sprintf($lang->product->lineSummary, $linesCount, count($productStats)));
jsVar('checkedSummary', $lang->program->checkedProducts);

render();
