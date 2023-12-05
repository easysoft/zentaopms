<?php
/**
 * The execution module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->execution->allExecutions       = 'All ' . $lang->execution->common . 's';
$lang->execution->allExecutionAB      = 'Execution List';
$lang->execution->id                  = $lang->executionCommon . ' ID';
$lang->execution->type                = $lang->executionCommon . ' Type';
$lang->execution->name                = "{$lang->executionCommon} Name";
$lang->execution->code                = "{$lang->executionCommon} Code";
$lang->execution->projectName         = $lang->projectCommon;
$lang->execution->project             = $lang->projectCommon;
$lang->execution->execId              = "{$lang->execution->common} ID";
$lang->execution->execName            = "{$lang->execution->common} Name";
$lang->execution->execCode            = "{$lang->execution->common} Code";
$lang->execution->execType            = 'Execution Type';
$lang->execution->lifetime            = $lang->projectCommon . ' Cycle';
$lang->execution->attribute           = 'Stage Type';
$lang->execution->percent             = 'Workload %';
$lang->execution->milestone           = 'Milestone';
$lang->execution->parent              = $lang->projectCommon;
$lang->execution->path                = 'Path';
$lang->execution->grade               = 'Grade';
$lang->execution->output              = 'Output';
$lang->execution->version             = 'Version';
$lang->execution->parentVersion       = 'Parent Version';
$lang->execution->planDuration        = 'Plan Duration';
$lang->execution->realDuration        = 'Real Duration';
$lang->execution->openedVersion       = 'Opened Version';
$lang->execution->lastEditedBy        = 'Last EditedBy';
$lang->execution->lastEditedDate      = 'Last EditedDate';
$lang->execution->suspendedDate       = 'Suspended Date';
$lang->execution->vision              = 'Vision';
$lang->execution->displayCards        = 'Max cards per column';
$lang->execution->fluidBoard          = 'Column Width';
$lang->execution->stage               = 'Stage';
$lang->execution->pri                 = 'Priority';
$lang->execution->openedBy            = 'OpenedBy';
$lang->execution->openedDate          = 'OpenedDate';
$lang->execution->closedBy            = 'ClosedBy';
$lang->execution->closedDate          = 'ClosedDate';
$lang->execution->canceledBy          = 'CanceledBy';
$lang->execution->canceledDate        = 'CanceledDate';
$lang->execution->begin               = 'Planned Begin';
$lang->execution->end                 = 'Planned End';
$lang->execution->dateRange           = 'Plan Duration';
$lang->execution->realBeganAB         = 'Actual Begin';
$lang->execution->realEndAB           = 'Actual End';
$lang->execution->teamCount           = 'number of people';
$lang->execution->realBegan           = 'Actual Begin';
$lang->execution->realEnd             = 'Actual End';
$lang->execution->to                  = 'To';
$lang->execution->days                = ' Days';
$lang->execution->day                 = ' Days';
$lang->execution->workHour            = ' Hours';
$lang->execution->workHourUnit        = 'H';
$lang->execution->totalHours          = ' Hours';
$lang->execution->totalDays           = ' Days';
$lang->execution->status              = $lang->executionCommon . ' Status';
$lang->execution->execStatus          = 'Status';
$lang->execution->subStatus           = 'Sub Status';
$lang->execution->desc                = "{$lang->executionCommon} Description";
$lang->execution->execDesc            = 'Description';
$lang->execution->owner               = 'Owner';
$lang->execution->PO                  = "{$lang->executionCommon} Owner";
$lang->execution->PM                  = "{$lang->executionCommon} Manager";
$lang->execution->execPM              = "Execution Manager";
$lang->execution->QD                  = 'Test Manager';
$lang->execution->RD                  = 'Release Manager';
$lang->execution->release             = 'Release';
$lang->execution->acl                 = 'Access Control';
$lang->execution->auth                = 'Privileges';
$lang->execution->teamName            = 'Team Name';
$lang->execution->teamSetting         = 'Team Setting';
$lang->execution->updateOrder         = 'Rank';
$lang->execution->order               = "Rank {$lang->executionCommon}";
$lang->execution->orderAB             = "Rank";
$lang->execution->products            = "Link {$lang->productCommon}";
$lang->execution->whitelist           = 'Whitelist';
$lang->execution->addWhitelist        = 'Add Whitelist';
$lang->execution->unbindWhitelist     = 'Remove Whitelist';
$lang->execution->totalEstimate       = 'Estimates';
$lang->execution->totalConsumed       = 'Cost';
$lang->execution->totalLeft           = 'Left';
$lang->execution->progress            = ' Progress';
$lang->execution->hours               = 'Estimates: %s, Cost: %s, Left: %s.';
$lang->execution->viewBug             = 'Bugs';
$lang->execution->noProduct           = "No {$lang->productCommon} yet.";
$lang->execution->createStory         = "Create Story";
$lang->execution->storyTitle          = "Story Name";
$lang->execution->storyView           = "Story Detail";
$lang->execution->all                 = "All {$lang->executionCommon}s";
$lang->execution->undone              = 'Unfinished ';
$lang->execution->unclosed            = 'Unclosed';
$lang->execution->closedExecution     = 'Closed Execution';
$lang->execution->typeDesc            = "OPS {$lang->executionCommon} has no {$lang->SRCommon}, Bug, Build, or Test features.";
$lang->execution->mine                = 'Mine: ';
$lang->execution->involved            = 'Mine';
$lang->execution->other               = 'Others';
$lang->execution->deleted             = 'Deleted';
$lang->execution->delayed             = 'Delayed';
$lang->execution->product             = $lang->execution->products;
$lang->execution->readjustTime        = "Adjust {$lang->executionCommon} Begin and End";
$lang->execution->readjustTask        = 'Adjust Task Begin and End';
$lang->execution->effort              = 'Effort';
$lang->execution->storyEstimate       = 'Story Estimate';
$lang->execution->newEstimate         = 'New Estimate';
$lang->execution->reestimate          = 'Reestimate';
$lang->execution->selectRound         = 'Select Round';
$lang->execution->average             = 'Average';
$lang->execution->relatedMember       = 'Team';
$lang->execution->member              = 'Member';
$lang->execution->watermark           = 'Exported by ZenTao';
$lang->execution->burnXUnit           = '(Date)';
$lang->execution->burnYUnit           = '(Hours)';
$lang->execution->count               = '(Count)';
$lang->execution->waitTasks           = 'Waiting Tasks';
$lang->execution->viewByUser          = 'By User';
$lang->execution->oneProduct          = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct       = "Not linked {$lang->productCommon}!";
$lang->execution->recent              = 'Recent visits: ';
$lang->execution->copyNoExecution     = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->noTeam              = 'No team members at the moment';
$lang->execution->or                  = ' or ';
$lang->execution->selectProject       = 'Please select ' . $lang->projectCommon;
$lang->execution->unfoldClosed        = 'Unfold Closed';
$lang->execution->editName            = 'Edit Name';
$lang->execution->setWIP              = 'WIP Settings';
$lang->execution->sortColumn          = 'Kanban Card Sorting';
$lang->execution->batchCreateStory    = "Batch create {$lang->SRCommon}";
$lang->execution->batchCreateTask     = 'Batch create task';
$lang->execution->kanbanNoLinkProduct = "Kanban not linked {$lang->productCommon}";
$lang->execution->myTask              = "My Task";
$lang->execution->list                = 'List';
$lang->execution->allProject          = 'All';
$lang->execution->method              = 'Management Method';
$lang->execution->sameAsParent        = "Same as parent";
$lang->execution->selectStoryPlan     = 'Select Plan';

/* Fields of zt_team. */
$lang->execution->root          = 'Root';
$lang->execution->estimate      = 'Estimate';
$lang->execution->estimateHours = 'Estimate';
$lang->execution->consumed      = 'Consumed';
$lang->execution->consumedHours = 'Consumed';
$lang->execution->left          = 'Left';
$lang->execution->leftHours     = 'Left';

