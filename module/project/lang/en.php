<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->project->common        = 'Project';
$lang->project->id            = 'ID';
$lang->project->company       = 'Company';
$lang->project->fromproject   = 'FromProject';
$lang->project->iscat         = 'Is category';
$lang->project->type          = 'Type';
$lang->project->parent        = 'Parent';
$lang->project->name          = 'Name';
$lang->project->code          = 'Code';
$lang->project->begin         = 'Begin';
$lang->project->end           = 'End';
$lang->project->days          = 'Workdays';
$lang->project->day           = 'day';
$lang->project->status        = 'Status';
$lang->project->statge        = 'Stage';
$lang->project->pri           = 'Priority';
$lang->project->desc          = 'Desc';
$lang->project->goal          = 'Goal';
$lang->project->openedBy      = 'Opened by';
$lang->project->openedDate    = 'Opened date';
$lang->project->closedBy      = 'Closed by';
$lang->project->closedDate    = 'Closed date';
$lang->project->canceledBy    = 'Canceled by';
$lang->project->canceledDate  = 'Canceled date';
$lang->project->PO            = 'Product owner';
$lang->project->PM            = 'Project manager';
$lang->project->QD            = 'Quality director';
$lang->project->RD            = 'Release director';
$lang->project->acl           = 'Access limitation';
$lang->project->teamname      = 'Team name';
$lang->project->order         = 'Project order';
$lang->project->products      = 'Products';
$lang->project->childProjects = 'Child projects';
$lang->project->whitelist     = 'Whitelist';
$lang->project->totalEstimate = 'Est';
$lang->project->totalConsumed = 'Done';
$lang->project->totalLeft     = 'Left';
$lang->project->progess       = 'Progess';
$lang->project->viewBug       = 'View bug';
$lang->project->createTesttask= 'Create testtask';
$lang->project->noProduct     = 'No product';
$lang->project->select        = '--select project--';
$lang->project->createStory   = "Create story";

$lang->project->endList[14]  = 'Two Weeks';
$lang->project->endList[31]  = 'One Month';
$lang->project->endList[62]  = 'Two Months';
$lang->project->endList[93]  = 'Three Months';
$lang->project->endList[186] = 'Half Of Year';
$lang->project->endList[365] = 'One Year';

$lang->team = new stdclass();
$lang->team->account    = 'Account';
$lang->team->role       = 'Role';
$lang->team->join       = 'Join date';
$lang->team->hours      = 'Hour/Day';
$lang->team->days       = 'Workdays';
$lang->team->totalHours = 'Total';

$lang->project->basicInfo = 'Basic info';
$lang->project->otherInfo = 'Other info';

/* Lists. */
$lang->project->statusList['']      = '';
$lang->project->statusList['wait']  = 'Pending';
$lang->project->statusList['doing'] = 'Progressing';
$lang->project->statusList['done']  = 'Done';

$lang->project->aclList['open']    = 'Default(Having the priviledge of project module can visit this project)';
$lang->project->aclList['private'] = 'Private(Only team members can visit)';
$lang->project->aclList['custom']  = 'Whitelist(Team members and who belongs to the whitelist grups can visit)';

/* Methods.*/
$lang->project->index           = "Index";
$lang->project->task            = 'Task';
$lang->project->groupTask       = 'View task by group';
$lang->project->story           = 'Story';
$lang->project->bug             = 'Bug';
$lang->project->dynamic         = 'Dynamic';
$lang->project->build           = 'Build';
$lang->project->testtask        = 'Testtask';
$lang->project->burn            = 'Burndown chart';
$lang->project->computeBurn     = 'Update burndown';
$lang->project->burnData        = 'Burndown data';
$lang->project->team            = 'Team';
$lang->project->doc             = 'Doc';
$lang->project->manageProducts  = 'Link product';
$lang->project->linkStory       = 'Link story';
$lang->project->view            = "Info";
$lang->project->create          = "Add";
$lang->project->copy            = "Copy a project";
$lang->project->delete          = "Delete";
$lang->project->browse          = "Browse";
$lang->project->edit            = "Edit";
$lang->project->manageMembers   = 'Manage team members';
$lang->project->unlinkMember    = 'Remove member';
$lang->project->unlinkStory     = 'Remove story';
$lang->project->importTask      = 'Transfer task';
$lang->project->importBug       = 'Import bug';
$lang->project->ajaxGetProducts = "API: get project's products";

