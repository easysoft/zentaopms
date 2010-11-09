<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* 字段列表.*/
$lang->project->common       = 'Project';
$lang->project->id           = 'ID';
$lang->project->company      = 'Company';
$lang->project->iscat        = 'Is category';
$lang->project->type         = 'Type';
$lang->project->parent       = 'Parent';
$lang->project->name         = 'Name';
$lang->project->code         = 'Code';
$lang->project->begin        = 'Begin';
$lang->project->end          = 'End';
$lang->project->status       = 'Status';
$lang->project->statge       = 'Stage';
$lang->project->pri          = 'Priority';
$lang->project->desc         = 'Desc';
$lang->project->goal         = 'Goal';
$lang->project->openedBy     = 'Opened by';
$lang->project->openedDate   = 'Opened date';
$lang->project->closedBy     = 'Closed by';
$lang->project->closedDate   = 'Closed date';
$lang->project->canceledBy   = 'Canceled by';
$lang->project->canceledDate = 'Canceled date';
$lang->project->PO           = 'Product owner';
$lang->project->PM           = 'Project manager';
$lang->project->QM           = 'QA manager';
$lang->project->acl          = 'Access limitation';
$lang->project->teamname     = 'Team name';
$lang->project->products     = 'Products';
$lang->project->childProjects= 'Child projects';
$lang->project->whitelist    = 'Whitelist';

$lang->team->account     = 'Account';
$lang->team->role        = 'Role';
$lang->team->joinDate    = 'Join date';
$lang->team->workingHour = 'Hour/Day';

/* 字段取值列表.*/
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = 'Waitting';
$lang->project->statusList['doing'] = 'Doing';
$lang->project->statusList['done']  = 'Done';

$lang->project->aclList['open']    = 'Default(Having the priviledge of project module can visit this project)';
$lang->project->aclList['private'] = 'Private(Only team members can visit)';
$lang->project->aclList['custom']  = 'Whitelist(Team members and who belongs to the whitelist grups can visit)';

/* 方法列表.*/
$lang->project->index          = "Index";
$lang->project->task           = 'Task';
$lang->project->groupTask      = 'View task by group';
$lang->project->story          = 'Story';
$lang->project->bug            = 'Bug';
$lang->project->build          = 'Build';
$lang->project->burn           = 'Burndown chart';
$lang->project->computeBurn    = 'Update burndown';
$lang->project->burnData       = 'Burndown data';
$lang->project->team           = 'Team';
$lang->project->doc            = 'Doc';
$lang->project->manageProducts = 'Link product';
$lang->project->linkStory      = 'Link story';
$lang->project->view           = "Info";
$lang->project->create         = "Add";
$lang->project->delete         = "Delete";
$lang->project->browse         = "Browse";
$lang->project->edit           = "Edit";
$lang->project->manageMembers  = 'Manage team members';
$lang->project->unlinkMember   = 'Remove member';
$lang->project->unlinkStory    = 'Remove story';
$lang->project->importTask     = 'Import undone';
$lang->project->ajaxGetProducts= "API: get project's products";

/* 分组浏览.*/
$lang->project->listTask            = 'List';
$lang->project->groupTaskByStory    = 'By story';
$lang->project->groupTaskByStatus   = 'By status';
$lang->project->groupTaskByPri      = 'By priority';
$lang->project->groupTaskByOwner    = 'By owner';
$lang->project->groupTaskByEstimate = 'By estimate';
$lang->project->groupTaskByConsumed = 'By consumed';
$lang->project->groupTaskByLeft     = 'By left';
$lang->project->groupTaskByType     = 'By type';
$lang->project->groupTaskByDeadline = 'BY deadline';
$lang->project->listTaskNeedConfrim = 'Story changed';

/* 页面提示.*/
$lang->project->selectProject  = "Select project";
$lang->project->beginAndEnd    = 'Begin and end';
$lang->project->lblStats       = 'Stats';
$lang->project->stats          = 'Total estimate is『%s』hours,<br />confused『%s』hours<br />left『%s』hours';
$lang->project->oneLineStats   = "Project『%s』, code is『%s』, products is『%s』,begin from『%s』to 『%s』,total estimate『%s』hours,consumed『%s』hours,left『%s』hours.";
$lang->project->storySummary   = "Total 『%s』stories, estimate『%s』hours.";
$lang->project->wbs            = "WBS";
$lang->project->largeBurnChart = 'View large';

/* 交互提示.*/
$lang->project->confirmDelete         = 'Are you sure to delete project [%s]?';
$lang->project->confirmUnlinkMember   = 'Are you sure to remove this user from this project?';
$lang->project->confirmUnlinkStory    = 'Are you sure to remove the story from this project?';
$lang->project->errorNoLinkedProducts = 'There is no linked products, go to the link page.';
$lang->project->accessDenied          = 'Access to this project denied.';

/* 统计.*/
$lang->project->charts->burn->graph->caption      = "Burndown chart";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
