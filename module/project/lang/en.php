<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->project->common        = $lang->projectCommon;
$lang->project->allProjects   = 'All ' . $lang->projectCommon . 's';
$lang->project->id            = $lang->projectCommon . ' ID';
$lang->project->type          = 'Type';
$lang->project->name          = "{$lang->projectCommon} Name";
$lang->project->code          = 'Code';
$lang->project->statge        = 'Stage';
$lang->project->pri           = 'Priority';
$lang->project->openedBy      = 'OpenedBy';
$lang->project->openedDate    = 'OpenedDate';
$lang->project->closedBy      = 'ClosedBy';
$lang->project->closedDate    = 'ClosedDate';
$lang->project->canceledBy    = 'CanceledBy';
$lang->project->canceledDate  = 'CanceledDate';
$lang->project->begin         = 'Begin';
$lang->project->end           = 'End';
$lang->project->dateRange     = 'Duration';
$lang->project->to            = 'To';
$lang->project->days          = 'Available Days';
$lang->project->day           = ' Days';
$lang->project->workHour      = ' Hours';
$lang->project->totalHours    = 'Available Hours';
$lang->project->totalDays     = 'Available Days';
$lang->project->status        = 'Status';
$lang->project->subStatus     = 'Sub Status';
$lang->project->desc          = 'Description';
$lang->project->owner         = 'Owner';
$lang->project->PO            = "{$lang->projectCommon} Owner";
$lang->project->PM            = "{$lang->projectCommon} Manager";
$lang->project->QD            = 'Test Manager';
$lang->project->RD            = 'Release Manager';
$lang->project->qa            = 'Test';
$lang->project->release       = 'Release';
$lang->project->acl           = 'Access Control';
$lang->project->teamname      = 'Team Name';
$lang->project->order         = "Rank {$lang->projectCommon}";
$lang->project->orderAB       = "Rank";
$lang->project->products      = "Link {$lang->productCommon}";
$lang->project->whitelist     = 'Whitelist';
$lang->project->totalEstimate = 'Estimates';
$lang->project->totalConsumed = 'Cost';
$lang->project->totalLeft     = 'Left';
$lang->project->progress      = ' Progress';
$lang->project->hours         = 'Estimates: %s, Cost: %s, Left: %s.';
$lang->project->viewBug       = 'Bugs';
$lang->project->noProduct     = "No {$lang->productCommon} yet.";
$lang->project->createStory   = "Create Story";
$lang->project->all           = "All {$lang->projectCommon}s";
$lang->project->undone        = 'Unfinished ';
$lang->project->unclosed      = 'Unclosed';
$lang->project->typeDesc      = "OPS {$lang->projectCommon} has no {$lang->storyCommon}, Bug, Build, or Test features.";
$lang->project->mine          = 'Mine: ';
$lang->project->other         = 'Others:';
$lang->project->deleted       = 'Deleted';
$lang->project->delayed       = 'Delayed';
$lang->project->product       = $lang->project->products;
$lang->project->readjustTime  = "Adjust {$lang->projectCommon} Begin and End";
$lang->project->readjustTask  = 'Adjust Task Begin and End';
$lang->project->effort        = 'Effort';
$lang->project->relatedMember = 'Team';
$lang->project->watermark     = 'Exported by ZenTao';
$lang->project->viewByUser    = 'By User';

$lang->project->start    = 'Start';
$lang->project->activate = 'Activate';
$lang->project->putoff   = 'Delay';
$lang->project->suspend  = 'Suspend';
$lang->project->close    = 'Close';
$lang->project->export   = 'Export';

$lang->project->typeList['sprint']    = 'Sprint';
$lang->project->typeList['waterfall'] = 'Waterfall';
$lang->project->typeList['ops']       = 'OPS';

$lang->project->endList[7]   = '1 Week';
$lang->project->endList[14]  = '2 Weeks';
$lang->project->endList[31]  = '1 Month';
$lang->project->endList[62]  = '2 Months';
$lang->project->endList[93]  = '3 Months';
$lang->project->endList[186] = '6 Months';
$lang->project->endList[365] = '1 Year';

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

$lang->project->basicInfo = 'Basic Information';
$lang->project->otherInfo = 'Other Information';