$lang->execution->copyTeamTip        = "copy {$lang->projectCommon}/{$lang->execution->common} team members";
$lang->execution->daysGreaterProject = 'Days cannot be greater than days of execution 『%s』';
$lang->execution->errorHours         = 'Hours/Day cannot be greater than『24』';
$lang->execution->agileplusMethodTip = "When creating executions in an Agile Plus {$lang->projectCommon}, both {$lang->executionCommon} and Kanban management methods are supported.";
$lang->execution->typeTip            = "The sub-stages of other types can be created under the parent stage of the 'mix' type, while the type of other parent-child levels is consistent.";
$lang->execution->waterfallTip       = "In the Waterfall {$lang->projectCommon} or in the Waterfall + {$lang->projectCommon},";
$lang->execution->progressTip        = 'All Progress = Consumed / (Consumed + Left)';

$lang->execution->start    = 'Start';
$lang->execution->activate = 'Activate';
$lang->execution->putoff   = 'Delay';
$lang->execution->suspend  = 'Suspend';
$lang->execution->close    = 'Close';
$lang->execution->export   = 'Export';
$lang->execution->next     = "Next";

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

$lang->execution->cfdTypeList['story'] = "View by {$lang->SRCommon}";
$lang->execution->cfdTypeList['task']  = "View by task";
$lang->execution->cfdTypeList['bug']   = "View By bug";

