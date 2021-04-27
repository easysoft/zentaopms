<?php
/**
 * The execution module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->execution->allExecutions   = 'All ' . $lang->executionCommon . 's';
$lang->execution->allExecutionAB  = 'All Executions';
$lang->execution->id              = $lang->executionCommon . ' ID';
$lang->execution->type            = $lang->executionCommon . 'Type';
$lang->execution->name            = $lang->executionCommon . 'Name';
$lang->execution->code            = $lang->executionCommon . 'Code';
$lang->execution->project         = 'Project';
$lang->execution->execName        = 'Execution Name';
$lang->execution->execCode        = 'Execution Code';
$lang->execution->execType        = 'Execution Type';
$lang->execution->stage           = 'Stage';
$lang->execution->pri             = 'Priority';
$lang->execution->openedBy        = 'OpenedBy';
$lang->execution->openedDate      = 'OpenedDate';
$lang->execution->closedBy        = 'ClosedBy';
$lang->execution->closedDate      = 'ClosedDate';
$lang->execution->canceledBy      = 'CanceledBy';
$lang->execution->canceledDate    = 'CanceledDate';
$lang->execution->begin           = 'Begin';
$lang->execution->end             = 'End';
$lang->execution->dateRange       = 'Duration';
$lang->execution->to              = 'To';
$lang->execution->days            = 'Available Days';
$lang->execution->day             = ' Days';
$lang->execution->workHour        = ' Hours';
$lang->execution->workHourUnit    = 'H';
$lang->execution->totalHours      = 'Available Hours';
$lang->execution->totalDays       = 'Available Days';
$lang->execution->status          = $lang->executionCommon . 'Status';
$lang->execution->execStatus      = 'Status';
$lang->execution->subStatus       = 'Sub Status';
$lang->execution->desc            = $lang->executionCommon . 'Description';
$lang->execution->execDesc        = 'Description';
$lang->execution->owner           = 'Owner';
$lang->execution->PO              = "{$lang->executionCommon} Owner";
$lang->execution->PM              = "{$lang->executionCommon} Manager";
$lang->execution->execPM          = "Execution Manager";
$lang->execution->QD              = 'Test Manager';
$lang->execution->RD              = 'Release Manager';
$lang->execution->release         = 'Release';
$lang->execution->acl             = 'Access Control';
$lang->execution->teamname        = 'Team Name';
$lang->execution->order           = "Rank {$lang->executionCommon}";
$lang->execution->orderAB         = "Rank";
$lang->execution->products        = "Link {$lang->productCommon}";
$lang->execution->whitelist       = 'Whitelist';
$lang->execution->addWhitelist    = 'Add Whitelist';
$lang->execution->unbindWhitelist = 'Remove Whitelist';
$lang->execution->totalEstimate   = 'Estimates';
$lang->execution->totalConsumed   = 'Cost';
$lang->execution->totalLeft       = 'Left';
$lang->execution->progress        = ' Progress';
$lang->execution->hours           = 'Estimates: %s, Cost: %s, Left: %s.';
$lang->execution->viewBug         = 'Bugs';
$lang->execution->noProduct       = "No {$lang->productCommon} yet.";
$lang->execution->createStory     = "Create Story";
$lang->execution->storyTitle      = "Story Name";
$lang->execution->all             = "All {$lang->executionCommon}s";
$lang->execution->undone          = 'Unfinished ';
$lang->execution->unclosed        = 'Unclosed';
$lang->execution->typeDesc        = "OPS {$lang->executionCommon} has no {$lang->SRCommon}, Bug, Build, or Test features.";
$lang->execution->mine            = 'Mine: ';
$lang->execution->involved        = 'Mine: ';
$lang->execution->other           = 'Others:';
$lang->execution->deleted         = 'Deleted';
$lang->execution->delayed         = 'Delayed';
$lang->execution->product         = $lang->execution->products;
$lang->execution->readjustTime    = "Adjust {$lang->executionCommon} Begin and End";
$lang->execution->readjustTask    = 'Adjust Task Begin and End';
$lang->execution->effort          = 'Effort';
$lang->execution->relatedMember   = 'Team';
$lang->execution->watermark       = 'Exported by ZenTao';
$lang->execution->burnXUnit       = '(Date)';
$lang->execution->burnYUnit       = '(Hours)';
$lang->execution->waitTasks       = 'Waiting Tasks';
$lang->execution->viewByUser      = 'By User';
$lang->execution->oneProduct      = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct   = "Stage not linked {$lang->productCommon}";
$lang->execution->recent          = 'Recent visits: ';
$lang->execution->copyNoExecution = 'There are no ' . $lang->executionCommon . 'available to copy.';

$lang->execution->start    = 'Start';
$lang->execution->activate = 'Activate';
$lang->execution->putoff   = 'Delay';
$lang->execution->suspend  = 'Suspend';
$lang->execution->close    = 'Close';
$lang->execution->export   = 'Export';

$lang->execution->endList[7]   = '1 Week';
$lang->execution->endList[14]  = '2 Weeks';
$lang->execution->endList[31]  = '1 Month';
$lang->execution->endList[62]  = '2 Months';
$lang->execution->endList[93]  = '3 Months';
$lang->execution->endList[186] = '6 Months';
$lang->execution->endList[365] = '1 Year';

$lang->execution->lifeTimeList['short'] = "Short-Term";
$lang->execution->lifeTimeList['long']  = "Long-Term";
$lang->execution->lifeTimeList['ops']   = "DevOps";

$lang->team = new stdclass();
$lang->team->account    = 'User';
$lang->team->role       = 'Role';
$lang->team->join       = 'Joined';
$lang->team->hours      = 'Hours/day';
$lang->team->days       = 'Day';
$lang->team->totalHours = 'Total Hours';

$lang->team->limited            = 'Limited User';
$lang->team->limitedList['yes'] = 'Yes';
$lang->team->limitedList['no']  = 'No';

$lang->execution->basicInfo = 'Basic Information';
$lang->execution->otherInfo = 'Other Information';

/* Field value list. */
$lang->execution->statusList['wait']      = 'Waiting';
$lang->execution->statusList['doing']     = 'Doing';
$lang->execution->statusList['suspended'] = 'Suspended';
$lang->execution->statusList['closed']    = 'Closed';

