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
/* Fields. */
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
$lang->project->RM           = 'Release manager';
$lang->project->acl          = 'Access limitation';
$lang->project->teamname     = 'Team name';
$lang->project->products     = 'Products';
$lang->project->childProjects= 'Child projects';
$lang->project->whitelist    = 'Whitelist';

$lang->team->account     = 'Account';
$lang->team->role        = 'Role';
$lang->team->joinDate    = 'Join date';
$lang->team->workingHour = 'Hour/Day';

/* Lists. */
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = 'Waitting';
$lang->project->statusList['doing'] = 'Doing';
$lang->project->statusList['done']  = 'Done';

$lang->project->aclList['open']    = 'Default(Having the priviledge of project module can visit this project)';
$lang->project->aclList['private'] = 'Private(Only team members can visit)';
$lang->project->aclList['custom']  = 'Whitelist(Team members and who belongs to the whitelist grups can visit)';

/* Methods.*/
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

/* Browse. */
$lang->project->allTasks            = 'All';
$lang->project->assignedToMe        = 'To me';
$lang->project->finishedByMe        = 'My done';
$lang->project->statusWait          = 'Wait';
$lang->project->statusDoing         = 'Doing';
$lang->project->statusDone          = 'Done';
$lang->project->delayed             = 'Delayed';
$lang->project->groups['story']     = 'Group by story';
$lang->project->groups['status']    = 'Group by status';
$lang->project->groups['pri']       = 'Group by priority';
$lang->project->groups['openedby']  = 'Group by openedBy';
$lang->project->groups['assignedTo']= 'Group by assignedTo';
$lang->project->groups['finishedby']= 'Group by finishedBy';
$lang->project->groups['closedby']  = 'Group by closedBy';
$lang->project->groups['estimate']  = 'Group by estimate';
$lang->project->groups['consumed']  = 'Group by consumed';
$lang->project->groups['left']      = 'Group by left';
$lang->project->groups['type']      = 'Group by type';
$lang->project->groups['deadline']  = 'Group by deadline';
$lang->project->listTaskNeedConfrim = 'Story changed';
$lang->project->byQuery             = 'Search';

/* Browse tabs. */
$lang->project->allProject          = 'All projects';

/* Notcie. */
$lang->project->selectProject  = "Select project";
$lang->project->beginAndEnd    = 'Begin and end';
$lang->project->lblStats       = 'Stats';
$lang->project->stats          = 'Total estimate is『%s』hours,<br />confused『%s』hours<br />left『%s』hours';
$lang->project->oneLineStats   = "Project『%s』, code is『%s』, products is『%s』,begin from『%s』to 『%s』,total estimate『%s』hours,consumed『%s』hours,left『%s』hours.";
$lang->project->storySummary   = "Total 『%s』stories, estimate『%s』hours.";
$lang->project->wbs            = "WBS";
$lang->project->largeBurnChart = 'View large';
$lang->project->howToUpdateBurn= "<a href='%s' class='helplink'><i>How?</i></a>";
$lang->project->whyNoStories   = "There no active stories to added to this project. Please check the linked product.";

/* Confirm. */
$lang->project->confirmDelete         = 'Are you sure to delete project [%s]?';
$lang->project->confirmUnlinkMember   = 'Are you sure to remove this user from this project?';
$lang->project->confirmUnlinkStory    = 'Are you sure to remove the story from this project?';
$lang->project->errorNoLinkedProducts = 'There is no linked products, go to the link page.';
$lang->project->accessDenied          = 'Access to this project denied.';
$lang->project->tips                  = 'Tips';
$lang->project->afterInfo             = 'Successful and you can do:';
$lang->project->setTeam               = 'Set team';
$lang->project->linkStory             = 'Link story';
$lang->project->createTask            = 'Create task';
$lang->project->goback                = 'Go back（Automatically after 5 seconds）';

/* Report. */
$lang->project->charts->burn->graph->caption      = "Burndown chart";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