/* Browse. */
$lang->project->allTasks             = 'All';
$lang->project->assignedToMe         = 'To me';

$lang->project->statusSelects['']             = 'More';
$lang->project->statusSelects['finishedbyme'] = 'Mydone'; 
$lang->project->statusSelects['wait']         = 'Pending';
$lang->project->statusSelects['doing']        = 'Progressing';
$lang->project->statusSelects['done']         = 'Done'; 
$lang->project->statusSelects['closed']       = 'Closed';
$lang->project->statusSelects['delayed']      = 'Delayed';
$lang->project->statusSelects['needconfirm']  = 'Story changed';
$lang->project->groups['']           = 'Group View';
$lang->project->groups['story']      = 'Group by story';
$lang->project->groups['status']     = 'Group by status';
$lang->project->groups['pri']        = 'Group by priority';
$lang->project->groups['openedby']   = 'Group by openedBy';
$lang->project->groups['assignedTo'] = 'Group by assignedTo';
$lang->project->groups['finishedby'] = 'Group by finishedBy';
$lang->project->groups['closedby']   = 'Group by closedBy';
$lang->project->groups['estimate']   = 'Group by estimate';
$lang->project->groups['consumed']   = 'Group by consumed';
$lang->project->groups['left']       = 'Group by left';
$lang->project->groups['type']       = 'Group by type';
$lang->project->groups['deadline']   = 'Group by deadline';

$lang->project->moduleTask           = 'Module';
$lang->project->byQuery              = 'Search';

/* Browse tabs. */
$lang->project->allProject      = 'All projects';
$lang->project->aboveAllProduct = 'Above all products';
$lang->project->aboveAllProject = 'Above all projects';

/* Notcie. */
$lang->project->selectProject   = "Select project";
$lang->project->beginAndEnd     = 'Begin and end';
$lang->project->lblStats        = 'Stats';
$lang->project->stats           = 'Total work hours is 『%s』hours, <br />Total estimate is『%s』hours,<br />Total confused is『%s』hours<br />Total left is『%s』hours';
$lang->project->oneLineStats    = "Project『%s』, code is『%s』, products is『%s』,begin from『%s』to 『%s』,total estimate『%s』hours,consumed『%s』hours,left『%s』hours.";
$lang->project->taskSummary     = "Total tasks shown: <strong>%s</strong>. Pending: <strong>%s</strong>. In progress: <strong>%s</strong>. Estimate: <strong>%s</strong> hrs. Consumed: <strong>%s</strong> hrs. Hours left: <strong>%s</strong>.";
$lang->project->memberHours     = "%s has <strong>%s</strong> workhours, ";
$lang->project->groupSummary    = "<strong>%s</strong> tasks in this group, wait:<strong>%s</strong>, doing:<strong>%s</strong>, estimate <strong>%s</strong>, consumed <strong>%s</strong>, left <strong>%s</strong> hours.";
$lang->project->wbs             = "WBS";
$lang->project->batchWBS        = "Batch WBS";
$lang->project->largeBurnChart  = 'View large';
$lang->project->howToUpdateBurn = "<a href='%s' class='helplink'><i>How?</i></a>";
$lang->project->whyNoStories    = "There no active stories to added to this project. Please check the linked product.";
$lang->project->doneProjects    = 'Done';
$lang->project->unDoneProjects  = 'Undone';

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
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = "Burndown chart";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "HOUR";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code = 'Project code';

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->doing = '(Doing)';
$lang->project->selectGroup->done  = '(Done)';

$lang->project->projectTasks = 'ProjectTasks';