$lang->team->account    = 'User';
$lang->team->realname   = 'Name';
$lang->team->role       = 'Role';
$lang->team->roleAB     = 'My Role';
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
$lang->execution->aclList['private'] = "Private (for team members and {$lang->projectCommon} stakeholders)";
$lang->execution->aclList['open']    = "Inherited {$lang->projectCommon} ACL (for who can access the current {$lang->projectCommon})";

$lang->execution->kanbanAclList['private'] = 'Private';
$lang->execution->kanbanAclList['open']    = "Inherited {$lang->projectCommon}";

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Method list. */
$lang->execution->index                     = "{$lang->executionCommon} Home";
$lang->execution->task                      = 'Task List';
$lang->execution->groupTask                 = 'Group View';
$lang->execution->story                     = 'Story List';
$lang->execution->qa                        = 'QA';
$lang->execution->bug                       = 'Bug List';
$lang->execution->testcase                  = 'Testcase List';
$lang->execution->dynamic                   = 'Dynamics';
$lang->execution->latestDynamic             = 'Dynamics';
$lang->execution->build                     = 'Build List';
$lang->execution->testtask                  = 'Request';
$lang->execution->burn                      = 'Burndown';
$lang->execution->computeBurn               = 'Update';
$lang->execution->computeCFD                = 'Compute Cumulative Flow diagrams';
$lang->execution->fixFirst                  = 'Edit 1st-Day Estimates';
$lang->execution->team                      = 'Members';
$lang->execution->doc                       = 'Document';
$lang->execution->doclib                    = 'Docoment Library';
$lang->execution->manageProducts            = 'Linked ' . $lang->productCommon . 's';
$lang->execution->linkStory                 = 'Link Stories';
$lang->execution->linkStoryByPlan           = 'Link Stories By Plan';
$lang->execution->linkPlan                  = 'Linked Plan';
$lang->execution->unlinkStoryTasks          = 'Unlink';
$lang->execution->linkedProducts            = "Linked {$lang->productCommon}s";
$lang->execution->unlinkedProducts          = "Unlinked {$lang->productCommon}s";
$lang->execution->view                      = "Execution Detail";
$lang->execution->startAction               = "Start Execution";
$lang->execution->activateAction            = "Activate Execution";
$lang->execution->delayAction               = "Delay Execution";
$lang->execution->suspendAction             = "Suspend Execution";
$lang->execution->closeAction               = "Close Execution";
$lang->execution->testtaskAction            = "Execution Request";
$lang->execution->teamAction                = "Execution Members";
$lang->execution->kanbanAction              = "Execution Kanban";
$lang->execution->printKanbanAction         = "Print Kanban";
$lang->execution->treeAction                = "Execution Tree View";
$lang->execution->exportAction              = "Export Execution";
$lang->execution->computeBurnAction         = "Update Burndown";
$lang->execution->create                    = "Create {$lang->executionCommon}";
$lang->execution->createExec                = "Create {$lang->execution->common}";
$lang->execution->createAction              = "Create {$lang->execution->common}";
$lang->execution->copyExec                  = "Copy {$lang->execution->common}";
$lang->execution->copy                      = "Copy {$lang->executionCommon}";
$lang->execution->delete                    = "Delete {$lang->executionCommon}";
$lang->execution->deleteAB                  = "Delete Execution";
$lang->execution->browse                    = "{$lang->executionCommon} List";
$lang->execution->edit                      = "Edit {$lang->executionCommon}";
$lang->execution->editAction                = "Edit Execution";
$lang->execution->batchEdit                 = "Edit";
$lang->execution->batchEditAction           = "Batch Edit";
$lang->execution->batchChangeStatus         = "Batch Change Status";
$lang->execution->manageMembers             = 'Manage Team';
$lang->execution->unlinkMember              = 'Remove Member';
$lang->execution->unlinkStory               = 'Unlink Story';
$lang->execution->unlinkStoryAB             = 'Unlink';
$lang->execution->batchUnlinkStory          = 'Batch Unlink Stories';
$lang->execution->importTask                = 'Transfer Task';
$lang->execution->importPlanStories         = 'Link Stories By Plan';
$lang->execution->importBug                 = 'Import Bug';
$lang->execution->tree                      = 'Tree';
$lang->execution->treeTask                  = 'Show Task Only';
$lang->execution->treeStory                 = 'Show Story Only';
$lang->execution->treeViewTask              = 'Tree View Task';
$lang->execution->treeViewStory             = 'Tree View Story';
$lang->execution->storyKanban               = 'Story Kanban';
$lang->execution->storySort                 = 'Rank Story';
$lang->execution->importPlanStory           = "{$lang->executionCommon} is created!\nDo you want to import stories that have been linked to the plan? Only active " . $lang->SRCommon . ' can be imported.';
$lang->execution->importEditPlanStory       = "{$lang->executionCommon} is edited!\nDo you want to import stories that have been linked to the plan? The stories in the draft will be automatically filtered out when imported.";
$lang->execution->importBranchPlanStory     = "{$lang->executionCommon} is created!\nDo you want to import stories that have been linked to the plan? Only the activation stories of the branch associated with this " . $lang->executionCommon. ' will be associated with the import';
$lang->execution->importBranchEditPlanStory = "{$lang->executionCommon} is edited!\nDo you want to import stories that have been linked to the plan? Only the activation stories of the branch associated with this " . $lang->executionCommon. ' will be associated with the import';
$lang->execution->needLinkProducts          = "The execution has not been linked with any {$lang->productCommon}, and the related functions cannot be used. Please link the {$lang->productCommon} first and try again.";
$lang->execution->iteration                 = 'Iterations';
$lang->execution->iterationInfo             = '%s Iterations';
$lang->execution->viewAll                   = 'View All';
$lang->execution->testreport                = 'Test Report';
$lang->execution->taskKanban                = 'Task Kanban';
$lang->execution->RDKanban                  = 'Research & Development Kanban';

