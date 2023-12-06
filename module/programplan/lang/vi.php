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
$lang->programplan->list          = 'Stage List';
$lang->programplan->create        = 'Create';
$lang->programplan->edit          = 'Edit';
$lang->programplan->delete        = 'Delete Stage';
$lang->programplan->createSubPlan = 'Create Sub Plan';

$lang->programplan->parent           = 'Parent Stage';
$lang->programplan->emptyParent      = 'N/A';
$lang->programplan->name             = 'Stage Name';
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
$lang->programplan->version          = 'Version';
$lang->programplan->full             = 'Full Screen';
$lang->programplan->today            = 'Today';
$lang->programplan->exporting        = 'Exporting';
$lang->programplan->exportFail       = 'Export failed';
$lang->programplan->hideCriticalPath = 'Hide Critical Path';
$lang->programplan->showCriticalPath = 'Show Critical Path';

$lang->programplan->milestoneList[1] = 'Yes';
$lang->programplan->milestoneList[0] = 'No';

$lang->programplan->noData        = 'No Data';
$lang->programplan->children      = 'Sub Plan';
$lang->programplan->childrenAB    = 'Child';
$lang->programplan->confirmDelete = 'Do you want to delete the current plan?';
$lang->programplan->workloadTips  = 'The proportion of the sub stage workload is divided by 100%.';

$lang->programplan->stageCustom = new stdClass();
$lang->programplan->stageCustom->date = 'Show Date';
$lang->programplan->stageCustom->task = 'Show Task';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"Workload %" must be digits.';
$lang->programplan->error->planFinishSmall = 'The "End" date must be > the "Begin" date.';
$lang->programplan->error->percentOver     = 'The sum of "Workload %" cannot exceed 100% of one stage.';
$lang->programplan->error->createdTask     = 'The task has been decomposed. Sub phases cannot be added.';
$lang->programplan->error->parentWorkload  = 'The sum of the workload of the child phase cannot be greater than that of the parent phase: %s.';
$lang->programplan->error->sameType        = 'Type of the stage must be as same as parent: "%s"';