global $config;
if($config->systemMode == 'new')
{
    $lang->execution->aclList['private'] = 'Private (for team members and execution stakeholders)';
    $lang->execution->aclList['open']    = 'Inherited Execution ACL (for who can access the current execution)';
}
else
{
    $lang->execution->aclList['private'] = 'Private (for team members and execution stakeholders)';
    $lang->execution->aclList['open']    = "Public (Users who can visit {$lang->executionCommon} can access it.)";
}

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Method list. */
$lang->execution->index             = "{$lang->executionCommon} Home";
$lang->execution->task              = 'Task List';
$lang->execution->groupTask         = 'Group View';
$lang->execution->story             = 'Story List';
$lang->execution->qa                = 'QA';
$lang->execution->bug               = 'Bug List';
$lang->execution->testcase          = 'Testcase List';
$lang->execution->dynamic           = 'Dynamics';
$lang->execution->latestDynamic     = 'Dynamics';
$lang->execution->build             = 'Build List';
$lang->execution->testtask          = 'Request';
$lang->execution->burn              = 'Burndown';
$lang->execution->computeBurn       = 'Update';
$lang->execution->burnData          = 'Burndown Data';
$lang->execution->fixFirst          = 'Edit 1st-Day Estimates';
$lang->execution->team              = 'Members';
$lang->execution->doc               = 'Document';
$lang->execution->doclib            = 'Docoment Library';
$lang->execution->manageProducts    = 'Linked ' . $lang->productCommon . 's';
$lang->execution->linkStory         = 'Link Stories';
$lang->execution->linkStoryByPlan   = 'Link Stories By Plan';
$lang->execution->linkPlan          = 'Linked Plan';
$lang->execution->unlinkStoryTasks  = 'Unlink';
$lang->execution->linkedProducts    = "Linked {$lang->productCommon}s";
$lang->execution->unlinkedProducts  = "Unlinked {$lang->productCommon}s";
$lang->execution->view              = "Execution Detail";
$lang->execution->startAction       = "Start Execution";
$lang->execution->activateAction    = "Activate Execution";
$lang->execution->delayAction       = "Delay Execution";
$lang->execution->suspendAction     = "Suspend Execution";
$lang->execution->closeAction       = "Close Execution";
$lang->execution->testtaskAction    = "Execution Request";
$lang->execution->teamAction        = "Execution Members";
$lang->execution->kanbanAction      = "Execution Kanban";
$lang->execution->printKanbanAction = "Print Kanban";
$lang->execution->treeAction        = "Execution Tree View";
$lang->execution->exportAction      = "Export Execution";
$lang->execution->computeBurnAction = "Update Burndown";
$lang->execution->create            = "Create {$lang->executionCommon}";
$lang->execution->createExec        = "Create Execution";
$lang->execution->copyExec          = "Copy Execution";
$lang->execution->copy              = "Copy {$lang->executionCommon}";
$lang->execution->delete            = "Delete {$lang->executionCommon}";
$lang->execution->deleteAB          = "Delete Execution";
$lang->execution->browse            = "{$lang->executionCommon} List";
$lang->execution->edit              = "Edit {$lang->executionCommon}";
$lang->execution->editAction        = "Edit Execution";
$lang->execution->batchEdit         = "Edit";
$lang->execution->batchEditAction   = "Batch Edit";
$lang->execution->manageMembers     = 'Manage Team';
$lang->execution->unlinkMember      = 'Remove Member';
$lang->execution->unlinkStory       = 'Unlink Story';
$lang->execution->unlinkStoryAB     = 'Unlink';
$lang->execution->batchUnlinkStory  = 'Batch Unlink Stories';
$lang->execution->importTask        = 'Transfer Task';
$lang->execution->importPlanStories = 'Link Stories By Plan';
$lang->execution->importBug         = 'Import Bug';
$lang->execution->tree              = 'Tree';
$lang->execution->treeTask          = 'Show Task Only';
$lang->execution->treeStory         = 'Show Story Only';
$lang->execution->treeOnlyTask      = 'Show Task Only';
$lang->execution->treeOnlyStory     = 'Show Story Only';
$lang->execution->storyKanban       = 'Story Kanban';
$lang->execution->storySort         = 'Rank Story';
$lang->execution->importPlanStory   = $lang->executionCommon . ' is created!\nDo you want to import stories that have been linked to the plan?';
$lang->execution->iteration         = 'Iterations';
$lang->execution->iterationInfo     = '%s Iterations';
$lang->execution->viewAll           = 'View All';
$lang->execution->testreport        = 'Test Report';