/* Group browsing. */
$lang->execution->allTasks     = 'All';
$lang->execution->assignedToMe = 'My';
$lang->execution->myInvolved   = 'Involved';
$lang->execution->assignedByMe = 'AssignedByMe';

$lang->execution->statusSelects['']             = 'More';
$lang->execution->statusSelects['wait']         = 'Waiting';
$lang->execution->statusSelects['doing']        = 'Doing';
$lang->execution->statusSelects['undone']       = 'Unfinished';
$lang->execution->statusSelects['finishedbyme'] = 'FinishedByMe';
$lang->execution->statusSelects['done']         = 'Done';
$lang->execution->statusSelects['closed']       = 'Closed';
$lang->execution->statusSelects['cancel']       = 'Cancelled';
$lang->execution->statusSelects['delayed']      = 'Delayed';

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
$lang->execution->linkStoryByPlanTips  = "This action will link all stories in this plan to the {$lang->executionCommon}.";
$lang->execution->batchCreateStoryTips = "Please select the {$lang->productCommon} that needs to be created in batches";
$lang->execution->selectExecution      = "Select {$lang->executionCommon}";
$lang->execution->beginAndEnd          = 'Duration';
$lang->execution->lblStats             = 'Efforts';
$lang->execution->DurationStats        = 'Duration information';
$lang->execution->stats                = 'Available: <strong>%s</strong>(h). Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).';
$lang->execution->taskSummary          = "Total tasks on this page:<strong>%s</strong>. Waiting: <strong>%s</strong>. Doing: <strong>%s</strong>.  &nbsp;&nbsp;&nbsp;  Estimates: <strong>%s</strong>(h). Cost: <strong>%s</strong>(h). Left: <strong>%s</strong>(h).";
$lang->execution->pageSummary          = "Total tasks: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->execution->checkedSummary       = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.    Estimates: <strong>%estimate%</strong>(h). Cost: <strong>%consumed%</strong>(h). Left: <strong>%left%</strong>(h).";
$lang->execution->executionSummary     = "Total {$lang->executionCommon}: <strong>%s</strong>.";
$lang->execution->pageExecSummary      = "Total {$lang->executionCommon}: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->checkedExecSummary   = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->memberHoursAB        = "%s has <strong>%s</ strong> hours.";
$lang->execution->memberHours          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Available Hours</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tasks</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Doing</div><div class="segment-value"><span class="label label-dot primary"></span> %s</div></div><div class="segment"><div class="segment-title">Waiting</div><div class="segment-value"><span class="label label-dot secondary"></span> %s</div></div></div></div>';
$lang->execution->timeSummary          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimates</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Cost</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Left</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB       = "<div>Tasks <strong>%s ：</strong><span class='text-muted'>Waiting</span> %s &nbsp; <span class='text-muted'>Doing</span> %s</div><div>Estimates <strong>%s ：</strong><span class='text-muted'>Cost</span> %s &nbsp; <span class='text-muted'>Left</span> %s</div>";
$lang->execution->wbs                  = "Create Task";
$lang->execution->batchWBS             = "Batch Create Tasks";
$lang->execution->howToUpdateBurn      = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='How to update the Burndown Chart?'>Help <i class='icon icon-help text-gray'></i></a>";
$lang->execution->whyNoStories         = "No story can be linked. Please check whether there is any story in {$lang->executionCommon} which is linked to {$lang->productCommon} and make sure it has been reviewed.";
$lang->execution->projectNoStories     = "No story can be linked. Please check whether there is any story in {$lang->projectCommon} and make sure it has been reviewed.";
$lang->execution->productStories       = "Stories linked to {$lang->executionCommon} are the subeset of stories linked to {$lang->productCommon}. Stories can only be linked after they pass the review. <a href='%s'> Link Stories</a> now.";
$lang->execution->haveBranchDraft      = "There are %s draft stories or not associated with this {$lang->executionCommon} can't be linked.";
$lang->execution->haveDraft            = "There are %s draft stories with this {$lang->executionCommon} can't be linked.";
$lang->execution->doneExecutions       = 'Finished';
$lang->execution->selectDept           = 'Select Department';
$lang->execution->selectDeptTitle      = 'Select User';
$lang->execution->copyTeam             = 'Copy Team';
$lang->execution->copyFromTeam         = "Copy from {$lang->execution->common} Team: <strong>%s</strong>";
$lang->execution->noMatched            = "No {$lang->execution->common} including '%s'can be found.";
$lang->execution->copyTitle            = "Choose a {$lang->execution->common} to copy.";
$lang->execution->copyNoExecution      = "No {$lang->execution->common} can be copied.";
$lang->execution->copyFromExecution    = "Copy from {$lang->execution->common} <strong>%s</strong>";
$lang->execution->cancelCopy           = 'Cancel Copy';
$lang->execution->byPeriod             = 'By Time';
$lang->execution->byUser               = 'By User';
$lang->execution->noExecution          = "No {$lang->executionCommon}. ";
$lang->execution->noExecutions         = "No {$lang->execution->common}.";
$lang->execution->noPrintData          = "No data can be printed.";
$lang->execution->noMembers            = 'No team members yet. ';
$lang->execution->workloadTotal        = "The cumulative workload ratio should not exceed 100%s, and the total workload under the current {$lang->productCommon} is: %s";
$lang->execution->linkAllStoryTip      = "({$lang->SRCommon} has never been linked under the {$lang->projectCommon}, and can be directly linked with {$lang->SRCommon} of the {$lang->productCommon} linked with the sprint/stage)";
$lang->execution->copyTeamTitle        = "Choose a {$lang->project->common} or {$lang->execution->common} Team to copy.";

