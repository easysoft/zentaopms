<?php
unset($lang->execution->featureBar['all']['undone']);
unset($lang->execution->featureBar['all']['wait']);
unset($lang->execution->featureBar['all']['suspended']);

$lang->execution->createKanban    = 'Create Kanban';
$lang->execution->noExecution     = "No Executions.";
$lang->execution->importTask      = 'Import Tsak';
$lang->execution->batchCreateTask = 'Batch Create Task';
$lang->execution->linkStory       = "Create {$lang->SRCommon}";

$lang->execution->kanbanGroup['default']    = 'Default';
$lang->execution->kanbanGroup['story']      = 'Target';
$lang->execution->kanbanGroup['module']     = 'Module';
$lang->execution->kanbanGroup['pri']        = 'Priority';
$lang->execution->kanbanGroup['assignedTo'] = 'Assignee';

$lang->execution->icons['kanban']    = 'kanban';
$lang->execution->icons['task']      = 'list';
$lang->execution->icons['calendar']  = 'calendar';
$lang->execution->icons['gantt']     = 'lane';
$lang->execution->icons['tree']      = 'treemap';
$lang->execution->icons['grouptask'] = 'sitemap';

$lang->execution->aclList['private'] = "Private (Accessible to team members and {$lang->projectCommon} leaders)";

$lang->execution->common = "{$lang->projectCommon} Execution";
