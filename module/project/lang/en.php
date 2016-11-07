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
$lang->project->code          = 'Code';
$lang->project->begin         = 'Begin';
$lang->project->end           = 'End';
$lang->project->dateRange     = 'Time Frame';
$lang->project->to            = 'To';
$lang->project->days          = 'Work Days';
$lang->project->day           = 'day';
$lang->project->status        = 'Status';
$lang->project->desc          = 'Desc';
$lang->project->owner         = 'Owner';
$lang->project->PO            = $lang->productCommon . ' Owner';
$lang->project->PM            = $lang->projectCommon . ' Manager';
$lang->project->QD            = 'Quality director';
$lang->project->RD            = 'Release director';
$lang->project->acl           = 'Access Control';
$lang->project->teamname      = 'Team Name';
$lang->project->order         = "Sort {$lang->projectCommon}";
$lang->project->products      = "Link {$lang->productCommon}";
$lang->project->whitelist     = 'Whitelist';
$lang->project->totalEstimate = 'Estimation';
$lang->project->totalConsumed = 'Consumed';
$lang->project->totalLeft     = 'Total Left';
$lang->project->Left          = 'Left';
$lang->project->progess       = 'Progess';
$lang->project->hours         = 'Estimate %s, Consumed %s, Left %s';
$lang->project->viewBug       = 'View Bug';
$lang->project->noProduct     = "No {$lang->productCommon}";
$lang->project->createStory   = "Add Story";
$lang->project->all           = 'All';
$lang->project->undone        = 'Undone';
$lang->project->unclosed      = 'Unclosed';
$lang->project->typeDesc      = 'No burndown and story in OPS';
$lang->project->mine          = 'My Responsibility: ';
$lang->project->other         = 'Other:';
$lang->project->deleted       = 'Deleted';
$lang->project->delayed       = 'Delayed';

$lang->project->start    = 'Start';
$lang->project->activate = 'Activate';
$lang->project->putoff   = 'Putoff';
$lang->project->suspend  = 'Suspend';
$lang->project->close    = 'Close';

$lang->project->typeList['sprint']    = 'Sprint';
$lang->project->typeList['waterfall'] = 'Waterfall';
$lang->project->typeList['ops']       = 'OPS';

$lang->project->endList[7]   = 'One Week';
$lang->project->endList[14]  = '2 Weeks';
$lang->project->endList[31]  = 'One Month';
$lang->project->endList[62]  = '2 Months';
$lang->project->endList[93]  = '3 Months';
$lang->project->endList[186] = '6 Months';
$lang->project->endList[365] = 'One Year';

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
$lang->project->statusList['wait']      = 'Pending';
$lang->project->statusList['doing']     = 'In Progress';
$lang->project->statusList['suspended'] = 'Suspended';
$lang->project->statusList['done']      = 'Done';

$lang->project->aclList['open']    = "Default(With View permission of {$lang->projectCommon}, you will have access to {$lang->projectCommon}.)";
$lang->project->aclList['private'] = 'Private(Only team members can access to it.)';
$lang->project->aclList['custom']  = 'Whitelist(Team members and the whitelist members can access to it.)';

/* 方法列表。*/
$lang->project->index            = "Home";
$lang->project->task             = 'Task';
$lang->project->groupTask        = 'View Task by Group';
$lang->project->story            = 'Story';
$lang->project->bug              = 'Bug';
$lang->project->dynamic          = 'Dynamic';
$lang->project->build            = 'Build';
$lang->project->testtask         = 'Test Task';
$lang->project->burn             = 'Burndown Chart';
$lang->project->baseline         = 'Base Line';
$lang->project->computeBurn      = 'Update';
$lang->project->burnData         = 'Burndown Chart Data';
$lang->project->fixFirst         = 'Edit First Day Data';
$lang->project->team             = 'Team Member';
$lang->project->doc              = 'Doc';
$lang->project->manageProducts   = 'Link ' . $lang->productCommon;
$lang->project->linkStory        = 'Link Story';
$lang->project->unlinkStoryTasks = 'Unlinked Task';
$lang->project->view             = "Info";
$lang->project->create           = "Add";
$lang->project->copy             = "Copy {$lang->projectCommon}";
$lang->project->delete           = "Delete";
$lang->project->browse           = "Browse";
$lang->project->edit             = "Edit";
$lang->project->batchEdit        = "Batch Edit";
$lang->project->manageMembers    = 'Team Management';
$lang->project->unlinkMember     = 'Remove Member';
$lang->project->unlinkStory      = 'Remove Story';
$lang->project->batchUnlinkStory = 'Batch Remove Story';
$lang->project->importTask       = 'Import Task';
$lang->project->importBug        = 'Import Bug';
$lang->project->updateOrder      = 'Order';
$lang->project->tree             = 'Tree Diagram';

/* 分组浏览。*/
$lang->project->allTasks             = 'All';
$lang->project->assignedToMe         = 'Assigned to Me';

$lang->project->statusSelects['']             = 'More';
$lang->project->statusSelects['wait']         = 'Pending';
$lang->project->statusSelects['doing']        = 'Ongoing';
$lang->project->statusSelects['finishedbyme'] = 'Finished by Me';
$lang->project->statusSelects['done']         = 'Done';
$lang->project->statusSelects['closed']       = 'Closed';
$lang->project->statusSelects['cancel']       = 'Cancelled';