/* Group browsing. */
$lang->execution->allTasks     = 'All';
$lang->execution->assignedToMe = 'My';
$lang->execution->myInvolved   = 'Involved';

$lang->execution->statusSelects['']             = 'More';
$lang->execution->statusSelects['wait']         = 'Waiting';
$lang->execution->statusSelects['doing']        = 'Doing';
$lang->execution->statusSelects['undone']       = 'Unfinished';
$lang->execution->statusSelects['finishedbyme'] = 'FinishedByMe';
$lang->execution->statusSelects['done']         = 'Done';
$lang->execution->statusSelects['closed']       = 'Closed';
$lang->execution->statusSelects['cancel']       = 'Cancelled';

$lang->execution->groups['']           = 'View by Groups';
$lang->execution->groups['story']      = 'Group by Story';
$lang->execution->groups['status']     = 'Group by Status';
$lang->execution->groups['pri']        = 'Group by Priority';
$lang->execution->groups['assignedTo'] = 'Group by AssignedTo';
$lang->execution->groups['finishedBy'] = 'Group by FinishedBy';
$lang->execution->groups['closedBy']   = 'Group by ClosedBy';
$lang->execution->groups['type']       = 'Group by Type';

$lang->execution->groupFilter['story']['all']         = 'All';
$lang->execution->groupFilter['story']['linked']      = 'Tasks linked to stories';
$lang->execution->groupFilter['pri']['all']           = 'All';
$lang->execution->groupFilter['pri']['noset']         = 'Not Set';
$lang->execution->groupFilter['assignedTo']['undone'] = 'Unfinished';
$lang->execution->groupFilter['assignedTo']['all']    = 'All';