/* Field value list. */
$lang->project->statusList['wait']      = 'Waiting';
$lang->project->statusList['doing']     = 'Doing';
$lang->project->statusList['suspended'] = 'Suspended';
$lang->project->statusList['closed']    = 'Closed';

$lang->project->aclList['open']    = "Default (Users who can visit {$lang->projectCommon} can access it.)";
$lang->project->aclList['private'] = 'Private (For team members only.)';
$lang->project->aclList['custom']  = 'Custom (Team members and the whitelist users can access it.)';

/* Method list. */
$lang->project->index             = "{$lang->projectCommon} Home";
$lang->project->task              = 'Task List';
$lang->project->groupTask         = 'Group View';
$lang->project->story             = 'Story List';
$lang->project->bug               = 'Bug List';
$lang->project->dynamic           = 'Dynamics';
$lang->project->latestDynamic     = 'Dynamics';
$lang->project->build             = 'Build List';
$lang->project->testtask          = 'Request';
$lang->project->burn              = 'Burndown';
$lang->project->computeBurn       = 'Update';
$lang->project->burnData          = 'Burndown Data';
$lang->project->fixFirst          = 'Edit 1st-Day Estimates';
$lang->project->team              = 'Members';
$lang->project->doc               = 'Document';
$lang->project->doclib            = 'Docoment Library';
$lang->project->manageProducts    = 'Linked ' . $lang->productCommon . 's';
$lang->project->linkStory         = 'Link Stories';
$lang->project->linkStoryByPlan   = 'Link Stories By Plan';
$lang->project->linkPlan          = 'Linked Plan';
$lang->project->unlinkStoryTasks  = 'Unlink';
$lang->project->linkedProducts    = "Linked {$lang->productCommon}s";
$lang->project->unlinkedProducts  = "Unlinked {$lang->productCommon}s";
$lang->project->view              = "{$lang->projectCommon} Detail";
$lang->project->startAction       = "Start {$lang->projectCommon}";
$lang->project->activateAction    = "Activate {$lang->projectCommon}";
$lang->project->delayAction       = "Delay {$lang->projectCommon}";
$lang->project->suspendAction     = "Suspend {$lang->projectCommon}";
$lang->project->closeAction       = "Close {$lang->projectCommon}";
$lang->project->testtaskAction    = "{$lang->projectCommon} Request";
$lang->project->teamAction        = "{$lang->projectCommon} Members";
$lang->project->kanbanAction      = "{$lang->projectCommon} Kanban";
$lang->project->printKanbanAction = "Print Kanban";
$lang->project->treeAction        = "{$lang->projectCommon} Tree View";
$lang->project->exportAction      = "Export {$lang->projectCommon}";
$lang->project->computeBurnAction = "Update Burndown";
$lang->project->create            = "Create {$lang->projectCommon}";
$lang->project->copy              = "Copy {$lang->projectCommon}";
$lang->project->delete            = "Delete {$lang->projectCommon}";
$lang->project->browse            = "{$lang->projectCommon} List";
$lang->project->edit              = "Edit {$lang->projectCommon}";
$lang->project->batchEdit         = "Batch Edit";
$lang->project->manageMembers     = 'Manage Team';
$lang->project->unlinkMember      = 'Remove Member';
$lang->project->unlinkStory       = 'Unlink Story';
$lang->project->unlinkStoryAB     = 'Unlink';
$lang->project->batchUnlinkStory  = 'Batch Unlink Stories';
$lang->project->importTask        = 'Transfer Task';
$lang->project->importPlanStories = 'Link Stories By Plan';
$lang->project->importBug         = 'Import Bug';
$lang->project->updateOrder       = "Rank {$lang->projectCommon}";
$lang->project->tree              = 'Tree';
$lang->project->treeTask          = 'Show Task Only';
$lang->project->treeStory         = 'Show Story Only';
$lang->project->treeOnlyTask      = 'Show Task Only';
$lang->project->treeOnlyStory     = 'Show Story Only';
$lang->project->storyKanban       = 'Story Kanban';
$lang->project->storySort         = 'Rank Story';
$lang->project->importPlanStory   = $lang->projectCommon . ' is created!\nDo you want to import stories that have been linked to the plan?';
$lang->project->iteration         = 'Iterations';
$lang->project->iterationInfo     = '%s Iterations';
$lang->project->viewAll           = 'View All';