$lang->project->groups['']           = 'Group View';
$lang->project->groups['story']      = 'By Story';
$lang->project->groups['status']     = 'By Status';
$lang->project->groups['pri']        = 'By Priority';
$lang->project->groups['assignedTo'] = 'By AssignedTo';
$lang->project->groups['finishedBy'] = 'By FinishedBy';
$lang->project->groups['closedBy']   = 'By ClosedBy';
$lang->project->groups['type']       = 'By Type';
$lang->project->groups['deadline']   = 'By Deadline';

$lang->project->groupFilter['story']['all']         = $lang->project->all;
$lang->project->groupFilter['story']['linked']      = 'Task Linked to Story';
$lang->project->groupFilter['pri']['all']           = $lang->project->all;
$lang->project->groupFilter['pri']['setted']        = 'Congfigured';
$lang->project->groupFilter['assignedTo']['undone'] = 'Pending';
$lang->project->groupFilter['assignedTo']['all']    = $lang->project->all;
$lang->project->groupFilter['finishedBy']['done']   = 'Done';
$lang->project->groupFilter['finishedBy']['all']    = $lang->project->all;
$lang->project->groupFilter['closedBy']['closed']   = 'Closed';
$lang->project->groupFilter['closedBy']['all']      = $lang->project->all;
$lang->project->groupFilter['deadline']['all']      = $lang->project->all;
$lang->project->groupFilter['deadline']['setted']   = 'Configured';

$lang->project->byQuery              = 'Search';

/* 查询条件列表。*/
$lang->project->allProject      = "All{$lang->projectCommon}";
$lang->project->aboveAllProduct = "All the Above {$lang->productCommon}";
$lang->project->aboveAllProject = "All the Above {$lang->projectCommon}";

/* 页面提示。*/
$lang->project->selectProject   = "Select {$lang->projectCommon}";
$lang->project->beginAndEnd     = 'Time Frame';
$lang->project->lblStats        = 'Man-Hour Report';
$lang->project->stats           = 'Available <strong>%s</strong> man-hour <br /> Estimated total <strong>%s</strong> man-hour<br /> Consumed <strong>%s</strong> man-hour<br /> Estimated left <strong>%s</strong> man-hour';
$lang->project->taskSummary     = " <strong>%s</strong> Tasks on this page Pending <strong>%s</strong> Ongoing <strong>%s</strong> Total estimated <strong>%s</strong> man-hour Consumed <strong>%s</strong> man-hour Left <strong>%s</strong> man-hour";
$lang->project->memberHours     = "%s has <strong>%s</strong> man-hour available ";
$lang->project->groupSummary    = "<strong>%s</strong> Tasks in this group Pending <strong>%s</strong> Ongoing <strong>%s</strong> Total estimated <strong>%s</strong> man-hour Consumed <strong>%s</strong> man-hour Left <strong>%s</strong> man-hour";
$lang->project->noTimeSummary   = " <strong>%s</strong> Tasks in this group Pending <strong>%s</strong> Ongoing <strong>%s</strong>";
$lang->project->wbs             = "Decompose Task";
$lang->project->batchWBS        = "Batch Decompose";
$lang->project->howToUpdateBurn = "<a href='http://api.zentao.net/goto.php?item=burndown&lang=zh-cn' target='_blank' title='How to Update the Burndown Chart?' class='btn btn-sm'>Help</a>";
$lang->project->whyNoStories    = "No Story can be linked. Please check whether there is Story in {$lang->projectCommon} related {$lang->productCommon} and make sure it has been reviewed.";
$lang->project->doneProjects    = 'Done';
$lang->project->selectDept      = 'Select Dept';
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
$lang->project->confirmUnlinkMember   = "Do you want to remove this User from {$lang->projectCommon}中?";
$lang->project->confirmUnlinkStory    = "Do you want to remove this Story from {$lang->projectCommon}?";
$lang->project->errorNoLinkedProducts = "No linked {$lang->productCommon} found in {$lang->projectCommon}. You will be directed to {$lang->productCommon}linked page.";
$lang->project->accessDenied          = "Access to {$lang->projectCommon} denied!";
$lang->project->tips                  = 'Note';
$lang->project->afterInfo             = "{$lang->projectCommon} created. You can do: ";
$lang->project->setTeam               = 'Team Settings';
$lang->project->linkStory             = 'Link Story';
$lang->project->createTask            = 'Creat Task';
$lang->project->goback                = "Back";
$lang->project->noweekend             = 'Without Weekend';
$lang->project->withweekend           = 'With Weekend';
$lang->project->interval              = 'Intervals';

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "Burndown Chart";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "Hour";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = "{$lang->projectCommon} Code";
$lang->project->placeholder->totalLeft = 'Total man-hour left when start Project';

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
$lang->project->bugList     = "Bug List";

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'Print Kanban';
$lang->printKanban->content = 'Content';
$lang->printKanban->print   = 'Print';

$lang->printKanban->taskStatus = 'Status';

$lang->printKanban->typeList['all']       = 'All';
$lang->printKanban->typeList['increment'] = 'Increment';

$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['delayed']      = 'Delayed';
$lang->project->featureBar['task']['needconfirm']  = 'Story Changed';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->treeLevel = array();
$lang->project->treeLevel['story']   = 'Show Story';
$lang->project->treeLevel['task']    = 'Show Task';
