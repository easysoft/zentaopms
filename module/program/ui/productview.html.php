<?php
namespace zin;

$cols = array_values($config->program->productView->dtable->fieldList);

$totalStories = 0;
$hasProduct   = false;
$linesCount   = 0;
$data         = array();
foreach($productStructure as $programID => $program)
{
    /* TODO attach program lines */
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
    if(isset($program['programName']) and $config->systemMode == 'ALM')
    {
        $item = new stdClass();

        $item->programPM = '';
        if(!empty($program['programPM']))
        {
            $programPM = $program['programPM'];
            $userName  = zget($users, $programPM);

            $userID = isset($userIdPairs[$programPM]) ? $userIdPairs[$programPM] : '';

            $item->programPM = $userName;
            $item->PM        = $userName;
            $item->PMAccount = $userName;
            $item->PMAvatar  = $usersAvatar[$programPM];
        }

        $totalStories = $program['finishClosedStories'] + $program['unclosedStories'];

        $item->name             = $program['programName'];
        $item->id               = 'program-' . $programID;
        $item->type             = 'program';
        $item->level            = 1;
        $item->asParent         = true;
        $item->feedback         = rand(0, 100);
        $item->programName      = $program['programName'];
        $item->draftStories     = $program['draftStories'];
        $item->activeStories    = $program['activeStories'];
        $item->changingStories  = $program['changingStories'];
        $item->reviewingStories = $program['reviewingStories'];
        $item->closedReqRate    = ($totalStories == 0 ? 0 : round($program['finishClosedStories'] / $totalStories, 3) * 100);
        $item->unResolvedBugs   = $program['unResolvedBugs'];
        $item->fixedRate        = (($program['unResolvedBugs'] + $program['fixedBugs']) == 0 ? 0 : round($program['fixedBugs'] / ($program['unResolvedBugs'] + $program['fixedBugs']), 3) * 100);
        $item->plans            = $program['plans'];
        $item->releaseCount     = $program['releases'];
        $item->releaseCountOld  = rand(0, 10);
        $item->testCaseCoverage = rand(0, 100);
        $item->unclosedReqCount = rand(0, 100);
        $item->executionCount   = rand(0, 100);
        /* TODO attach extend fields. */

        $data[] = $item;
    }

    foreach($program as $lineID => $line)
    {
        /* ALM mode with Product Line. */
        if(isset($line['lineName']) and isset($line['products']) and is_array($line['products']) and $config->systemMode == 'ALM')
        {
            $totalStories = (isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) + (isset($line['unclosedStories']) ? $line['unclosedStories'] : 0);
            $linesCount++;

            $item = new stdClass();
            $item->name             = $line['lineName'];
            $item->id               = 'productLine-' . $lineID;
            $item->type             = 'productLine';
            $item->asParent         = true;
            $item->feedback         = rand(0, 100);
            $item->parent           = 'program-' . $programID;
            $item->programName      = $line['lineName'];
            $item->draftStories     = $line['draftStories'];
            $item->activeStories    = $line['activeStories'];
            $item->changingStories  = $line['changingStories'];
            $item->reviewingStories = $line['reviewingStories'];
            $item->closedReqRate    = ($totalStories == 0 ? 0 : round((isset($line['finishClosedStories']) ? $line['finishClosedStories'] : 0) / $totalStories, 3) * 100);
            $item->unResolvedBugs   = $line['unResolvedBugs'];
            $item->fixedRate        = ((isset($line['fixedBugs']) and ($line['unResolvedBugs'] + $line['fixedBugs'] != 0)) ? round($line['fixedBugs'] / ($line['unResolvedBugs'] + $line['fixedBugs']), 3) * 100 : 0);
            $item->plans            = $line['plans'];
            $item->releaseCount     = isset($line['releases']) ? $line['releases'] : 0;
            $item->releaseCountOld  = rand(0, 10);
            $item->testCaseCoverage = rand(0, 100);
            $item->unclosedReqCount = rand(0, 100);
            $item->executionCount   = rand(0, 100);
            /* TODO attach extend fields. */

            $data[] = $item;
        }

        /* Products of Product Line. */
        if(isset($line['products']) and is_array($line['products']))
        {
            foreach($line['products'] as $productID => $product)
            {
                $hasProduct = true;

                $item = new stdClass();

                if(!empty($product->PO))
                {
                    $item->PM               = zget($users, $product->PO);
                    $item->PMAvatar         = $usersAvatar[$product->PO];
                    $item->PMAccount        = $product->PO;
                }
                $totalStories = $product->stories['finishClosed'] + $product->stories['unclosed'];

                $item->name             = $product->name; /* TODO replace with <a> */
                $item->id               = $product->id;
                $item->type             = 'product';
                $item->programName      = $product->name; /* TODO replace with <a> */
                $item->feedback         = rand(0, 100);
                $item->draftStories     = $product->stories['draft'];
                $item->activeStories    = $product->stories['active'];
                $item->changingStories  = $product->stories['changing'];
                $item->reviewingStories = $product->stories['reviewing'];
                $item->closedReqRate    = ($totalStories == 0 ? 0 : round($product->stories['finishClosed'] / $totalStories, 3) * 100);
                $item->unResolvedBugs   = $product->unResolved;
                $item->fixedRate        = (($product->unResolved + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unResolved + $product->fixedBugs), 3) * 100);
                $item->plans            = $product->plans;
                $item->parent           = $product->line ? "productLine-$lineID" : ($product->program ? "program-$product->program" : '');
                $item->releaseCount     = $product->releases;
                $item->releaseCountOld  = rand(0, 10);
                $item->testCaseCoverage = rand(0, 100);
                $item->unclosedReqCount = rand(0, 100);
                $item->executionCount   = rand(0, 100);
                /* TODO attach extend fields. */

                $data[] = $item;
            }
        }
    }
}

$summary = sprintf($lang->product->lineSummary, $linesCount, count($productStats));

set::title($lang->program->productView);

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

js
(
<<<RENDERCELL
window.footerGenerator = function()
{
    return [{children: '{$summary}', className: "text-dark"}, "flex", "pager"];
}

window.renderReleaseCountCell = function(result, {col, row})
{
    if(col.name !== 'releaseCount') return result;

    var changed = row.data.releaseCount - row.data.releaseCountOld;

    if(changed === 0) result[0] = 0;
    if(changed > 0)   result[0] = {html: row.data.releaseCount + ' <span class="label size-sm circle primary-pale bd-primary">+' + changed + '</span>'};
    if(changed < 0)   result[0] = {html: row.data.releaseCount + ' <span class="label size-sm circle warning-pale bd-warning">' + changed + '</span>'};

    return result;
}
RENDERCELL
);

dtable
(
    set::className('shadow rounded'),
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::nested(true),
    set::onRenderCell(jsRaw('function(result, data){ return window.renderReleaseCountCell(result, data); }')),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
