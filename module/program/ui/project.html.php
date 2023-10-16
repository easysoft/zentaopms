<?php
namespace zin;

jsVar('confirmDeleteTip', $lang->project->confirmDelete);

dropmenu();

featureBar
(
    set::current($browseType),
    set::linkParams("programID={$programID}&browseType={key}&orderBy=$orderBy"),
    checkbox
    (
        set::id('involved'),
        set::name('involved'),
        set::checked($this->cookie->involved),
        set::text($lang->project->mine),
    ),
);

toolbar
(
    common::hasPriv('project', 'create') ? item(set
    (array(
        'text' => $lang->project->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => $this->createLink('project', 'createGuide', "programID={$programID}"),
        'data-toggle' => 'modal',
    ))) : null,
);

$cols         = $this->config->project->dtable->fieldList;
$projectStats = initTableData($projectStats, $cols, $this->project);

$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
foreach($projectStats as $project)
{
    if($project->status == 'wait')      $waitCount ++;
    if($project->status == 'doing')     $doingCount ++;
    if($project->status == 'suspended') $suspendedCount ++;
    if($project->status == 'closed')    $closedCount ++;

    $projectStories = $this->loadModel('story')->getExecutionStoryPairs($project->id);
    $project->storyCount = count($projectStories);

    $executions = $this->loadModel('execution')->getStatData($project->id, 'all');
    $project->executionCount = count($executions);

    $project = $this->project->formatDataForList($project, $PMList);
}

$summary     = $browseType == 'all' ? sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount) : sprintf($lang->project->summary, count($projectStats));
$footToolbar = common::hasPriv('project', 'batchEdit') ? array('items' => array(array('text' => $lang->edit, 'class' => 'btn batch-btn size-sm secondary', 'data-url' => $this->createLink('project', 'batchEdit', "from=pgmproject&programID={$programID}")))) : null;

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data(array_values($projectStats)),
    set::nested(false),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList, '{$summary}');}")),
);

render();