$lang->execution->byQuery = 'Search';

/* Query condition list. */
$lang->execution->allExecution      = "All {$lang->executionCommon}s";
$lang->execution->aboveAllProduct   = "All the above {$lang->productCommon}s";
$lang->execution->aboveAllExecution = "All the above {$lang->executionCommon}s";

/* Page prompt. */
$lang->execution->linkStoryByPlanTips = "This action will link all stories in this plan to the {$lang->executionCommon}.";
$lang->execution->selectExecution     = "Select {$lang->executionCommon}";
$lang->execution->beginAndEnd         = 'Duration';
$lang->execution->lblStats            = 'Efforts';
$lang->execution->stats               = 'Available: <strong>%s</strong>(h). Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).';
$lang->execution->taskSummary         = "Total tasks on this page:<strong>%s</strong>. Waiting: <strong>%s</strong>. Doing: <strong>%s</strong>.  &nbsp;&nbsp;&nbsp;  Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).";
$lang->execution->pageSummary         = "Total tasks: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->execution->checkedSummary      = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->execution->memberHoursAB       = "%s has <strong>%s</ strong> hours.";
$lang->execution->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Available Hours</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tasks</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Doing</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Waiting</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimates</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Cost</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Left</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB      = "<div>Tasks <strong>%s ：</strong><span class='text-muted'>Waiting</span> %s &nbsp; <span class='text-muted'>Doing</span> %s</div><div>Estimates <strong>%s ：</strong><span class='text-muted'>Cost</span> %s &nbsp; <span class='text-muted'>Left</span> %s</div>";
$lang->execution->wbs                 = "Create Task";
$lang->execution->batchWBS            = "Batch Create Tasks";
$lang->execution->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='How to update the Burndown Chart?' class='btn btn-link'>Help <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories        = "No story can be linked. Please check whether there is any story in {$lang->executionCommon} which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->execution->productStories      = "Stories linked to {$lang->executionCommon} are the subeset of stories linked to {$lang->productCommon}. Stories can only be linked after they pass the review. <a href='%s'> Link Stories</a> now.";
$lang->execution->haveDraft           = "%s stories in draft, so they can't be linked.";
$lang->execution->doneExecutions      = 'Finished';
$lang->execution->selectDept          = 'Select Department';
$lang->execution->selectDeptTitle     = 'Select User';
$lang->execution->copyTeam            = 'Copy Team';
$lang->execution->copyFromTeam        = "Copy from {$lang->executionCommon} Team: <strong>%s</strong>";
$lang->execution->noMatched           = "No $lang->executionCommon including '%s'can be found.";
$lang->execution->copyTitle           = "Choose a {$lang->executionCommon} to copy.";
$lang->execution->copyTeamTitle       = "Choose a {$lang->executionCommon} Team to copy.";
$lang->execution->copyNoExecution     = "No {$lang->executionCommon} can be copied.";
$lang->execution->copyFromExecution   = "Copy from {$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy          = 'Cancel Copy';
$lang->execution->byPeriod            = 'By Time';
$lang->execution->byUser              = 'By User';
$lang->execution->noExecution         = "No {$lang->executionCommon}. ";
$lang->execution->noExecutions        = "No {$lang->execution->common}.";
$lang->execution->noMembers           = 'No team members yet. ';
$lang->execution->workloadTotal       = "The cumulative workload ratio should not exceed 100, and the total workload under the current product is: %s";
// $lang->execution->linkProjectStoryTip = "(Link {$lang->SRCommon} comes from {$lang->SRCommon} linked under the execution)";
$lang->execution->linkAllStoryTip     = "({$lang->SRCommon} has never been linked under the execution, and can be directly linked with {$lang->SRCommon} of the product linked with the sprint/stage)";

