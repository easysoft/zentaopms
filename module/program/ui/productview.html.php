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

$canBatchEdit = common::hasPriv('product', 'batchEdit');
$hasProduct   = false;

/* Closure creating program buttons. */
$fnGenerateCreateProgramBtns = function() use ($lang, $browseType)
{
    $items = array();

    hasPriv('program', 'create') ? $items[] = array
    (
        'text' => $lang->program->create,
        'icon' => 'plus',
        'url'  => createLink('program', 'create')
    ) : null;

    hasPriv('product', 'create') ? $items[] = array
    (
        'text' => $lang->program->createProduct,
        'icon' => 'plus',
        'url'  => createLink('product', 'create')
    ) : null;

    hasPriv('product', 'manageLine') ? $items[] = array
    (
        'text'        => $lang->program->manageLine,
        'icon'        => 'edit',
        'data-toggle' => 'modal',
        'data-url'    => createLink('product', 'manageLine', $browseType)
    ) : null;

    return inputGroup
    (
        hasPriv('program', 'create') ? btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::text($lang->program->create),
            set::url(createLink('program', 'create'))
        ) : null,
        !empty($items) ? dropdown
        (
            setClass('btn primary'),
            span(setClass('caret')),
            set::items($items),
        ) : null
    );
};

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

/* Closure for generating program row data. */
$fnGenerateProgramRowData = function($programID, $program) use ($config, $users)
{
    if(!isset($program['programName']) || $config->systemMode != 'ALM') return null;

    /* ALM mode with more data. */
    $totalStories = $program['finishClosedStories'] + $program['unclosedStories'];
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
    $item->parent               = '';
    $item->name                 = $program['programName'];
    $item->PM                   = $pmName;
    $item->createdDate          = '';
    $item->createdBy            = '';
    $item->totalUnclosedStories = $program['unclosedStories'];
    $item->totalStories         = $totalStories;
    $item->closedStoryRate      = ($totalStories == 0 ? 0 : round($program['finishClosedStories'] / $totalStories, 3) * 100);
    $item->totalPlans           = $program['plans'];
    $item->totalProjects        = 0;
    $item->totalExecutions      = 0;
    $item->testCaseCoverage     = $program['coverage'];
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
    if(!isset($line['lineName']) || !isset($line['products']) || !is_array($line['products']) || $config->systemMode != 'ALM') return null;

    /* ALM mode with Product Line. */
    $totalStories = (isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) + (isset($line['unclosedStories']) ? $line['unclosedStories'] : 0);
    $linesCount++;

    $item = new stdClass();
    $item->type                 = 'productLine';
    $item->id                   = 'productLine-' . $lineID;
    $item->parent               = 'program-' . $programID;
    $item->name                 = $line['lineName'];
    $item->PM                   = '';
    $item->createdDate          = '';
    $item->createdBy            = '';
    $item->totalUnclosedStories = $line['unclosedStories'];
    $item->totalStories         = $totalStories;
    $item->closedStoryRate      = ($totalStories == 0 ? 0 : round((isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) / $totalStories, 3) * 100);
    $item->totalPlans           = $line['plans'];
    $item->totalProjects        = 0;
    $item->totalExecutions      = 0;
    $item->testCaseCoverage     = $line['coverage'];
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
    $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];

    $item = new stdClass();
    $item->type                 = 'product';
    $item->id                   = $product->id;
    $item->parent               = $product->line ? "productLine-$lineID" : ($product->program ? "program-$product->program" : '');
    $item->name                 = $product->name;
    $item->PM                   = !empty($product->PO) ? zget($users, $product->PO) : '';
    $item->createdDate          = $product->createdDate;
    $item->createdBy            = $product->createdBy;
    $item->totalUnclosedStories = $product->stories['unclosed'];
    $item->totalStories         = $totalStories;
    $item->closedStoryRate      = empty($totalStories) ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100;
    $item->totalPlans           = $product->plans;
    $item->totalProjects        = $product->projects;
    $item->totalExecutions      = $product->executions;
    $item->testCaseCoverage     = $product->coverage;
    $item->totalActivatedBugs   = $product->activeBugs;
    $item->totalBugs            = $product->bugs;
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

/* ZIN: layout. */

featureBar
(
    set::current($browseType),
    set::linkParams("status={key}&orderBy=$orderBy"),
    (hasPriv('product', 'batchEdit') && $hasProduct === true) ? li(checkbox
    (
        on::click('onClickCheckBatchEdit'),
        set::type('checkbox'),
        set::text($lang->project->edit),
        set('data-id', 'checkbox-batchedit'),
        set('data-load', 'table'),
        set::checked($this->cookie->editProject)
    )) : NULL,
    li(searchToggle(set::open($browseType == 'bySearch'))),
    li(btn(setClass('ghost'), set::icon('unfold-all'), $lang->sort))
);

toolbar
(
    item(set(array(
        'text' => $lang->program->export,
        'icon' => 'export',
        'class'=> 'ghost',
        'url'  => createLink('program', 'exportTable')
    ))),
    div(setClass('nav-divider')),
    item(set(array(
        'text' => $lang->program->edit,
        'icon' => 'edit',
        'class'=> 'ghost',
        'url'  => createLink('program', 'exportTable')
    ))),
    $fnGenerateCreateProgramBtns()
);

dtable
(
    set::cols($fnGenerateCols()),
    set::data($data),
    set::userMap($users),
    set::customCols(true),
    set::checkable($canBatchEdit),
    set::nested(true),
    set::className('shadow rounded'),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderCellProductView')),
    set::footToolbar(array
    (
        'type'  => 'btn-group',
        'items' => array(
            $canBatchEdit ? array
            (
                'text'    => $lang->edit,
                'btnType' => 'secondary',
                'url'     => createLink('product', 'batchEdit'),
                'onClick' => jsRaw('onClickBatchEdit')
            ) : null
        )
    )),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList);}"))
);

jsVar('pageSummary', sprintf($lang->product->lineSummary, $linesCount, count($productStats)));
jsVar('checkedSummary', $lang->program->checkedProducts);

render();