/* Interactive prompts. */
$lang->execution->confirmDelete                = "Do you want to delete the {$lang->executionCommon}[%s]?";
$lang->execution->confirmUnlinkMember          = "Do you want to unlink this User from {$lang->executionCommon}?";
$lang->execution->confirmUnlinkStory           = "After {$lang->SRCommon} is removed, cased linked to {$lang->SRCommon} will be reomoved and tasks linked to {$lang->SRCommon} will be cancelled. Do you want to continue?";
$lang->execution->confirmSync                  = "After modifying the {$lang->projectCommon}, in order to maintain the consistency of data, the data of {$lang->productCommon}s, {$lang->SRCommon}s, teams and whitelist associated with the implementation will be synchronized to the new {$lang->projectCommon}. Please know.";
$lang->execution->confirmUnlinkExecutionStory  = "Do you want to unlink this Story from the {$lang->projectCommon}?";
$lang->execution->notAllowedUnlinkStory        = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the {$lang->projectCommon}. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts       = "The story %s of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts        = "No {$lang->productCommon} is linked to {$lang->executionCommon}. You will be directed to {$lang->productCommon} page to link one.";
$lang->execution->errorSameProducts            = "{$lang->executionCommon} cannot be linked to the same {$lang->productCommon} twice.";
$lang->execution->errorSameBranches            = "{$lang->executionCommon} cannot be linked to the same branch twice";
$lang->execution->errorBegin                   = "The start time of {$lang->executionCommon} cannot be less than the start time of the {$lang->projectCommon} %s.";
$lang->execution->errorEnd                     = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the {$lang->projectCommon}.";
$lang->execution->errorLesserProject           = "The start time of {$lang->executionCommon} cannot be less than the start time of the {$lang->projectCommon} %s.";
$lang->execution->errorGreaterProject          = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the {$lang->projectCommon}.";
$lang->execution->errorCommonBegin             = "The start date of ' . $lang->executionCommon . ' should be ≥ the start date of {$lang->projectCommon} : %s.";
$lang->execution->errorCommonEnd               = "The deadline of ' . $lang->executionCommon .  ' should be ≤ the deadline of {$lang->projectCommon} : %s.";
$lang->execution->errorLesserParent            = 'The begin cannot be less than the begin of the parent stage to which it belongs: %s.';
$lang->execution->errorGreaterParent           = 'The end cannot be greater than the end of the parent stage to which it belongs：%s.';
$lang->execution->errorNameRepeat              = "Child %s of the same parent stage cannot have the same name.";
$lang->execution->errorAttrMatch               = "Parent stage's attribute is [%s], the attribute needs to be consistent with the parent stage.";
$lang->execution->errorLesserPlan              = "『%s』cannot be less than the plan start time『%s』。";
$lang->execution->accessDenied                 = "Your access to {$lang->executionCommon} is denied!";
$lang->execution->tips                         = 'Note';
$lang->execution->afterInfo                    = "{$lang->executionCommon} is created. Next you can ";
$lang->execution->setTeam                      = 'Set Team';
$lang->execution->linkStory                    = 'Link Story';
$lang->execution->createTask                   = 'Create Task';
$lang->execution->goback                       = "Go Back Task List";
$lang->execution->gobackExecution              = "Go Back {$lang->executionCommon} List";
$lang->execution->noweekend                    = 'Exclude Weekend';
$lang->execution->nodelay                      = 'Exclude Delay Date';
$lang->execution->withweekend                  = 'Include Weekend';
$lang->execution->withdelay                    = 'Include Delay Date';
$lang->execution->interval                     = 'Intervals ';
$lang->execution->fixFirstWithLeft             = 'Update hours left too';
$lang->execution->unfinishedExecution          = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask               = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug                = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty              = "{$lang->projectCommon} cannot be empty.";
$lang->execution->confirmStoryToTask           = $lang->SRCommon . '%s are converted to tasks in the current. Do you want to convert them anyways?';
$lang->execution->ge                           = "『%s』should be >= actual begin『%s』.";
$lang->execution->storyDragError               = "The {$lang->SRCommon} is not active. Please activate and drag again.";
$lang->execution->countTip                     = ' (%s member)';
$lang->execution->pleaseInput                  = "Enter";
$lang->execution->week                         = 'week';
$lang->execution->checkedExecutions            = "Seleted %s {$lang->executionCommon}.";
$lang->execution->hasStartedTaskOrSubStage     = "Tasks or subphases under %s %s have already started, cannot be modified, and have been filtered.";
$lang->execution->hasSuspendedOrClosedChildren = "The sub-stages under stage %s are not all suspended or closed, cannot be modified, and have been filtered.";
$lang->execution->hasNotClosedChildren         = "The sub-stages under stage %s are not all closed, cannot be modified, and have been filtered.";
$lang->execution->hasStartedTask               = "The task under %s %s has already started, cannot be modified, and has been filtered.";
$lang->execution->cannotManageProducts         = 'The ' . strtolower($lang->project->common). ' model of this ' . strtolower($lang->execution->common) . " is %s and this " . strtolower($lang->execution->common) . " cannot be associated with {$lang->productCommon}.";

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
$lang->execution->charts->burn->graph->delay        = 'Delay';