/* Interactive prompts. */
$lang->execution->confirmDelete             = "Do you want to delete the {$lang->executionCommon}[%s]?";
$lang->execution->confirmUnlinkMember       = "Do you want to unlink this User from {$lang->executionCommon}?";
$lang->execution->confirmUnlinkStory        = "Do you want to unlink this Story from {$lang->executionCommon}?";
$lang->execution->confirmUnlinkExecutionStory = "Do you want to unlink this Story from the execution?";
$lang->execution->notAllowedUnlinkStory     = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the execution. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts    = "The story of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts     = "No {$lang->productCommon} is linked to {$lang->executionCommon}. You will be directed to {$lang->productCommon} page to link one.";
$lang->execution->errorSameProducts         = "{$lang->executionCommon} cannot be linked to the same {$lang->productCommon} twice.";
$lang->execution->accessDenied              = "Your access to {$lang->executionCommon} is denied!";
$lang->execution->tips                      = 'Note';
$lang->execution->afterInfo                 = "{$lang->executionCommon} is created. Next you can ";
$lang->execution->setTeam                   = 'Set Team';
$lang->execution->linkStory                 = 'Link Story';
$lang->execution->createTask                = 'Create Task';
$lang->execution->goback                    = "Go Back";
$lang->execution->noweekend                 = 'Exclude Weekend';
$lang->execution->withweekend               = 'Include Weekend';
$lang->execution->interval                  = 'Intervals ';
$lang->execution->fixFirstWithLeft          = 'Update hours left too';
$lang->execution->unfinishedExecution         = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask            = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug             = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty           = 'Project cannot be empty.';

/* Statistics. */
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = " Burndown Chart";
$lang->execution->charts->burn->graph->xAxisName    = "Date";
$lang->execution->charts->burn->graph->yAxisName    = "Hour";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = 'Ideal';
$lang->execution->charts->burn->graph->actuality    = 'Actual';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = "Abbreviation of {$lang->executionCommon} name";
$lang->execution->placeholder->totalLeft = "Hours estimated on the first day of the {$lang->executionCommon}.";

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(Done)';

$lang->execution->orderList['order_asc']  = "Story Rank Ascending";
$lang->execution->orderList['order_desc'] = "Story Rank Descending";
$lang->execution->orderList['pri_asc']    = "Story Priority Ascending";
$lang->execution->orderList['pri_desc']   = "Story Priority Descending";
$lang->execution->orderList['stage_asc']  = "Story Phase Ascending";
$lang->execution->orderList['stage_desc'] = "Story Phase Descending";

$lang->execution->kanban        = "Kanban";
$lang->execution->kanbanSetting = "Settings";
$lang->execution->resetKanban   = "Reset";
$lang->execution->printKanban   = "Print";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Closed & Cancelled Columns';
$lang->execution->kanbanShowOption = 'Unfold';
$lang->execution->kanbanColsColor  = 'Customize Column Color';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = 'Do you want to reset Kanban?';
$lang->kanbanSetting->optionList['0'] = 'Hide';
$lang->kanbanSetting->optionList['1'] = 'Show';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'Print Kanban';
$lang->printKanban->content = 'Content';
$lang->printKanban->print   = 'Print';

$lang->printKanban->taskStatus = 'Status';

$lang->printKanban->typeList['all']       = 'All';
$lang->printKanban->typeList['increment'] = 'Increment';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = 'Stage';
$lang->execution->typeList['sprint'] = $lang->executionCommon;

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['delayed']      = 'Delayed';
$lang->execution->featureBar['task']['needconfirm']  = 'Changed';
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Expand All';
$lang->execution->treeLevel['root']  = 'Collapse All';
$lang->execution->treeLevel['task']  = 'Stories&Tasks';
$lang->execution->treeLevel['story'] = 'Only Stories';

$lang->execution->action = new stdclass();
$lang->execution->action->opened  = '$date, created by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->managed = '$date, managed by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->edited  = '$date, edited by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->extra   = 'Linked products is %s.';