/* Group browsing. */
$lang->project->allTasks     = 'All';
$lang->project->assignedToMe = 'My';
$lang->project->myInvolved   = 'Involved';

$lang->project->statusSelects['']             = 'More';
$lang->project->statusSelects['wait']         = 'Waiting';
$lang->project->statusSelects['doing']        = 'Doing';
$lang->project->statusSelects['undone']       = 'Unfinished';
$lang->project->statusSelects['finishedbyme'] = 'FinishedByMe';
$lang->project->statusSelects['done']         = 'Done';
$lang->project->statusSelects['closed']       = 'Closed';
$lang->project->statusSelects['cancel']       = 'Cancelled';

$lang->project->groups['']           = 'View by Groups';
$lang->project->groups['story']      = 'Group by Story';
$lang->project->groups['status']     = 'Group by Status';
$lang->project->groups['pri']        = 'Group by Priority';
$lang->project->groups['assignedTo'] = 'Group by AssignedTo';
$lang->project->groups['finishedBy'] = 'Group by FinishedBy';
$lang->project->groups['closedBy']   = 'Group by ClosedBy';
$lang->project->groups['type']       = 'Group by Type';

$lang->project->groupFilter['story']['all']         = 'All';
$lang->project->groupFilter['story']['linked']      = 'Tasks linked to stories';
$lang->project->groupFilter['pri']['all']           = 'All';
$lang->project->groupFilter['pri']['noset']         = 'Not Set';
$lang->project->groupFilter['assignedTo']['undone'] = 'Unfinished';
$lang->project->groupFilter['assignedTo']['all']    = 'All';

$lang->project->byQuery = 'Search';

/* Query condition list. */
$lang->project->allProject      = "All {$lang->projectCommon}s";
$lang->project->aboveAllProduct = "All the above {$lang->productCommon}s";
$lang->project->aboveAllProject = "All the above {$lang->projectCommon}s";

/* Page prompt. */
$lang->project->linkStoryByPlanTips = "This action will link all stories in this plan to the {$lang->projectCommon}.";
$lang->project->selectProject       = "Select {$lang->projectCommon}";
$lang->project->beginAndEnd         = 'Duration';
$lang->project->begin               = 'Begin';
$lang->project->end                 = 'End';
$lang->project->lblStats            = 'Efforts';
$lang->project->stats               = 'Available: <strong>%s</strong>(h). Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).';
$lang->project->taskSummary         = "Total tasks on this page:<strong>%s</strong>. Waiting: <strong>%s</strong>. Doing: <strong>%s</strong>.  &nbsp;&nbsp;&nbsp;  Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).";
$lang->project->pageSummary         = "Total tasks: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->project->checkedSummary      = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->project->memberHoursAB       = "%s has <strong>%s</ strong> hours.";
$lang->project->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Available Hours</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tasks</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Doing</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">Waiting</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimates</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Cost</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Left</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB      = "<div>Tasks <strong>%s ：</strong><span class='text-muted'>Waiting</span> %s &nbsp; <span class='text-muted'>Doing</span> %s</div><div>Estimates <strong>%s ：</strong><span class='text-muted'>Cost</span> %s &nbsp; <span class='text-muted'>Left</span> %s</div>";
$lang->project->wbs                 = "Create Task";
$lang->project->batchWBS            = "Batch Create Tasks";
$lang->project->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='How to update the Burndown Chart?' class='btn btn-link'>Help <i class='icon icon-help'></i></a>";
$lang->project->whyNoStories        = "No story can be linked. Please check whether there is any story in {$lang->projectCommon} which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->project->productStories      = "Stories linked to {$lang->projectCommon} are the subeset of stories linked to {$lang->productCommon}. Stories can only be linked after they pass the review. <a href='%s'> Link Stories</a> now.";
$lang->project->haveDraft           = "%s stories in draft, so they can't be linked.";
$lang->project->doneProjects        = 'Finished';
$lang->project->selectDept          = 'Select Department';
$lang->project->selectDeptTitle     = 'Select User';
$lang->project->copyTeam            = 'Copy Team';
$lang->project->copyFromTeam        = "Copy from {$lang->projectCommon} Team: <strong>%s</strong>";
$lang->project->noMatched           = "No $lang->projectCommon including '%s'can be found.";
$lang->project->copyTitle           = "Choose a {$lang->projectCommon} to copy.";
$lang->project->copyTeamTitle       = "Choose a {$lang->projectCommon} Team to copy.";
$lang->project->copyNoProject       = "No {$lang->projectCommon} can be copied.";
$lang->project->copyFromProject     = "Copy from {$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy          = 'Cancel Copy';
$lang->project->byPeriod            = 'By Time';
$lang->project->byUser              = 'By User';
$lang->project->noProject           = "No {$lang->projectCommon}. ";
$lang->project->noMembers           = 'No team members yet. ';