$lang->execution->charts->cfd = new stdclass();
$lang->execution->charts->cfd->cfdTip        = "<p>
1. The CFD（Cumulative Flow Diagram）reflects the trend of accumulated workload at each stage over time.<br>
2. The horizontal axis represents the date, and the vertical axis represents the number of work items.<br>
3. To learn about the team's delivery, you can calculate the WIP quantity, delivery rate and average lead time through the CFD. <p>";
$lang->execution->charts->cfd->cycleTime     = 'Average cycle time';
$lang->execution->charts->cfd->cycleTimeTip  = 'Average cycle time of each card from development start to completion';
$lang->execution->charts->cfd->throughput    = 'Throughput Rate';
$lang->execution->charts->cfd->throughputTip = 'Throughput Rate = WIP / Average cycle time';

$lang->execution->charts->cfd->begin          = 'Begin';
$lang->execution->charts->cfd->end            = 'End';
$lang->execution->charts->cfd->errorBegin     = 'The start time cannot be greater than the end time.';
$lang->execution->charts->cfd->errorDateRange = 'The Cumulative Flow Diagram（CFD） only provides data display within 3 months.';
$lang->execution->charts->cfd->dateRangeTip   = 'CFD only shows the data within 3 months';

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
$lang->execution->setKanban     = "Set Kanban";
$lang->execution->resetKanban   = "Reset";
$lang->execution->printKanban   = "Print Kanban";
$lang->execution->fullScreen    = "Full Screen";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Closed & Cancelled Columns';
$lang->execution->kanbanShowOption = 'Unfold';
$lang->execution->kanbanColsColor  = 'Customize Column Color';
$lang->execution->kanbanCardsUnit  = 'X';

