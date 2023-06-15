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

/* Generate cols for the data table. */
$fnGenerateCols = function()
{
    return $this->loadModel('datatable') ->getSetting('program');
};

$totalStories = 0;
$hasProduct   = false;
$linesCount   = 0;
$data         = array();
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

    /* ALM mode with more data. */
    if(isset($program['programName']) && $config->systemMode == 'ALM')
    {
        $totalStories = $program['finishClosedStories'] + $program['unclosedStories'];
        $pmName       = '';
        if(!empty($program['programPM']))
        {
            $programPM = $program['programPM'];
            $userName  = zget($users, $programPM);
            $pmname    = $userName;
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
        $item->totalProjects        = rand(0, 100);
        $item->totalExecutions      = rand(0, 100);
        $item->testCaseCoverage     = rand(0, 100);
        $item->totalActivatedBugs   = $program['activeStories'];
        $item->totalBugs            = $program['unResolvedBugs'] + $program['fixedBugs'];
        $item->fixedRate            = $item->totalBugs == 0 ? 0 : round($program['fixedBugs'] / $item->totalBugs, 3) * 100;
        $item->totalReleases        = $program['releases'];
        $item->latestReleaseDate    = '';
        $item->latestRelease        = '';

        $data[] = $item;
    }

    foreach($program as $lineID => $line)
    {
        /* ALM mode with Product Line. */
        if(isset($line['lineName']) && isset($line['products']) && is_array($line['products']) && $config->systemMode == 'ALM')
        {
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
            $item->totalProjects        = rand(0, 100);
            $item->totalExecutions      = rand(0, 100);
            $item->testCaseCoverage     = rand(0, 100);
            $item->totalActivatedBugs   = $line['activeStories'];
            $item->totalBugs            = $line['unResolvedBugs'] + $line['fixedBugs'];
            $item->fixedRate            = !empty($item->totalBugs) ? round($line['fixedBugs'] / $item->totalBugs, 3) * 100 : 0;
            $item->totalReleases        = isset($line['releases']) ? $line['releases'] : 0;
            $item->latestReleaseDate    = '';
            $item->latestRelease        = '';

            $data[] = $item;
        }

        /* Products of Product Line. */
        if(isset($line['products']) && is_array($line['products']))
        {
            foreach($line['products'] as $productID => $product)
            {
                $hasProduct   = true;
                $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];
                $totalBugs    = $product->unResolved + $product->fixedBugs;

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
                $item->totalProjects        = rand(0, 100);
                $item->totalExecutions      = rand(0, 100);
                $item->testCaseCoverage     = rand(0, 100);
                $item->totalActivatedBugs   = $product->stories['active'];
                $item->totalBugs            = $totalBugs;
                $item->fixedRate            = empty($totalBugs) ? 0 : round($product->fixedBugs / $totalBugs, 3) * 100;
                $item->totalReleases        = $product->releases;
                $item->latestReleaseDate    = '';
                $item->latestRelease        = '';

                $data[] = $item;
            }
        }
    }
}

$summary = sprintf($lang->product->lineSummary, $linesCount, count($productStats));
jsVar('summary', $summary);

set::title($lang->program->productView);

/* Layout. */

featureBar
(
    set::current($browseType),
    set::linkParams("status={key}&orderBy=$orderBy"),
    (hasPriv('product', 'batchEdit') && $hasProduct === true) ? item
    (
        set::type('checkbox'),
        set::text($lang->project->edit),
        set::checked($this->cookie->editProject)
    ) : NULL,
    li(searchToggle())
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
    item(set(array(
        'text' => $lang->program->createProduct,
        'icon' => 'plus',
        'class'=> 'btn secondary',
        'url'  => createLink('program', 'exportTable')
    ))),
    item(set(array(
        'text' => $lang->program->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => createLink('program', 'create')
    ))),
);

dtable
(
    set::cols($fnGenerateCols()),
    set::data($data),
    set::userMap($users),
    set::customCols(true),
    set::checkable(true),
    set::nested(true),
    set::className('shadow rounded'),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('window.renderReleaseCountCell')),
    set::footer(jsRaw('window.footerGenerator()'))
);

render();