/* Interactive prompts. */
$lang->project->confirmDelete         = "Do you want to delete the {$lang->projectCommon}[%s]?";
$lang->project->confirmUnlinkMember   = "Do you want to unlink this User from {$lang->projectCommon}?";
$lang->project->confirmUnlinkStory    = "Do you want to unlink this Story from {$lang->projectCommon}?";
$lang->project->errorNoLinkedProducts = "No {$lang->productCommon} is linked to {$lang->projectCommon}. You will be directed to {$lang->productCommon} page to link one.";
$lang->project->errorSameProducts     = "{$lang->projectCommon} cannot be linked to the same {$lang->productCommon} twice.";
$lang->project->accessDenied          = "Your access to {$lang->projectCommon} is denied!";
$lang->project->tips                  = 'Note';
$lang->project->afterInfo             = "{$lang->projectCommon} is created. Next you can ";
$lang->project->setTeam               = 'Set Team';
$lang->project->linkStory             = 'Link Story';
$lang->project->createTask            = 'Create Task';
$lang->project->goback                = "Go Back";
$lang->project->noweekend             = 'Exclude Weekend';
$lang->project->withweekend           = 'Include Weekend';
$lang->project->interval              = 'Intervals ';
$lang->project->fixFirstWithLeft      = 'Update hours left too';

$lang->project->action = new stdclass();
$lang->project->action->opened  = '$date, created by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->managed = '$date, managed by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->edited  = '$date, edited by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->extra   = "The linked {$lang->productCommon}s are %s.";

/* Statistics. */
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = " Burndown Chart";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "Hour";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
$lang->project->charts->burn->graph->reference    = 'Ideal';
$lang->project->charts->burn->graph->actuality    = 'Actual';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = "Abbreviation of {$lang->projectCommon} name";
$lang->project->placeholder->totalLeft = "Hours estimated on the first day of the {$lang->projectCommon}.";

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(Done)';

$lang->project->orderList['order_asc']  = "Story Rank Ascending";
$lang->project->orderList['order_desc'] = "Story Rank Descending";
$lang->project->orderList['pri_asc']    = "Story Priority Ascending";
$lang->project->orderList['pri_desc']   = "Story Priority Descending";
$lang->project->orderList['stage_asc']  = "Story Phase Ascending";
$lang->project->orderList['stage_desc'] = "Story Phase Descending";

$lang->project->kanban        = "Kanban";
$lang->project->kanbanSetting = "Settings";
$lang->project->resetKanban   = "Reset";
$lang->project->printKanban   = "Print";
$lang->project->bugList       = "Bugs";

$lang->project->kanbanHideCols   = 'Closed & Cancelled Columns';
$lang->project->kanbanShowOption = 'Unfold';
$lang->project->kanbanColsColor  = 'Customize Column Color';

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

$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved']   = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed']      = 'Delayed';
$lang->project->featureBar['task']['needconfirm']  = 'Changed';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all']       = $lang->project->all;
$lang->project->featureBar['all']['undone']    = $lang->project->undone;
$lang->project->featureBar['all']['wait']      = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing']     = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed']    = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all']   = 'Expand All';
$lang->project->treeLevel['root']  = 'Collapse All';
$lang->project->treeLevel['task']  = 'Stories&Tasks';
$lang->project->treeLevel['story'] = 'Only Stories';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
