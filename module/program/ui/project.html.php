<?php
namespace zin;

jsVar('confirmDeleteTip', $lang->project->confirmDelete);

dropmenu();

featureBar
(
    set::current($browseType),
    set::linkParams("programID={$programID}&browseType={key}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    checkbox
    (
        setID('involved'),
        set::name('involved'),
        set::checked($this->cookie->involved),
        set::text($lang->project->mine)
    )
);

toolbar
(
    hasPriv('project', 'create') ? item(set
    (array(
        'text' => $lang->project->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => $this->createLink('project', 'createGuide', "programID={$programID}"),
        'data-toggle' => 'modal'
    ))) : null,
);

$cols = $this->config->project->dtable->fieldList;
$cols['actions']['list']['edit']['data-app'] = 'program';
$cols['progress']['sortType'] = false;

$projectStats = initTableData($projectStats, $cols, $this->project);

$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
$projectIdList  = array_column($projectStats, 'id');
$storyGroup     = $this->loadModel('story')->fetchStoriesByProjectIdList($projectIdList);
$executionGroup = $this->loadModel('execution')->fetchExecutionsByProjectIdList($projectIdList);
foreach($projectStats as $project)
{
    if($browseType == 'all')
    {
        if($project->status == 'wait')      $waitCount ++;
        if($project->status == 'doing')     $doingCount ++;
        if($project->status == 'suspended') $suspendedCount ++;
        if($project->status == 'closed')    $closedCount ++;
    }

    $projectStories = zget($storyGroup, $project->id, array());
    $project->storyCount  = count($projectStories);
    $project->storyPoints = round(array_sum(array_column($projectStories, 'estimate')), 2) . ' ' . $this->config->hourUnit;

    $executions = zget($executionGroup, $project->id, array());
    $project->executionCount = count($executions);

    if(!empty($project->delay) && $project->delay > 0)
    {
        $project->postponed = true;
        $project->delayInfo = sprintf($lang->project->delayInfo, $project->delay);
    }

    $project = $this->project->formatDataForList($project, $PMList);
}

$canBatchEdit = hasPriv('project', 'batchEdit');
$summary      = $browseType == 'all' ? sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount) : sprintf($lang->project->summary, count($projectStats));
$footToolbar  = $canBatchEdit ? array('items' => array(array('text' => $lang->edit, 'class' => 'btn batch-btn size-sm secondary', 'data-url' => $this->createLink('project', 'batchEdit', "from=pgmproject&programID={$programID}")))) : null;

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($projectStats)),
    set::checkable($canBatchEdit),
    set::nested(false),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::orderBy($orderBy),
    set::sortLink(createLink('program', 'project', "programID={$programID}&&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    !$canBatchEdit ? set::footer([jsRaw("function(){return {html: '{$summary}'};}"), 'flex', 'pager']) : null,
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList, '{$summary}');}"))
);

render();
