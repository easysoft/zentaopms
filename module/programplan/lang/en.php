<?php
$lang->programplan->common = 'Program Plan';

$lang->programplan->browse        = 'Browse Program Plan';
$lang->programplan->gantt         = 'Gantt Chart';
$lang->programplan->list          = 'Stage List';
$lang->programplan->create        = 'Create';
$lang->programplan->edit          = 'Edit';
$lang->programplan->delete        = 'Delete';
$lang->programplan->createSubPlan = 'Create Sub Plan';

$lang->programplan->parent            = 'Parent Stage';
$lang->programplan->emptyParent       = 'Not Yet';
$lang->programplan->name              = 'Name';
$lang->programplan->percent           = 'Plan Workload';
$lang->programplan->percentAB         = 'Plan Workload';
$lang->programplan->planPercent       = 'Workload';
$lang->programplan->attribute         = 'Attribute';
$lang->programplan->milestone         = 'Milestone';
$lang->programplan->taskProgress      = 'Task Progress';
$lang->programplan->task              = 'Task';
$lang->programplan->begin             = 'Begin';
$lang->programplan->end               = 'Finish';
$lang->programplan->realStarted       = 'Actual Started';
$lang->programplan->realFinished      = 'Actual Finished';
$lang->programplan->output            = 'Output';
$lang->programplan->openedBy          = 'Created By';
$lang->programplan->openedDate        = 'Created Date';
$lang->programplan->editedBy          = 'Edited By';
$lang->programplan->editedDate        = 'Edited Date';
$lang->programplan->duration          = 'Duration';
$lang->programplan->version           = 'Version';
$lang->programplan->full              = 'Full Screen';
$lang->programplan->today             = 'Today';
$lang->programplan->exporting         = 'Export';
$lang->programplan->exportFail        = 'Export Fail';
$lang->programplan->hideCriticalPath  = 'Hide Critical Path';
$lang->programplan->showCriticalPath  = 'Show Critical Path';

$lang->programplan->milestoneList[1] = 'Yes';
$lang->programplan->milestoneList[0] = 'No';

$lang->programplan->noData        = 'No Data';
$lang->programplan->children      = 'Children';
$lang->programplan->childrenAB    = 'Children';
$lang->programplan->confirmDelete = 'Are you sure you want to delete the current plan?';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = 'Show Date';
$lang->programplan->stageCustom->task = 'Show Task';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"Workload Ration" must be number';
$lang->programplan->error->planFinishSmall = '"Finish" must greater than "Begin"';
$lang->programplan->error->percentOver     = 'The "Workload Ration" total cannot exceed 100%';
$lang->programplan->error->onlyOneDev      = 'Only one development type of phase plan can be set per phase plan';
$lang->programplan->error->createdTask     = 'The task has been decompose. Subphases cannot be added';
