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
$lang->project->allProjects   = 'All';
$lang->project->type          = 'Type';
$lang->project->name          = 'Name';
$lang->project->code          = 'Alias';
$lang->project->begin         = 'Begin';
$lang->project->end           = 'End';
$lang->project->dateRange     = 'Time Frame';
$lang->project->to            = 'To';
$lang->project->days          = 'Work Days';
$lang->project->day           = 'day';
$lang->project->workHour      = 'Hour';
$lang->project->status        = 'Status';
$lang->project->desc          = 'Desc';
$lang->project->owner         = 'Owner';
$lang->project->PO            = $lang->productCommon . ' Owner';
$lang->project->PM            = $lang->projectCommon . ' Manager';
$lang->project->QD            = 'Quality Director';
$lang->project->RD            = 'Release Director';
$lang->project->acl           = 'Access Control';
$lang->project->teamname      = 'Team Name';
$lang->project->order         = "Sort {$lang->projectCommon}";
$lang->project->products      = "Link {$lang->productCommon}";
$lang->project->whitelist     = 'Whitelist';
$lang->project->totalEstimate = 'Est.';
$lang->project->totalConsumed = 'Consumed';
$lang->project->totalLeft     = 'Total Remained';
$lang->project->Left          = 'Remained';
$lang->project->progess       = 'Progess';
$lang->project->hours         = 'Estimate %s, Consumed %s, Remained %s';
$lang->project->viewBug       = 'Bugs';
$lang->project->noProduct     = "No {$lang->productCommon}";
$lang->project->createStory   = "Create Story";
$lang->project->all           = 'All';
$lang->project->undone        = 'Undone';
$lang->project->unclosed      = 'Unclosed';
$lang->project->typeDesc      = 'No story, bug, build, testtask and burndown allowed in OPS';
$lang->project->mine          = 'My Responsibility: ';
$lang->project->other         = 'Other:';
$lang->project->deleted       = 'Deleted';
$lang->project->delayed       = 'Delayed';

$lang->project->start    = 'Start';
$lang->project->activate = 'Activate';
$lang->project->putoff   = 'Deplay';
$lang->project->suspend  = 'Suspend';
$lang->project->close    = 'Close';

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
$lang->team->account    = 'Account';
$lang->team->role       = 'Role';
$lang->team->join       = 'Joined Date';
$lang->team->hours      = 'Hour/Day';
$lang->team->days       = 'Workdays';
$lang->team->totalHours = 'Total';

$lang->project->basicInfo = 'Basic Info';
$lang->project->otherInfo = 'Other Info';

/* 字段取值列表。*/
$lang->project->statusList['wait']      = 'Wait';
$lang->project->statusList['doing']     = 'Doing';
$lang->project->statusList['suspended'] = 'Suspend';
$lang->project->statusList['done']      = 'Done';

$lang->project->aclList['open']    = "Default (With the privilege to view {$lang->projectCommon}, you will have access to {$lang->projectCommon}.)";
$lang->project->aclList['private'] = 'Private (Only team members can access to it.)';
$lang->project->aclList['custom']  = 'Whitelist (Team members and the whitelist members have access to it.)';

/* 方法列表。*/
$lang->project->index            = "Home";
$lang->project->task             = 'Tasks';
$lang->project->groupTask        = 'View by';
$lang->project->story            = 'Stories';
$lang->project->bug              = 'Bugs';
$lang->project->dynamic          = 'Dynamic';
$lang->project->build            = 'Builds';
$lang->project->testtask         = 'Test Tasks';
$lang->project->burn             = 'Burndown';
$lang->project->baseline         = 'Base Line';
$lang->project->computeBurn      = 'Update';
$lang->project->burnData         = 'Burndown Data';
$lang->project->fixFirst         = 'Edit 1st Day Data';
$lang->project->team             = 'Team Member';
$lang->project->doc              = 'Doc';
$lang->project->manageProducts   = 'Link ' . $lang->productCommon;
$lang->project->linkStory        = 'Linked Story';
$lang->project->unlinkStoryTasks = 'Unlinked';
$lang->project->linkedProducts   = 'Linked Products';
$lang->project->unlinkedProducts = 'Unlinked Products';
$lang->project->view             = "Info";
$lang->project->create           = "Add";
$lang->project->copy             = "Copy {$lang->projectCommon}";
$lang->project->delete           = "Delete";
$lang->project->browse           = "Browse";
$lang->project->edit             = "Edit";
$lang->project->batchEdit        = "Batch Edit";
$lang->project->manageMembers    = 'Manange Team';
$lang->project->unlinkMember     = 'Unlink Member';
$lang->project->unlinkStory      = 'Unlink Story';
$lang->project->batchUnlinkStory = 'Batch Unlink Story';
$lang->project->importTask       = 'Import Task';
$lang->project->importBug        = 'Import Bug';
$lang->project->updateOrder      = 'Order';
$lang->project->tree             = 'Tree';

