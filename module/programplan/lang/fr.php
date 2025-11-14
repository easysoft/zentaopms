<?php
/**
 * The programplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: en.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->programplan->common        = 'Program Plan';
$lang->programplan->browse        = 'Program Plan';
$lang->programplan->gantt         = 'Gantt Chart';
$lang->programplan->ganttEdit     = 'Gantt Edit';
$lang->programplan->list          = 'Stage List';
$lang->programplan->create        = 'Create';
$lang->programplan->edit          = 'Edit Stage';
$lang->programplan->delete        = 'Delete Stage';
$lang->programplan->close         = 'Close Stage';
$lang->programplan->activate      = 'Activate Stage';
$lang->programplan->createSubPlan = 'Create Sub Plan';
$lang->programplan->subPlanManage = 'Sub-stages management';
$lang->programplan->submit        = 'Submit';
$lang->programplan->idAB          = 'ID';

$lang->programplan->parent           = 'Parent Stage';
$lang->programplan->emptyParent      = 'N/A';
$lang->programplan->name             = 'Stage Name';
$lang->programplan->code             = 'Code';
$lang->programplan->status           = 'Stage Progress';
$lang->programplan->PM               = 'Stage Manager';
$lang->programplan->PMAB             = 'Owner';
$lang->programplan->acl              = 'Access Control';
$lang->programplan->subStageName     = 'Sub Stage Name';
$lang->programplan->percent          = 'Workload Ratio';
$lang->programplan->percentAB        = 'Workload Ratio';
$lang->programplan->planPercent      = 'Workload';
$lang->programplan->attribute        = 'Stage Type';
$lang->programplan->milestone        = 'Milestone';
$lang->programplan->taskProgress     = 'Task Progress';
$lang->programplan->task             = 'Task';
$lang->programplan->begin            = 'Begin';
$lang->programplan->end              = 'End';
$lang->programplan->realBegan        = 'Actual Started';
$lang->programplan->realEnd          = 'Actual End';
$lang->programplan->ac               = 'Actual cost';
$lang->programplan->sv               = 'Schedule Variance';
$lang->programplan->cv               = 'Cost Variance';
$lang->programplan->planDateRange    = 'Planned Start';
$lang->programplan->realDateRange    = 'Actual Start';
$lang->programplan->output           = 'Output';
$lang->programplan->openedBy         = 'Created By';
$lang->programplan->openedDate       = 'Created Date';
$lang->programplan->editedBy         = 'Edited By';
$lang->programplan->editedDate       = 'Edited Date';
$lang->programplan->duration         = 'Duration';
$lang->programplan->estimate         = 'Man-hours';
$lang->programplan->consumed         = 'Consumed';
$lang->programplan->version          = 'Version';
$lang->programplan->full             = 'Full Screen';
$lang->programplan->today            = 'Today';
$lang->programplan->exporting        = 'Exporting';
$lang->programplan->exportFail       = 'Export failed';
$lang->programplan->hideCriticalPath = 'Hide Critical Path';
$lang->programplan->showCriticalPath = 'Show Critical Path';
$lang->programplan->delay            = 'Delay';
$lang->programplan->delayDays        = 'Delay days';
$lang->programplan->settingGantt     = 'Gantt Setting';
$lang->programplan->viewSetting      = 'Setting';
$lang->programplan->desc             = 'Description';
$lang->programplan->wait             = 'Wait';
$lang->programplan->enabled          = 'Enabling Stage';
$lang->programplan->point            = 'Review Point';
$lang->programplan->progress         = 'Progress';

$lang->programplan->relation            = 'Manage Task Relation';
$lang->programplan->setTaskRelation     = 'Manage Task Relation';
$lang->programplan->viewTaskRelation    = 'View Task Relation';
$lang->programplan->createRelation      = 'Create Task Relation';
$lang->programplan->editRelation        = 'Manage Task Relation';
$lang->programplan->batchEditRelation   = 'Batch Manage Task Relation';
$lang->programplan->deleteRelation      = 'Delete Relation';
$lang->programplan->batchDeleteRelation = 'Batch Delete Relation';

$lang->programplan->errorBegin       = "{$lang->projectCommon} begin date: %s, begin date should be >= {$lang->projectCommon} begin date.";
$lang->programplan->errorEnd         = "{$lang->projectCommon} end date: %s, end date should be <= {$lang->projectCommon} end date.";
$lang->programplan->emptyBegin       = '『Begin』should not be blank';
$lang->programplan->emptyEnd         = '『End』should not be blank';
$lang->programplan->checkBegin       = '『Begin』should be valid date';
$lang->programplan->checkEnd         = '『End』should be valid date';
$lang->programplan->methodTip        = "You can choose to continue creating stages or {$lang->executionCommon}/Kanban for work in this stage. It's not supported to further split the {$lang->executionCommon}/Kanban.";
$lang->programplan->cropStageTip     = "Stages that have already started cannot be cropped";
$lang->programplan->childEnabledTip  = "Child stage enabled follows parent stage.";
$lang->programplan->reviewedPointTip = "The review point has been submitted for review and can no longer be operated.";
$lang->programplan->typeTip          = "The first level only supports creating stages, and stages can be created or {$lang->executionCommon}/Kanban can be created under the same stage. It's not supported to further split the {$lang->executionCommon}/Kanban.";

$lang->programplan->milestoneList[1] = 'Yes';
$lang->programplan->milestoneList[0] = 'No';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = 'Yes';
$lang->programplan->delayList[0] = 'No';

$lang->programplan->enabledList = array();
$lang->programplan->enabledList['on']  = 'On';
$lang->programplan->enabledList['off'] = 'Off';

$lang->programplan->typeList = array();
$lang->programplan->typeList['stage']     = 'Stage';
$lang->programplan->typeList['agileplus'] = $lang->executionCommon . '/Kanban';

$lang->programplan->noData            = 'No Data';
$lang->programplan->children          = 'Sub Plan';
$lang->programplan->childrenAB        = 'Child';
$lang->programplan->confirmDelete     = 'Do you want to delete the current plan?';
$lang->programplan->confirmChangeAttr = 'The type of the sub-stage will be adjusted to "%s" synchronously according to the type of the parent stage after modification. Do you want to save?';
$lang->programplan->noticeChangeAttr  = 'The type of the sub-stage will be adjusted to "%s" synchronously according to the type of the parent stage after modification.';
$lang->programplan->workloadTips      = 'The proportion of the sub stage workload is divided by 100%.';
$lang->programplan->emptyStageTip     = 'Please contact the administrator to set up the IPD stage list in the "Project Process Configuration" in the backend.';

$lang->programplan->stageCustom['date'] = 'Show Date';
$lang->programplan->stageCustom['task'] = 'Show Task';

$lang->programplan->ganttCustom['PM']           = 'Manager';
$lang->programplan->ganttCustom['deadline']     = 'Deadline';
$lang->programplan->ganttCustom['status']       = 'Status';
$lang->programplan->ganttCustom['realBegan']    = 'Actual Began';
$lang->programplan->ganttCustom['realEnd']      = 'Actual End';
$lang->programplan->ganttCustom['progress']     = 'Workload Ratio';
$lang->programplan->ganttCustom['taskProgress'] = 'Task Progress';
$lang->programplan->ganttCustom['estimate']     = 'Estimate';
$lang->programplan->ganttCustom['consumed']     = 'Consumed';
$lang->programplan->ganttCustom['delay']        = 'Delay';
$lang->programplan->ganttCustom['delayDays']    = 'Delay days';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"Workload %" must be non-negative';
$lang->programplan->error->planFinishSmall = 'The "End" date must be > the "Begin" date.';
$lang->programplan->error->percentOver     = 'The sum of "Workload %" cannot exceed 100% of one stage.';
$lang->programplan->error->createdTask     = 'The task has been decomposed. Sub phases cannot be added.';
$lang->programplan->error->parentWorkload  = 'The sum of the workload of the child phase cannot be greater than that of the parent phase: %s.';
$lang->programplan->error->letterParent    = "The planned start of the child stage cannot be less than the begin of parent stage: %s.";
$lang->programplan->error->greaterParent   = "The planned end of the child stage cannot be greater the end of parent stage: %s";
$lang->programplan->error->sameName        = 'Stage name cannot be the same!';
$lang->programplan->error->sameCode        = 'Stage code cannot be the same!';
$lang->programplan->error->taskDrag        = 'The %s task cannot be dragged';
$lang->programplan->error->planDrag        = 'The %s stage cannot be dragged';
$lang->programplan->error->notStage        = $lang->executionCommon . '/Kanban cannot create a sub stage.';
$lang->programplan->error->sameType        = 'Type of the stage must be as same as parent: "%s"';
$lang->programplan->error->emptyParentName = "Contains sub stages, stage names cannot be empty.";
$lang->programplan->error->noProject       = "When there are no waterfall, waterfall plus {$lang->projectCommon} in the system, Gantt charts cannot be added.";
$lang->programplan->error->noProject4IPD   = "When there are no waterfall, waterfall plus, or IPD {$lang->projectCommon} in the system, Gantt charts cannot be added.";

$lang->programplan->ganttBrowseType['gantt']       = 'Group by Stage';
$lang->programplan->ganttBrowseType['assignedTo']  = 'Group by AssignedTo';

$lang->programplan->reviewColorList['draft']     = '#FC913F';
$lang->programplan->reviewColorList['reviewing'] = '#CD6F27';
$lang->programplan->reviewColorList['pass']      = '#0DBB7D';
$lang->programplan->reviewColorList['fail']      = '#FB2B2B';