$lang->execution->kanbanViewList['all']   = 'All';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}";
$lang->execution->kanbanViewList['bug']   = 'Bug';
$lang->execution->kanbanViewList['task']  = 'Task';

$lang->execution->teamWords  = 'Team';

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
$lang->execution->typeList['kanban'] = 'Kanban';

$lang->execution->featureBar['tree']['all'] = 'All';

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['assignedbyme'] = $lang->execution->assignedByMe;
$lang->execution->featureBar['task']['needconfirm']  = 'Changed';
$lang->execution->featureBar['task']['status']       = $lang->more;

$lang->execution->moreSelects['task']['status']['wait']         = 'Waiting';
$lang->execution->moreSelects['task']['status']['doing']        = 'Doing';
$lang->execution->moreSelects['task']['status']['undone']       = 'Unfinished';
$lang->execution->moreSelects['task']['status']['finishedbyme'] = 'FinishedByMe';
$lang->execution->moreSelects['task']['status']['done']         = 'Done';
$lang->execution->moreSelects['task']['status']['closed']       = 'Closed';
$lang->execution->moreSelects['task']['status']['cancel']       = 'Cancelled';
$lang->execution->moreSelects['task']['status']['delayed']      = 'Delayed';

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = 'All';
$lang->execution->featureBar['bug']['unresolved'] = 'Active';

$lang->execution->featureBar['build']['all'] = 'Build List';

$lang->execution->featureBar['story']['all']       = 'All';
$lang->execution->featureBar['story']['unclosed']  = 'Unclosed';
$lang->execution->featureBar['story']['draft']     = 'Draft';
$lang->execution->featureBar['story']['reviewing'] = 'Reviewing';

$lang->execution->featureBar['testcase']['all'] = 'All';

$lang->execution->featureBar['importtask']['all'] = $lang->execution->importTask;

$lang->execution->featureBar['importbug']['all'] = $lang->execution->importBug;

$lang->execution->myExecutions = 'Mine';
$lang->execution->doingProject = "Ongoing {$lang->projectCommon}s";

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . ' ' . $lang->execution->common . '(The recent two executions)';

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Expand All';
$lang->execution->treeLevel['root']  = 'Collapse All';
$lang->execution->treeLevel['task']  = 'Stories&Tasks';
$lang->execution->treeLevel['story'] = 'Only Stories';

$lang->execution->action = new stdclass();
$lang->execution->action->opened               = '$date, created by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->managed              = '$date, managed by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->edited               = '$date, edited by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->extra                = "Linked {$lang->productCommon}s is %s.";
$lang->execution->action->startbychildactivate = '$date, activating the sub stage sets the execution status as Ongoing.' . "\n";
$lang->execution->action->waitbychilddelete    = '$date, deleting the sub stage sets the execution status as waitting.' . "\n";
$lang->execution->action->closebychilddelete   = '$date, deleting the sub stage sets the execution status as closing.' . "\n";
$lang->execution->action->closebychildclose    = '$date, closing the sub stage sets the execution status as closing.' . "\n";
$lang->execution->action->waitbychild          = '$date, the stage status is <strong>Wait</strong> as the system judges that all its sub-stages statuses are <strong>Wait</strong>.';
$lang->execution->action->suspendedbychild     = '$date, the stage status is <strong>Suspended</strong> as the system judges that all its sub-stages statuses are <strong>Suspended</strong>.';
$lang->execution->action->closedbychild        = '$date, the stage status is <strong>Closed</strong> as the system judges that all its sub-stages are <strong>Closed</strong>.';
$lang->execution->action->startbychildstart    = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Started</strong>.';
$lang->execution->action->startbychildactivate = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Activated</strong>.';
$lang->execution->action->startbychildsuspend  = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Suspended</strong>.';
$lang->execution->action->startbychildclose    = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Closed</strong>.';
$lang->execution->action->startbychildcreate   = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Created</strong>. ';
$lang->execution->action->startbychildedit     = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->startbychild         = '$date, the stage status is <strong>Doing</strong> as the system judges that its sub-stages are <strong>Activated</strong>.';
$lang->execution->action->waitbychild          = '$date, the stage status is <strong>Wait</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->suspendbychild       = '$date, the stage status is <strong>Suspended</strong> as the system judges that its sub-stages are <strong>Edited</strong>';
$lang->execution->action->closebychild         = '$date, the stage status is <strong>Closed</strong> as the system judges that its sub-stages are <strong>Edited</strong>';

