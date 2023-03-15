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

/* unset contribute and projectteam. */
unset($lang->block->default['full']['my']['9']);
unset($lang->block->default['full']['my']['6']);

$lang->block->default['full']['my']['5']['title']  = 'Kanban List';
$lang->block->default['full']['my']['5']['block']  = 'scrumlist';
$lang->block->default['full']['my']['5']['source'] = 'execution';

$lang->block->default['full']['my']['5']['params']['type']    = 'doing';
$lang->block->default['full']['my']['5']['params']['orderBy'] = 'id_desc';
$lang->block->default['full']['my']['5']['params']['count']   = '15';

$lang->block->modules['kanban']['index'] = new stdclass();
$lang->block->modules['kanban']['index']->availableBlocks = new stdclass();
$lang->block->modules['kanban']['index']->availableBlocks->scrumoverview  = "{$lang->projectCommon} Overview";
$lang->block->modules['kanban']['index']->availableBlocks->scrumlist      = $lang->executionCommon . ' List';
$lang->block->modules['kanban']['index']->availableBlocks->sprint         = $lang->executionCommon . ' Overview';
$lang->block->modules['kanban']['index']->availableBlocks->projectdynamic = 'Dynamics';

$lang->block->modules['project']->availableBlocks = new stdclass();
$lang->block->modules['project']->availableBlocks->project = "{$lang->projectCommon} List";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks = new stdclass();
$lang->block->modules['execution']->availableBlocks->statistic = $lang->execution->common . ' Statistics';
$lang->block->modules['execution']->availableBlocks->overview  = $lang->execution->common . ' Overview';
$lang->block->modules['execution']->availableBlocks->list      = $lang->execution->common . ' List';
$lang->block->modules['execution']->availableBlocks->task      = 'Task List';

unset($lang->block->moduleList['product']);
unset($lang->block->moduleList['qa']);