/* 分组浏览。*/
$lang->project->allTasks             = 'All';
$lang->project->assignedToMe         = 'Assigned to Me';

$lang->project->statusSelects['']             = 'More';
$lang->project->statusSelects['wait']         = 'Wait';
$lang->project->statusSelects['doing']        = 'Doing';
$lang->project->statusSelects['finishedbyme'] = 'Finished by Me';
$lang->project->statusSelects['done']         = 'Done';
$lang->project->statusSelects['closed']       = 'Closed';
$lang->project->statusSelects['cancel']       = 'Cancelled';

$lang->project->groups['']           = 'Groups';
$lang->project->groups['story']      = 'By Story';
$lang->project->groups['status']     = 'By Status';
$lang->project->groups['pri']        = 'By Priority';
$lang->project->groups['assignedTo'] = 'By AssignedTo';
$lang->project->groups['finishedBy'] = 'By FinishedBy';
$lang->project->groups['closedBy']   = 'By ClosedBy';
$lang->project->groups['type']       = 'By Type';

$lang->project->groupFilter['story']['all']         = $lang->project->all;
$lang->project->groupFilter['story']['linked']      = 'Task Linked to Story';
$lang->project->groupFilter['pri']['all']           = $lang->project->all;
$lang->project->groupFilter['pri']['noset']         = 'Not set';
$lang->project->groupFilter['assignedTo']['undone'] = 'Pending';
$lang->project->groupFilter['assignedTo']['all']    = $lang->project->all;

$lang->project->byQuery              = 'Search';

/* 查询条件列表。*/
$lang->project->allProject      = "All {$lang->projectCommon}";
$lang->project->aboveAllProduct = "All the above {$lang->productCommon}";
$lang->project->aboveAllProject = "All the above {$lang->projectCommon}";