$lang->execution->startbychildactivate = 'activated';
$lang->execution->waitbychilddelete    = 'stop';
$lang->execution->closebychilddelete   = 'closed';
$lang->execution->closebychildclose    = 'closed';
$lang->execution->waitbychild          = 'activated';
$lang->execution->suspendedbychild     = 'suspended';
$lang->execution->closedbychild        = 'closed';
$lang->execution->startbychildstart    = 'started';
$lang->execution->startbychildactivate = 'activated';
$lang->execution->startbychildsuspend  = 'activated';
$lang->execution->startbychildclose    = 'activated';
$lang->execution->startbychildcreate   = 'activated';
$lang->execution->startbychildedit     = 'activated';
$lang->execution->startbychild         = 'activated';
$lang->execution->waitbychild          = 'stop';
$lang->execution->suspendbychild       = 'suspended';
$lang->execution->closebychild         = 'closed';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

if(!isset($lang->execution->gantt)) $lang->execution->gantt = new stdclass();
$lang->execution->gantt->progressColor[0] = '#B7B7B7';
$lang->execution->gantt->progressColor[1] = '#FF8287';
$lang->execution->gantt->progressColor[2] = '#FFC73A';
$lang->execution->gantt->progressColor[3] = '#6BD5F5';
$lang->execution->gantt->progressColor[4] = '#9DE88A';
$lang->execution->gantt->progressColor[5] = '#9BA8FF';

$lang->execution->gantt->color[0] = '#E7E7E7';
$lang->execution->gantt->color[1] = '#FFDADB';
$lang->execution->gantt->color[2] = '#FCECC1';
$lang->execution->gantt->color[3] = '#D3F3FD';
$lang->execution->gantt->color[4] = '#DFF5D9';
$lang->execution->gantt->color[5] = '#EBDCF9';

$lang->execution->gantt->textColor[0] = '#2D2D2D';
$lang->execution->gantt->textColor[1] = '#8D0308';
$lang->execution->gantt->textColor[2] = '#9D4200';
$lang->execution->gantt->textColor[3] = '#006D8E';
$lang->execution->gantt->textColor[4] = '#1A8100';
$lang->execution->gantt->textColor[5] = '#660ABC';

$lang->execution->gantt->stage = new stdclass();
$lang->execution->gantt->stage->progressColor = '#70B8FE';
$lang->execution->gantt->stage->color         = '#D2E7FC';
$lang->execution->gantt->stage->textColor     = '#0050A7';

$lang->execution->gantt->defaultColor         = '#EBDCF9';
$lang->execution->gantt->defaultProgressColor = '#9BA8FF';
$lang->execution->gantt->defaultTextColor     = '#660ABC';

$lang->execution->gantt->bar_height = '24';

$lang->execution->gantt->exportImg  = 'Export as Image';
$lang->execution->gantt->exportPDF  = 'Export as PDF';
$lang->execution->gantt->exporting  = 'Exporting...';
$lang->execution->gantt->exportFail = 'Failed to export.';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');

$lang->execution->linkBranchStoryByPlanTips = "When a scheduled association requirement is executed, only the active requirements associated with the %s of this execution are imported.";
$lang->execution->linkNormalStoryByPlanTips = "Only the active requirements are imported when the scheduled requirements are associated.";

$lang->execution->featureBar['dynamic']['all']       = 'All';
$lang->execution->featureBar['dynamic']['today']     = 'Today';
$lang->execution->featureBar['dynamic']['yesterday'] = 'Yesterday';
$lang->execution->featureBar['dynamic']['thisWeek']  = 'This Week';
$lang->execution->featureBar['dynamic']['lastWeek']  = 'Last Week';
$lang->execution->featureBar['dynamic']['thisMonth'] = 'This Month';
$lang->execution->featureBar['dynamic']['lastMonth'] = 'Last Month';

$lang->execution->featureBar['team']['all'] = 'Members';

$lang->execution->featureBar['managemembers']['all'] = 'Manage Team';
