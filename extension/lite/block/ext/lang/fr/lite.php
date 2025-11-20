<?php
$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('Administrator', 'Add Departments', 'Add Users', 'Maintain Privileges');
$lang->block->flowchart['project'] = array('Project Manager', 'Add projects', 'Maintain Teams', 'Maintain Target', 'Create Kanban');
$lang->block->flowchart['dev']     = array('Execution Team', 'Create Tasks', 'Claim Tasks', 'Execution Tasks');

$lang->block->undone   = 'Undone';
$lang->block->delaying = 'Delaying';
$lang->block->delayed  = 'Delayed';

$lang->block->titleList['scrumlist'] = 'Kanban List';
$lang->block->titleList['sprint']    = 'Kanban Overview';

$lang->block->myTask = 'My Task';

$lang->block->finishedTasks = 'Tasks Finished';

$lang->block->story = 'Target';

$lang->block->storyCount = 'Target Count';

$lang->block->projectstatistic->story = 'Target';

$lang->block->default['full']['my'][] = array('title' => 'Kanban List', 'module' => 'execution', 'code' => 'scrumlist', 'width' => '2', 'height' => '6', 'left' => '0', 'top' => '45', 'params' => array('type' => 'doing', 'orderBy' => 'id_desc', 'count' => '15'));

$lang->block->modules['kanban'] = new stdclass();
$lang->block->modules['kanban']->availableBlocks['scrumoverview']  = "{$lang->projectCommon} Overview";
$lang->block->modules['kanban']->availableBlocks['scrumlist']      = $lang->executionCommon . ' List';
$lang->block->modules['kanban']->availableBlocks['sprint']         = $lang->executionCommon . ' Overview';
$lang->block->modules['kanban']->availableBlocks['projectdynamic'] = 'Dynamics';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['project'] = "{$lang->projectCommon} List";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . ' Statistics';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . ' Overview';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . ' List';
$lang->block->modules['execution']->availableBlocks['task']      = 'Task List';

unset($lang->block->moduleList['product']);
unset($lang->block->moduleList['qa']);

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task'] = 'Task';

$lang->block->summary->welcome    = 'Zentao has been with you for %s: ';
$lang->block->summary->yesterday  = '<strong>Yesterday</strong>';
$lang->block->summary->noWork     = 'You have not yet processed tasks and bugs,';
$lang->block->summary->finishTask = 'finished <a href="' . helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a> tasks';
$lang->block->summary->fixBug     = '';
