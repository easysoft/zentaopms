<?php
/**
 * The programplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: en.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->programplan->common        = 'Program Plan';
$lang->programplan->browse        = 'Gantt Chart';
$lang->programplan->gantt         = 'Gantt Chart';
$lang->programplan->ganttEdit     = 'Gantt Edit';
$lang->programplan->list          = 'Stage List';
$lang->programplan->create        = 'Create';
$lang->programplan->edit          = 'Edit';
$lang->programplan->delete        = 'Delete Stage';
$lang->programplan->close         = 'Close Stage';
$lang->programplan->activate      = 'Activate Stage';
$lang->programplan->createSubPlan = 'Create Sub Stage';

$lang->programplan->parent           = 'Parent Stage';
$lang->programplan->emptyParent      = 'N/A';
$lang->programplan->name             = 'Stage Name';
$lang->programplan->status           = 'Stage Progress';
$lang->programplan->PM               = 'Stage Manager';
$lang->programplan->PMAB             = 'Owner';
$lang->programplan->acl              = 'Access Control';
$lang->programplan->subStageName     = 'Sub Stage Name';
$lang->programplan->percent          = 'Workload Ratio';
$lang->programplan->percentAB        = 'Ratio';
$lang->programplan->planPercent      = 'Workload';
$lang->programplan->attribute        = 'Type';
$lang->programplan->milestone        = 'Milestone';
$lang->programplan->taskProgress     = 'Task Progress';
$lang->programplan->task             = 'Task';
$lang->programplan->begin            = 'Begin';
$lang->programplan->end              = 'End';
$lang->programplan->realBegan        = 'Actual Start';
$lang->programplan->realEnd          = 'Actual End';
$lang->programplan->ac               = 'Actual Cost';
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
$lang->programplan->exportFail       = 'Failed';
$lang->programplan->hideCriticalPath = 'Hide Critical Path';
$lang->programplan->showCriticalPath = 'Show Critical Path';
$lang->programplan->delay            = 'Delay';
$lang->programplan->delayDays        = 'Delay days';

$lang->programplan->errorBegin       = "Project begin date: %s, begin date should be >= project begin date.";
$lang->programplan->errorEnd         = "Project end date: %s, end date should be <= project end date.";
$lang->programplan->emptyBegin       = '『Begin』should not be blank';
$lang->programplan->emptyEnd         = '『End』should not be blank';
$lang->programplan->checkBegin       = '『Begin』should be valid date';
$lang->programplan->checkEnd         = '『End』should be valid date';

$lang->programplan->milestoneList[1] = 'Yes';
$lang->programplan->milestoneList[0] = 'No';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = 'Yes';
$lang->programplan->delayList[0] = 'No';

$lang->programplan->noData        = 'No Data';
$lang->programplan->children      = 'Sub Plan';
$lang->programplan->childrenAB    = 'Child';
$lang->programplan->confirmDelete = 'Do you want to delete the current plan?';
$lang->programplan->workloadTips  = 'The workload of the sub stage is divided by 100%.';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = 'Show Date';
$lang->programplan->stageCustom->task = 'Show Task';

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
$lang->programplan->error->percentNumber   = '"Workload %" must be digits.';
$lang->programplan->error->planFinishSmall = 'The "End" date must be > the "Begin" date.';
$lang->programplan->error->percentOver     = 'The sum of "Workload %" cannot exceed 100%.';
$lang->programplan->error->createdTask     = 'The task is decomposed. Sub stages cannot be added.';
$lang->programplan->error->parentWorkload  = 'The sum of the workload in the sub stage cannot be > that in the parent stage: %s.';
$lang->programplan->error->parentDuration  = 'The planned start and planned completion of the child phase cannot exceed the parent phase.';
$lang->programplan->error->sameName        = 'Stage name cannot be the same!';
$lang->programplan->error->taskDrag        = 'The %s task cannot be dragged';
$lang->programplan->error->planDrag        = 'The %s stage cannot be dragged';

$lang->programplan->ganttBrowseType['gantt']       = 'Group by Stage';
$lang->programplan->ganttBrowseType['assignedTo']  = 'Group by AssignedTo';