/* 页面提示。*/
$lang->project->selectProject   = "Select {$lang->projectCommon}";
$lang->project->beginAndEnd     = 'Time Frame';
$lang->project->lblStats        = 'Man-Hour Report';
$lang->project->stats           = 'Available <strong>%s</strong> man-hour, <strong>%s</strong> man-hour Estimated, <strong>%s</strong> man-hour Consumed, <strong>%s</strong> man-hour.';
$lang->project->taskSummary     = " <strong>%s</strong> Tasks on this page, <strong>%s</strong> Wait, <strong>%s</strong> Doing. <strong>%s</strong> man-hour Estimated, <strong>%s</strong> man-hour Consumed, <strong>%s</strong> man-hour Remained.";
$lang->project->memberHours     = "%s has <strong>%s</strong> man-hour available ";
$lang->project->groupSummary    = "<strong>%s</strong> Tasks in this group, Wait <strong>%s</strong>, Doing <strong>%s</strong>. <strong>%s</strong> man-hour Estimated , <strong>%s</strong> man-hour Consumed, <strong>%s</strong> man-hour Remained.";
$lang->project->groupSummaryAB  = "Total Tasks <strong>%s</strong>, Wait <strong>%s</strong>, Doing <strong>%s</strong>.<br /> <strong>%s</strong> man-hour Estimated, <strong>%s</strong> man-hour Consumed，<strong>%s</strong> man-hour Remained.";
$lang->project->noTimeSummary   = " <strong>%s</strong> Tasks in this group, Wait <strong>%s</strong>, Doing <strong>%s</strong>.";
$lang->project->wbs             = "Decompose Task";
$lang->project->batchWBS        = "Batch Decompose";
$lang->project->howToUpdateBurn = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='How to Update the Burndown Chart?' class='btn btn-sm'>Help</a>";
$lang->project->whyNoStories    = "No Story can be linked. Please check whether there is Story in {$lang->projectCommon} linked {$lang->productCommon} and make sure it has been reviewed.";
$lang->project->productStories  = "{$lang->projectCommon} linked  story is the subeset of {$lang->productCommon}, which can only be linked after review. Please <a href='%s'> Link Story</a>。";
$lang->project->doneProjects    = 'Done';
$lang->project->selectDept      = 'Select Department';
$lang->project->selectDeptTitle = 'Select Department';
$lang->project->copyTeam        = 'Duplicate Team';
$lang->project->copyFromTeam    = "Duplicated from {$lang->projectCommon} Team: <strong>%s</strong>";
$lang->project->noMatched       = "No $lang->projectCommon including '%s'can be found.";
$lang->project->copyTitle       = "Choose a {$lang->projectCommon} to duplicate.";
$lang->project->copyTeamTitle   = "Choose {$lang->projectCommon}Team to duplicate.";
$lang->project->copyNoProject   = "No {$lang->projectCommon} can be duplicated.";
$lang->project->copyFromProject = "Duplicate from {$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy      = 'Cancel Duplication';
$lang->project->byPeriod        = 'By Time';
$lang->project->byUser          = 'By User';

/* 交互提示。*/
$lang->project->confirmDelete         = "Do you want to delete {$lang->projectCommon}[%s]?";
$lang->project->confirmUnlinkMember   = "Do you want to unlink this User from {$lang->projectCommon}?";
$lang->project->confirmUnlinkStory    = "Do you want to unlink this Story from {$lang->projectCommon}?";
$lang->project->errorNoLinkedProducts = "No linked {$lang->productCommon} found in {$lang->projectCommon}. You will be directed to {$lang->productCommon}linked page.";
$lang->project->accessDenied          = "Access to {$lang->projectCommon} denied!";
$lang->project->tips                  = 'Note';
$lang->project->afterInfo             = "{$lang->projectCommon} is created. Next you can ";
$lang->project->setTeam               = 'Set Team';
$lang->project->linkStory             = 'Link Story';
$lang->project->createTask            = 'Create Task';
$lang->project->goback                = "Go Back";
$lang->project->noweekend             = 'Without Weekend';
$lang->project->withweekend           = 'With Weekend';
$lang->project->interval              = 'Intervals';
$lang->project->fixFirstWithLeft      = 'Modify the left time';

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "Burndown";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "Hour";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = "{$lang->projectCommon} Code";
$lang->project->placeholder->totalLeft = 'Total man-hour remained when start Project';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(Done)';

$lang->project->orderList['pri_asc']    = "Priority Asc";
$lang->project->orderList['pri_desc']   = "Priority Desc";
$lang->project->orderList['id_asc']     = "ID Asc";
$lang->project->orderList['id_desc']    = "ID Desc";
$lang->project->orderList['stage_asc']  = "Stage Asc";
$lang->project->orderList['stage_desc'] = "Stage Desc";

$lang->project->kanban      = "Kanban";
$lang->project->printKanban = "Print Kanban";
$lang->project->bugList     = "Bugs";

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'Print Kanban';
$lang->printKanban->content = 'Content';
$lang->printKanban->print   = 'Print';

$lang->printKanban->taskStatus = 'Status';

$lang->printKanban->typeList['all']       = 'All';
$lang->printKanban->typeList['increment'] = 'Increment';

$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['delayed']      = 'Delayed';
$lang->project->featureBar['task']['needconfirm']  = 'Story Changed';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->treeLevel = array();
$lang->project->treeLevel['root']    = 'Show Root';
$lang->project->treeLevel['story']   = 'Show Story';
$lang->project->treeLevel['task']    = 'Show Task';
$lang->project->treeLevel['all']     = 'Show All';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
