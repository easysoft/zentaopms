<?php
/**
 * The programplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: en.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->programplan->common        = 'Program Plan';
$lang->programplan->browse        = 'Gantt Chart';
$lang->programplan->gantt         = 'Gantt Chart';
$lang->programplan->ganttEdit     = 'Edit Gantt Chart';
$lang->programplan->ganttExport   = 'Gantt Export';
$lang->programplan->list          = 'Phase List';
$lang->programplan->create        = 'Create Phase';
$lang->programplan->edit          = 'Edit Phase';
$lang->programplan->delete        = 'Delete Phase';
$lang->programplan->close         = 'Close Phase';
$lang->programplan->activate      = 'Activate Phase';
$lang->programplan->createSubPlan = 'Create Sub-phase';
$lang->programplan->subPlanManage = 'Sub-phase Management Type';
$lang->programplan->submit        = 'Submit Review';
$lang->programplan->idAB          = 'ID';

$lang->programplan->parent           = 'Parent Phase';
$lang->programplan->emptyParent      = 'N/A';
$lang->programplan->name             = 'Phase Name';
$lang->programplan->code             = 'Code';
$lang->programplan->status           = 'Phase Progress';
$lang->programplan->PM               = 'Phase Manager';
$lang->programplan->PMAB             = 'Manager';
$lang->programplan->acl              = 'Access Control';
$lang->programplan->subStageName     = 'Sub-phase Name';
$lang->programplan->percent          = 'Workload Ratio';
$lang->programplan->percentAB        = 'Workload Ratio';
$lang->programplan->planPercent      = 'Workload';
$lang->programplan->attribute        = 'Type';
$lang->programplan->milestone        = 'Milestone';
$lang->programplan->taskProgress     = 'Task Progress';
$lang->programplan->task             = 'Task';
$lang->programplan->begin            = 'Planned Start';
$lang->programplan->end              = 'Planned End';
$lang->programplan->realBegan        = 'Actual Start';
$lang->programplan->realEnd          = 'Actual End';
$lang->programplan->ac               = 'Actual Cost';
$lang->programplan->sv               = 'Schedule Variance';
$lang->programplan->cv               = 'Cost Variance';
$lang->programplan->planDateRange    = 'Planned Duration';
$lang->programplan->realDateRange    = 'Actual Duration';
$lang->programplan->output           = 'Output';
$lang->programplan->openedBy         = 'Creator';
$lang->programplan->openedDate       = 'Created on';
$lang->programplan->editedBy         = 'Edited by';
$lang->programplan->editedDate       = 'Edited on';
$lang->programplan->duration         = 'Available Workdays';
$lang->programplan->estimate         = 'Man Hours';
$lang->programplan->consumed         = 'Cost';
$lang->programplan->version          = 'Version';
$lang->programplan->createVersion    = 'Create Version';
$lang->programplan->editVersion      = 'Edit Version';
$lang->programplan->full             = 'Full Screen';
$lang->programplan->today            = 'Today';
$lang->programplan->exporting        = 'Export';
$lang->programplan->exportFail       = 'Failed';
$lang->programplan->hideCriticalPath = 'Hide Critical Path';
$lang->programplan->showCriticalPath = 'Show Critical Path';
$lang->programplan->delay            = 'Postponed';
$lang->programplan->delayDays        = 'Days Delayed';
$lang->programplan->settingGantt     = 'Set Gantt Chart';
$lang->programplan->viewSetting      = 'Settings';
$lang->programplan->desc             = 'Description';
$lang->programplan->wait             = 'Awaiting Submit';
$lang->programplan->enabled          = 'Enable Phase';
$lang->programplan->point            = 'Review Point';
$lang->programplan->progress         = 'Progress';

$lang->programplan->relation             = 'Manage Task Dependencies';
$lang->programplan->setTaskRelation      = 'Manage Task Dependencies';
$lang->programplan->viewTaskRelation     = 'View Task Dependencies';
$lang->programplan->createRelation       = 'Add Task Dependencies';
$lang->programplan->editRelation         = 'Manage Task Dependencies';
$lang->programplan->batchEditRelation    = 'Batch Manage Task Dependencies';
$lang->programplan->deleteRelation       = 'Delete Task Dependencies';
$lang->programplan->batchDeleteRelation  = 'Batch Delete Task Dependencies';
$lang->programplan->createGanttVersion   = 'Create Version';
$lang->programplan->editGanttVersion     = 'Edit Version';
$lang->programplan->deleteGanttVersion   = 'Delete Version';
$lang->programplan->diffGanttVersion     = 'Version comparison';
$lang->programplan->rollbackGanttVersion = 'Version Rollback';
$lang->programplan->deliverableVersion   = 'Deliverables & Baseline Version';
$lang->programplan->ganttVersion         = 'Plan Version';
$lang->programplan->tmpGanttVersion      = 'Temporary Version';
$lang->programplan->versionDisplay       = 'Version Display';

$lang->programplan->errorBegin       = "The phase start date cannot be earlier than the start date of its {$lang->projectCommon} %s.";
$lang->programplan->errorEnd         = "The phase end date cannot be later than the end date of its {$lang->projectCommon} %s.";
$lang->programplan->emptyBegin       = 'Planned Start date is required.';
$lang->programplan->emptyEnd         = 'Planned Complete date is required.';
$lang->programplan->checkBegin       = 'Planned Start must be a valid date.';
$lang->programplan->checkEnd         = 'Planned Complete must be a valid date.';
$lang->programplan->methodTip        = "You can create another phase or add a {lang->executionCommon} / Kanban board under this phase to organize work. {$lang->executionCommon}s and Kanban boards cannot be further subdivided.";
$lang->programplan->cropStageTip     = "You cannot trim a phase that has already started.";
$lang->programplan->childEnabledTip  = "Sub-phases inherit the status of their parent phase.";
$lang->programplan->reviewedPointTip = "This review point has already been submitted for review and cannot be modified.";
$lang->programplan->typeTip          = "At the top level, you can only create phases. Within a phase, you may create sub-phases or {$lang->executionCommon} / Kanban boards. {$lang->executionCommon}s and Kanban boards cannot be further subdivided.";
$lang->programplan->rollbackTip      = 'New executions and tasks will be deleted, deleted ones will be restored, and only some fields will be rolled back. This operation will overwrite the current schedule and cannot be recovered. Please proceed with caution. Continue?';
$lang->programplan->rollbackTip4IPD  = 'New executions and tasks will be deleted, deleted ones will be restored, and only some fields will be rolled back, and some TR and DCP review points need to be resubmitted. This operation will overwrite the current schedule and cannot be recovered. Please proceed with caution. Continue?';
$lang->programplan->canNotCallback   = 'Unable to rollback. The execution plan dates would exceed the project plan dates. Please adjust the project plan dates first.';
$lang->programplan->frozenCallback   = 'Version rollback is not allowed after the stage has been baselined.';

$lang->programplan->milestoneList[1] = 'Yes';
$lang->programplan->milestoneList[0] = 'No';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = 'Yes';
$lang->programplan->delayList[0] = 'No';

$lang->programplan->enabledList = array();
$lang->programplan->enabledList['on']  = 'Enable';
$lang->programplan->enabledList['off'] = 'Disable';

$lang->programplan->typeList = array();
$lang->programplan->typeList['stage']     = 'Phase';
$lang->programplan->typeList['agileplus'] = $lang->executionCommon . '/Kanban';

$lang->programplan->noData            = 'No data available yet.';
$lang->programplan->children          = 'Sub-plan';
$lang->programplan->childrenAB        = 'Sub';
$lang->programplan->confirmDelete     = 'Are you sure you want to delete the plan?';
$lang->programplan->confirmChangeAttr = 'The sub‑phase type will be automatically updated to match the parent phase type %s. Do you want to save the changes?';
$lang->programplan->noticeChangeAttr  = 'The sub‑phase type will be automatically updated to match the parent phase type %s. ';
$lang->programplan->noticeDiffVersion = 'When comparing versions, the left version is displayed in normal color, and the right version is displayed in dark gray color.';
$lang->programplan->workloadTips      = 'The sub‑phase workload will be split proportionally to total 100%.';
$lang->programplan->emptyStageTip     = 'Please contact the administrator to set up the IPD stage list in the "Project Process Configuration" in the backend.';

$lang->programplan->stageCustom['date'] = 'Show Date';
$lang->programplan->stageCustom['task'] = 'Show Task';

$lang->programplan->ganttCustom['ownerID']        = 'Manager';
$lang->programplan->ganttCustom['status']         = 'Status';
$lang->programplan->ganttCustom['begin']          = 'Begin';
$lang->programplan->ganttCustom['deadline']       = 'Deadline';
$lang->programplan->ganttCustom['realBegan']      = 'Actual Began';
$lang->programplan->ganttCustom['realEnd']        = 'Actual End';
$lang->programplan->ganttCustom['duration']       = 'Duration';
$lang->programplan->ganttCustom['progress']       = 'Workload Ratio';
$lang->programplan->ganttCustom['taskProgress']   = 'Task Progress';
$lang->programplan->ganttCustom['estimate']       = 'Estimate';
$lang->programplan->ganttCustom['consumed']       = 'Consumed';
$lang->programplan->ganttCustom['left']           = 'Left';
$lang->programplan->ganttCustom['delay']          = 'Delay';
$lang->programplan->ganttCustom['delayDays']      = 'Delay days';
$lang->programplan->ganttCustom['taskType']       = 'Type';
$lang->programplan->ganttCustom['openedBy']       = 'Creator';
$lang->programplan->ganttCustom['openedDate']     = 'Created Date';
$lang->programplan->ganttCustom['assignedDate']   = 'Assigned Date';
$lang->programplan->ganttCustom['finishedBy']     = 'Finished By';
$lang->programplan->ganttCustom['closedBy']       = 'Closed By';
$lang->programplan->ganttCustom['closedDate']     = 'Closed Date';
$lang->programplan->ganttCustom['closedReason']   = 'Closed Reason';
$lang->programplan->ganttCustom['canceledBy']     = 'Canceled By';
$lang->programplan->ganttCustom['canceledDate']   = 'Canceled Date';
$lang->programplan->ganttCustom['lastEditedBy']   = 'Last Edited By';
$lang->programplan->ganttCustom['lastEditedDate'] = 'Last Edited Date';
$lang->programplan->ganttCustom['activatedDate']  = 'Activated Date';
$lang->programplan->ganttCustom['story']          = 'Story';
$lang->programplan->ganttCustom['keywords']       = 'Keywords';
$lang->programplan->ganttCustom['mailto']         = 'Mail To';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = 'The workload ratio must be a numeric value.';
$lang->programplan->error->planFinishSmall = 'The planned end time must be later than the planned start time.';
$lang->programplan->error->percentOver     = 'The total workload ratio of all sub‑phases under the same parent phase must not exceed 100%.';
$lang->programplan->error->createdTask     = 'This phase has decomposed tasks. Sub‑phases cannot be added.';
$lang->programplan->error->parentWorkload  = 'The total workload of all sub‑phases cannot exceed the parent phase’s workload: %s.';
$lang->programplan->error->letterParent    = "A sub‑phase’s planned start time cannot be earlier than the parent phase’s planned start time %s.";
$lang->programplan->error->greaterParent   = "A sub‑phase’s planned end time cannot exceed the parent phase’s planned end time %s.";
$lang->programplan->error->sameName        = 'Phase names must be unique.';
$lang->programplan->error->sameCode        = 'Phase codes must be unique.';
$lang->programplan->error->taskDrag        = 'Tasks under %s cannot be moved.';
$lang->programplan->error->planDrag        = 'Phases under %s cannot be moved.';
$lang->programplan->error->notStage        = 'Sub‑phases cannot be created on the '. $lang->executionCommon . '/ Kanban board.';
$lang->programplan->error->sameType        = 'The parent phase type is %s. The current phase type must match the parent phase.';
$lang->programplan->error->emptyParentName = "The phase contains sub‑phases, and the sub-stage name cannot be empty.";
$lang->programplan->error->noProject       = "A Gantt chart cannot be added because no Waterfall or Waterfal + {$lang->projectCommon} exists in the system.";
$lang->programplan->error->noProject4IPD   = "A Gantt chart cannot be added because no Waterfall, Waterfal +, or IPD {$lang->projectCommon} exists in the system.";

$lang->programplan->ganttBrowseType['gantt']      = 'Group by Phase';
$lang->programplan->ganttBrowseType['assignedTo'] = 'Group by Assignee';
$lang->programplan->ganttBrowseType['type']       = 'Group by Type';
$lang->programplan->ganttBrowseType['module']     = 'Group by Module';
$lang->programplan->ganttBrowseType['story']      = "Group by {$lang->SRCommon}";
$lang->programplan->ganttBrowseType['status']     = 'Group by Status';
$lang->programplan->ganttBrowseType['pri']        = 'Group by Pri';
$lang->programplan->ganttBrowseType['finishedBy'] = 'Group by FinishedBy';
$lang->programplan->ganttBrowseType['closedBy']   = 'Group by ClosedBy';

$lang->programplan->reviewColorList['draft']     = '#FC913F';
$lang->programplan->reviewColorList['reviewing'] = '#CD6F27';
$lang->programplan->reviewColorList['pass']      = '#0DBB7D';
$lang->programplan->reviewColorList['fail']      = '#FB2B2B';
