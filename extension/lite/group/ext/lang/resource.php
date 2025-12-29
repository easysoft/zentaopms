<?php

global $config;

/* Module order. */
$lang->moduleOrder = array();

$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';

$lang->moduleOrder[20]  = 'personnel';
$lang->moduleOrder[30]  = 'story';

$lang->moduleOrder[45]  = 'project';
$lang->moduleOrder[50]  = 'projectstory';
$lang->moduleOrder[55]  = 'execution';
$lang->moduleOrder[60]  = 'task';

$lang->moduleOrder[110] = 'doc';

$lang->moduleOrder[120] = 'company';
$lang->moduleOrder[125] = 'dept';
$lang->moduleOrder[130] = 'group';
$lang->moduleOrder[135] = 'user';

$lang->moduleOrder[140] = 'admin';
$lang->moduleOrder[142] = 'stage';
$lang->moduleOrder[145] = 'extension';
$lang->moduleOrder[150] = 'custom';
$lang->moduleOrder[155] = 'action';

$lang->moduleOrder[175] = 'search';
$lang->moduleOrder[180] = 'tree';
$lang->moduleOrder[190] = 'file';
$lang->moduleOrder[195] = 'misc';
$lang->moduleOrder[200] = 'backup';
$lang->moduleOrder[205] = 'cron';
$lang->moduleOrder[215] = 'message';

/* Backup xuan related stuff. */
if(isset($config->xuanxuan))
{
    $imResource      = !empty($lang->resource->im) ? $lang->resource->im : '';
    $clientResource  = !empty($lang->resource->client) ? $lang->resource->client : '';
    $settingResource = !empty($lang->resource->setting) ? $lang->resource->setting : '';
}

/* Reset resource. */
$lang->resource = new stdclass();

/* Reapply xuan stuff. */
if(isset($config->xuanxuan))
{
    $lang->resource->im      = $imResource;
    $lang->resource->client  = $clientResource;
    $lang->resource->setting = $settingResource;
    if(isset($lang->admin->methodOrder[26]))   $lang->admin->methodOrder[26]   = 'xuanxuan';
    if(isset($lang->setting->methodOrder[26])) $lang->setting->methodOrder[26] = 'xuanxuan';
}

/* Index module. */
$lang->resource->index = new stdclass();
$lang->resource->index->index = 'index';

$lang->index->methodOrder[0] = 'index';

/* My module. */
$lang->resource->my = new stdclass();
$lang->resource->my->index           = 'indexAction';
$lang->resource->my->todo            = 'todo';
$lang->resource->my->calendar        = 'calendarAction';
$lang->resource->my->work            = 'workAction';
$lang->resource->my->contribute      = 'contributeAction';
$lang->resource->my->profile         = 'profileAction';
$lang->resource->my->uploadAvatar    = 'uploadAvatar';
//$lang->resource->my->preference      = 'preference';
$lang->resource->my->dynamic         = 'dynamicAction';
$lang->resource->my->editProfile     = 'editProfile';
$lang->resource->my->changePassword  = 'changePassword';
$lang->resource->my->manageContacts  = 'manageContacts';
$lang->resource->my->deleteContacts  = 'deleteContacts';
$lang->resource->my->score           = 'score';
$lang->resource->my->team            = 'team';
//$lang->resource->my->requirement     = 'requirement';
$lang->resource->my->story           = 'story';
$lang->resource->my->task            = 'task';
//$lang->resource->my->bug             = 'bug';
$lang->resource->my->doc             = 'doc';
//$lang->resource->my->testtask        = 'testtask';
//$lang->resource->my->testcase        = 'testcase';
$lang->resource->my->execution       = 'execution';

$lang->my->methodOrder[1]   = 'index';
$lang->my->methodOrder[5]   = 'todo';
$lang->my->methodOrder[10]  = 'work';
$lang->my->methodOrder[15]  = 'contribute';
$lang->my->methodOrder[20]  = 'project';
$lang->my->methodOrder[25]  = 'profile';
$lang->my->methodOrder[30]  = 'uploadAvatar';
$lang->my->methodOrder[35]  = 'preference';
$lang->my->methodOrder[40]  = 'dynamic';
$lang->my->methodOrder[45]  = 'editProfile';
$lang->my->methodOrder[50]  = 'changePassword';
$lang->my->methodOrder[55]  = 'manageContacts';
$lang->my->methodOrder[60]  = 'deleteContacts';
$lang->my->methodOrder[65]  = 'score';
$lang->my->methodOrder[70]  = 'unbind';
$lang->my->methodOrder[75]  = 'team';
//$lang->my->methodOrder[80]  = 'requirement';
$lang->my->methodOrder[85]  = 'story';
$lang->my->methodOrder[90]  = 'task';
$lang->my->methodOrder[95]  = 'bug';
$lang->my->methodOrder[100] = 'testtask';
$lang->my->methodOrder[105] = 'testcase';
$lang->my->methodOrder[110] = 'execution';
$lang->my->methodOrder[115] = 'doc';

/* Todo. */
$lang->resource->todo = new stdclass();
$lang->resource->todo->create       = 'create';
$lang->resource->todo->createcycle  = 'createCycle';
$lang->resource->todo->batchCreate  = 'batchCreate';
$lang->resource->todo->edit         = 'edit';
$lang->resource->todo->batchEdit    = 'batchEdit';
$lang->resource->todo->view         = 'view';
$lang->resource->todo->delete       = 'delete';
$lang->resource->todo->export       = 'export';
$lang->resource->todo->start        = 'start';
$lang->resource->todo->finish       = 'finish';
$lang->resource->todo->batchFinish  = 'batchFinish';
$lang->resource->todo->import2Today = 'import2Today';
$lang->resource->todo->assignTo     = 'assignAction';
$lang->resource->todo->activate     = 'activate';
$lang->resource->todo->close        = 'close';
$lang->resource->todo->batchClose   = 'batchClose';

/* Personnel . */
$lang->resource->personnel = new stdclass();
$lang->resource->personnel->accessible      = 'accessible';
$lang->resource->personnel->invest          = 'invest';
$lang->resource->personnel->whitelist       = 'whitelist';
$lang->resource->personnel->addWhitelist    = 'addWhitelist';

$lang->personnel->methodOrder[5]  = 'accessible';
$lang->personnel->methodOrder[10] = 'invest';
$lang->personnel->methodOrder[15] = 'whitelist';
$lang->personnel->methodOrder[20] = 'addWhitelist';

if($config->systemMode == 'ALM')
{
    $lang->resource->my->project = 'project';

    /* Project. */
    $lang->resource->project = new stdclass();
    $lang->resource->project->index               = 'index';
    $lang->resource->project->browse              = 'browse';
    $lang->resource->project->kanban              = 'kanban';
  //$lang->resource->project->programTitle        = 'moduleOpen';
    $lang->resource->project->create              = 'create';
    $lang->resource->project->edit                = 'edit';
    $lang->resource->project->batchEdit           = 'batchEdit';
    $lang->resource->project->group               = 'group';
    $lang->resource->project->createGroup         = 'createGroup';
    $lang->resource->project->managePriv          = 'managePriv';
    $lang->resource->project->manageMembers       = 'manageMembers';
    $lang->resource->project->manageGroupMember   = 'manageGroupMember';
    $lang->resource->project->copyGroup           = 'copyGroup';
    $lang->resource->project->editGroup           = 'editGroup';
    $lang->resource->project->start               = 'start';
    $lang->resource->project->suspend             = 'suspend';
    $lang->resource->project->close               = 'close';
    $lang->resource->project->activate            = 'activate';
    $lang->resource->project->delete              = 'delete';
    $lang->resource->project->view                = 'view';
    $lang->resource->project->whitelist           = 'whitelist';
    $lang->resource->project->addWhitelist        = 'addWhitelist';
    $lang->resource->project->unbindWhitelist     = 'unbindWhitelist';
//   $lang->resource->project->manageProducts      = 'manageProducts';
    $lang->resource->project->dynamic             = 'dynamic';
//   $lang->resource->project->qa                  = 'qa';
//   $lang->resource->project->bug                 = 'bug';
//   $lang->resource->project->testcase            = 'testcase';
//   $lang->resource->project->testtask            = 'testtask';
//   $lang->resource->project->testreport          = 'testreport';
    $lang->resource->project->execution           = 'execution';
    $lang->resource->project->export              = 'export';
    $lang->resource->project->updateOrder         = 'updateOrder';
    $lang->resource->project->team                = 'teamAction';
    $lang->resource->project->unlinkMember        = 'unlinkMember';

    $lang->project->methodOrder[0]   = 'index';
    $lang->project->methodOrder[5]   = 'browse';
    $lang->project->methodOrder[10]  = 'kanban';
    $lang->project->methodOrder[15]  = 'projectTitle';
    $lang->project->methodOrder[20]  = 'create';
    $lang->project->methodOrder[25]  = 'edit';
    $lang->project->methodOrder[30]  = 'batchEdit';
    $lang->project->methodOrder[35]  = 'group';
    $lang->project->methodOrder[40]  = 'createGroup';
    $lang->project->methodOrder[45]  = 'managePriv';
    $lang->project->methodOrder[50]  = 'manageMembers';
    $lang->project->methodOrder[55]  = 'manageGroupMember';
    $lang->project->methodOrder[60]  = 'copyGroup';
    $lang->project->methodOrder[65]  = 'editGroup';
    $lang->project->methodOrder[70]  = 'start';
    $lang->project->methodOrder[75]  = 'suspend';
    $lang->project->methodOrder[80]  = 'close';
    $lang->project->methodOrder[85]  = 'activate';
    $lang->project->methodOrder[90]  = 'updateOrder';
    $lang->project->methodOrder[95]  = 'delete';
    $lang->project->methodOrder[100] = 'view';
    $lang->project->methodOrder[105] = 'whitelist';
    $lang->project->methodOrder[110] = 'addWhitelist';
    $lang->project->methodOrder[115] = 'unbindWhitelist';
    $lang->project->methodOrder[120] = 'manageProducts';
    $lang->project->methodOrder[125] = 'view';
    $lang->project->methodOrder[130] = 'dynamic';
    $lang->project->methodOrder[140] = 'qa';
    $lang->project->methodOrder[145] = 'bug';
    $lang->project->methodOrder[150] = 'testcase';
    $lang->project->methodOrder[155] = 'testtask';
    $lang->project->methodOrder[160] = 'testreport';
    $lang->project->methodOrder[165] = 'execution';
    $lang->project->methodOrder[170] = 'export';
    $lang->project->methodOrder[180] = 'updateOrder';
    $lang->project->methodOrder[185] = 'team';
    $lang->project->methodOrder[190] = 'unlinkMember';

    /* Project Story. */
    $lang->resource->projectstory = new stdclass();
    $lang->resource->projectstory->story             = 'story';
//    $lang->resource->projectstory->track             = 'trackAction';
    $lang->resource->projectstory->view              = 'view';
    $lang->resource->projectstory->linkStory         = 'linkStory';
    $lang->resource->projectstory->unlinkStory       = 'unlinkStory';
    $lang->resource->projectstory->batchUnlinkStory  = 'batchUnlinkStory';
    $lang->resource->projectstory->batchReview       = 'batchReview';
//    $lang->resource->projectstory->importplanstories = 'importplanstories';

    $lang->projectstory->methodOrder[5]  = 'story';
    $lang->projectstory->methodOrder[10] = 'track';
    $lang->projectstory->methodOrder[15] = 'view';
    $lang->projectstory->methodOrder[20] = 'linkStory';
    $lang->projectstory->methodOrder[25] = 'unlinkStory';
    $lang->projectstory->methodOrder[23] = 'importplanstories';
    $lang->projectstory->methodOrder[30] = 'batchReview';
}

/* Story. */
$lang->resource->story = new stdclass();
$lang->resource->story->create             = 'create';
$lang->resource->story->batchCreate        = 'batchCreate';
$lang->resource->story->edit               = 'editAction';
$lang->resource->story->linkStory          = 'linkStory';
$lang->resource->story->batchEdit          = 'batchEdit';
$lang->resource->story->export             = 'exportAction';
$lang->resource->story->delete             = 'deleteAction';
$lang->resource->story->view               = 'view';
$lang->resource->story->change             = 'changeAction';
$lang->resource->story->review             = 'reviewAction';
$lang->resource->story->submitReview       = 'submitReview';
$lang->resource->story->batchReview        = 'batchReview';
$lang->resource->story->recall             = 'recall';
$lang->resource->story->assignTo           = 'assignAction';
$lang->resource->story->close              = 'closeAction';
$lang->resource->story->batchClose         = 'batchClose';
$lang->resource->story->activate           = 'activateAction';
$lang->resource->story->tasks              = 'tasks';
//$lang->resource->story->bugs               = 'bugs';
//$lang->resource->story->cases              = 'cases';
//$lang->resource->story->zeroCase           = 'zeroCase';
$lang->resource->story->report             = 'reportAction';
$lang->resource->story->batchChangePlan    = 'batchChangePlan';
$lang->resource->story->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->story->batchChangeStage   = 'batchChangeStage';
$lang->resource->story->batchAssignTo      = 'batchAssignTo';
$lang->resource->story->batchChangeModule  = 'batchChangeModule';
$lang->resource->story->batchToTask        = 'batchToTask';
//$lang->resource->story->track              = 'trackAB';
$lang->resource->story->processStoryChange = 'processStoryChange';

$lang->story->methodOrder[5]   = 'create';
$lang->story->methodOrder[10]  = 'batchCreate';
$lang->story->methodOrder[15]  = 'edit';
$lang->story->methodOrder[20]  = 'export';
$lang->story->methodOrder[25]  = 'delete';
$lang->story->methodOrder[30]  = 'view';
$lang->story->methodOrder[35]  = 'change';
$lang->story->methodOrder[40]  = 'review';
$lang->story->methodOrder[44]  = 'submitReview';
$lang->story->methodOrder[45]  = 'batchReview';
$lang->story->methodOrder[50]  = 'recall';
$lang->story->methodOrder[55]  = 'close';
$lang->story->methodOrder[60]  = 'batchClose';
$lang->story->methodOrder[65]  = 'batchChangePlan';
$lang->story->methodOrder[70]  = 'batchChangeStage';
$lang->story->methodOrder[75]  = 'assignTo';
$lang->story->methodOrder[80]  = 'batchAssignTo';
$lang->story->methodOrder[85]  = 'activate';
$lang->story->methodOrder[90]  = 'tasks';
$lang->story->methodOrder[95]  = 'bugs';
$lang->story->methodOrder[100] = 'cases';
$lang->story->methodOrder[105] = 'zeroCase';
$lang->story->methodOrder[110] = 'report';
$lang->story->methodOrder[115] = 'linkStory';
$lang->story->methodOrder[120] = 'batchChangeBranch';
$lang->story->methodOrder[125] = 'batchChangeModule';
$lang->story->methodOrder[130] = 'batchToTask';
$lang->story->methodOrder[135] = 'track';
$lang->story->methodOrder[140] = 'processStoryChange';

/* Kanban */
$lang->resource->kanban = new stdclass();
$lang->resource->kanban->space              = 'spaceCommon';
$lang->resource->kanban->createSpace        = 'createSpace';
$lang->resource->kanban->editSpace          = 'editSpace';
$lang->resource->kanban->closeSpace         = 'closeSpace';
$lang->resource->kanban->deleteSpace        = 'deleteSpace';
$lang->resource->kanban->create             = 'create';
$lang->resource->kanban->edit               = 'edit';
$lang->resource->kanban->setting            = 'settingKanban';
$lang->resource->kanban->view               = 'view';
$lang->resource->kanban->close              = 'close';
$lang->resource->kanban->delete             = 'delete';
$lang->resource->kanban->createRegion       = 'createRegion';
$lang->resource->kanban->editRegion         = 'editRegion';
$lang->resource->kanban->sortRegion         = 'sortRegion';
$lang->resource->kanban->sortGroup          = 'sortGroup';
$lang->resource->kanban->deleteRegion       = 'deleteRegion';
$lang->resource->kanban->createLane         = 'createLane';
$lang->resource->kanban->editLaneName       = 'editLaneName';
$lang->resource->kanban->editLaneColor      = 'editLaneColor';
$lang->resource->kanban->sortLane           = 'sortLane';
$lang->resource->kanban->deleteLane         = 'deleteLane';
$lang->resource->kanban->createColumn       = 'createColumn';
$lang->resource->kanban->splitColumn        = 'splitColumn';
$lang->resource->kanban->archiveColumn      = 'archiveColumn';
$lang->resource->kanban->restoreColumn      = 'restoreColumn';
$lang->resource->kanban->setColumn          = 'setColumn';
$lang->resource->kanban->setWIP             = 'setWIP';
$lang->resource->kanban->sortColumn         = 'sortColumn';
$lang->resource->kanban->deleteColumn       = 'deleteColumn';
$lang->resource->kanban->createCard         = 'createCard';
$lang->resource->kanban->editCard           = 'editCard';
$lang->resource->kanban->viewCard           = 'viewCard';
$lang->resource->kanban->sortCard           = 'sortCard';
$lang->resource->kanban->archiveCard        = 'archiveCard';
$lang->resource->kanban->assigntoCard       = 'assigntoCard';
//$lang->resource->kanban->copyCard           = 'copyCard';
$lang->resource->kanban->deleteCard         = 'deleteCard';
$lang->resource->kanban->moveCard           = 'moveCard';
$lang->resource->kanban->setCardColor       = 'setCardColor';
$lang->resource->kanban->viewArchivedColumn = 'viewArchivedColumn';
$lang->resource->kanban->viewArchivedCard   = 'viewArchivedCard';
$lang->resource->kanban->restoreCard        = 'restoreCard';
$lang->resource->kanban->batchCreateCard    = 'batchCreateCard';

$lang->kanban->methodOrder[5]   = 'space';
$lang->kanban->methodOrder[10]  = 'createSpace';
$lang->kanban->methodOrder[15]  = 'editSpace';
$lang->kanban->methodOrder[20]  = 'closeSpace';
$lang->kanban->methodOrder[25]  = 'deleteSpace';
$lang->kanban->methodOrder[35]  = 'create';
$lang->kanban->methodOrder[40]  = 'edit';
$lang->kanban->methodOrder[41]  = 'setting';
$lang->kanban->methodOrder[45]  = 'view';
$lang->kanban->methodOrder[50]  = 'close';
$lang->kanban->methodOrder[55]  = 'delete';
$lang->kanban->methodOrder[60]  = 'createRegion';
$lang->kanban->methodOrder[65]  = 'editRegion';
$lang->kanban->methodOrder[70]  = 'sortRegion';
$lang->kanban->methodOrder[72]  = 'sortGroup';
$lang->kanban->methodOrder[75]  = 'deleteRegion';
$lang->kanban->methodOrder[80]  = 'createLane';
$lang->kanban->methodOrder[85]  = 'setLane';
$lang->kanban->methodOrder[90]  = 'sortLane';
$lang->kanban->methodOrder[95]  = 'deleteLane';
$lang->kanban->methodOrder[100] = 'createColumn';
$lang->kanban->methodorder[105] = 'splitColumn';
$lang->kanban->methodorder[110] = 'restoreColumn';
$lang->kanban->methodOrder[115] = 'setColumn';
$lang->kanban->methodOrder[120] = 'setWIP';
$lang->kanban->methodOrder[125] = 'sortColumn';
$lang->kanban->methodOrder[130] = 'deleteColumn';
$lang->kanban->methodOrder[135] = 'createCard';
$lang->kanban->methodOrder[140] = 'editCard';
$lang->kanban->methodOrder[145] = 'viewCard';
$lang->kanban->methodOrder[150] = 'sortCard';
$lang->kanban->methodOrder[155] = 'archivedCard';
//$lang->kanban->methodOrder[160] = 'copyCard';
$lang->kanban->methodOrder[165] = 'deleteCard';
$lang->kanban->methodOrder[170] = 'assigntoCard';
$lang->kanban->methodOrder[175] = 'moveCard';
$lang->kanban->methodOrder[180] = 'setCardColor';
$lang->kanban->methodorder[190] = 'cardsSort';
$lang->kanban->methodOrder[195] = 'viewArchivedColumn';
$lang->kanban->methodorder[200] = 'viewArchivedCard';
$lang->kanban->methodorder[205] = 'archiveColumn';
$lang->kanban->methodorder[210] = 'restoreCard';
$lang->kanban->methodOrder[225] = 'batchCreateCard';

/* Execution. */
$lang->resource->execution = new stdclass();
$lang->resource->execution->view              = 'view';
$lang->resource->execution->create            = 'createExec';
$lang->resource->execution->edit              = 'editAction';
$lang->resource->execution->batchedit         = 'batchEditAction';
$lang->resource->execution->start             = 'startAction';
$lang->resource->execution->activate          = 'activateAction';
$lang->resource->execution->putoff            = 'delayAction';
$lang->resource->execution->suspend           = 'suspendAction';
$lang->resource->execution->close             = 'closeAction';
$lang->resource->execution->delete            = 'deleteAB';
$lang->resource->execution->task              = 'task';
$lang->resource->execution->grouptask         = 'groupTask';
$lang->resource->execution->importtask        = 'importTask';
//$lang->resource->execution->importplanstories = 'importPlanStories';
//$lang->resource->execution->importBug         = 'importBug';
$lang->resource->execution->story             = 'story';
//$lang->resource->execution->build             = 'build';
//$lang->resource->execution->qa                = 'qa';
//$lang->resource->execution->testtask          = 'testtask';
//$lang->resource->execution->testcase          = 'testcase';
//$lang->resource->execution->bug               = 'bug';
//$lang->resource->execution->testreport        = 'testreport';
//$lang->resource->execution->burn              = 'burn';
//$lang->resource->execution->computeBurn       = 'computeBurnAction';
//$lang->resource->execution->fixFirst          = 'fixFirst';
//$lang->resource->execution->burnData          = 'burnData';
$lang->resource->execution->team              = 'teamAction';
$lang->resource->execution->doc               = 'doc';
$lang->resource->execution->dynamic           = 'dynamic';
//$lang->resource->execution->manageProducts    = 'manageProducts';
//$lang->resource->execution->manageChilds    = 'manageChilds';
$lang->resource->execution->manageMembers     = 'manageMembers';
$lang->resource->execution->unlinkMember      = 'unlinkMember';
$lang->resource->execution->linkStory         = 'linkStory';
$lang->resource->execution->unlinkStory       = 'unlinkStory';
$lang->resource->execution->batchUnlinkStory  = 'batchUnlinkStory';
$lang->resource->execution->updateOrder       = 'updateOrder';
$lang->resource->execution->taskKanban        = 'taskKanban';
//$lang->resource->execution->printKanban       = 'printKanbanAction';
$lang->resource->execution->tree              = 'treeAction';
$lang->resource->execution->treeTask          = 'treeViewTask';
$lang->resource->execution->treeStory         = 'treeViewStory';
$lang->resource->execution->all               = 'allExecutionAB';
$lang->resource->execution->export            = 'exportAction';
$lang->resource->execution->storyKanban       = 'storyKanban';
$lang->resource->execution->storySort         = 'storySort';
$lang->resource->execution->whitelist         = 'whitelist';
$lang->resource->execution->addWhitelist      = 'addWhitelist';
$lang->resource->execution->unbindWhitelist   = 'unbindWhitelist';
//$lang->resource->execution->storyEstimate     = 'storyEstimate';
$lang->resource->execution->storyView         = 'storyView';
$lang->resource->execution->kanban            = 'kanban';
//if($config->systemMode == 'classic') $lang->resource->project->list = 'list';
if($config->edition != 'open') $lang->resource->execution->gantt    = 'ganttchart';
if($config->edition != 'open') $lang->resource->execution->calendar = 'calendar';

//$lang->execution->methodOrder[0]   = 'index';
//if($config->systemMode == 'classic') $lang->project->methodOrder[1] = 'list';
$lang->execution->methodOrder[5]   = 'view';
$lang->execution->methodOrder[15]  = 'create';
$lang->execution->methodOrder[20]  = 'edit';
$lang->execution->methodOrder[25]  = 'batchedit';
$lang->execution->methodOrder[30]  = 'start';
$lang->execution->methodOrder[35]  = 'activate';
$lang->execution->methodOrder[40]  = 'putoff';
$lang->execution->methodOrder[45]  = 'suspend';
$lang->execution->methodOrder[50]  = 'close';
$lang->execution->methodOrder[60]  = 'delete';
$lang->execution->methodOrder[65]  = 'task';
$lang->execution->methodOrder[70]  = 'grouptask';
$lang->execution->methodOrder[75]  = 'importtask';
$lang->execution->methodOrder[80]  = 'importplanstories';
$lang->execution->methodOrder[85]  = 'importBug';
$lang->execution->methodOrder[90]  = 'story';
$lang->execution->methodOrder[95]  = 'build';
$lang->execution->methodOrder[100] = 'qa';
$lang->execution->methodOrder[105] = 'testcase';
$lang->execution->methodOrder[110] = 'bug';
$lang->execution->methodOrder[115] = 'testtask';
$lang->execution->methodOrder[120] = 'testreport';
$lang->execution->methodOrder[125] = 'burn';
$lang->execution->methodOrder[130] = 'computeBurn';
$lang->execution->methodOrder[135] = 'fixFirst';
$lang->execution->methodOrder[140] = 'burnData';
$lang->execution->methodOrder[145] = 'team';
//$lang->execution->methodOrder[130] = 'doc';
$lang->execution->methodOrder[150] = 'dynamic';
$lang->execution->methodOrder[155] = 'manageProducts';
$lang->execution->methodOrder[160] = 'manageMembers';
$lang->execution->methodOrder[165] = 'unlinkMember';
$lang->execution->methodOrder[170] = 'linkStory';
$lang->execution->methodOrder[175] = 'unlinkStory';
$lang->execution->methodOrder[180] = 'batchUnlinkStory';
$lang->execution->methodOrder[185] = 'updateOrder';
$lang->execution->methodOrder[190] = 'taskKanban';
$lang->execution->methodOrder[195] = 'printKanban';
$lang->execution->methodOrder[210] = 'tree';
$lang->execution->methodOrder[215] = 'treeTask';
$lang->execution->methodOrder[220] = 'treeStory';
$lang->execution->methodOrder[225] = 'all';
$lang->execution->methodOrder[230] = 'export';
$lang->execution->methodOrder[235] = 'storyKanban';
$lang->execution->methodOrder[240] = 'storySort';
$lang->execution->methodOrder[245] = 'whitelist';
$lang->execution->methodOrder[250] = 'addWhitelist';
$lang->execution->methodOrder[255] = 'unbindWhitelist';
$lang->execution->methodOrder[260] = 'storyView';
$lang->execution->methodOrder[270] = 'kanban';
$lang->execution->methodOrder[280] = 'gantt';
$lang->execution->methodOrder[285] = 'calendar';

/* Task. */
$lang->resource->task = new stdclass();
$lang->resource->task->create             = 'create';
$lang->resource->task->edit               = 'edit';
$lang->resource->task->assignTo           = 'assignAction';
$lang->resource->task->start              = 'startAction';
$lang->resource->task->pause              = 'pauseAction';
$lang->resource->task->restart            = 'restartAction';
$lang->resource->task->finish             = 'finishAction';
$lang->resource->task->cancel             = 'cancelAction';
$lang->resource->task->close              = 'closeAction';
$lang->resource->task->batchCreate        = 'batchCreate';
$lang->resource->task->batchEdit          = 'batchEdit';
$lang->resource->task->batchClose         = 'batchClose';
$lang->resource->task->batchCancel        = 'batchCancel';
$lang->resource->task->batchAssignTo      = 'batchAssignTo';
$lang->resource->task->batchChangeModule  = 'batchChangeModule';
$lang->resource->task->activate           = 'activateAction';
$lang->resource->task->delete             = 'deleteAction';
$lang->resource->task->view               = 'view';
$lang->resource->task->export             = 'exportAction';
$lang->resource->task->confirmStoryChange = 'confirmStoryChange';
$lang->resource->task->recordWorkhour     = 'recordWorkhourAction';
$lang->resource->task->editEffort         = 'editEffort';
$lang->resource->task->deleteWorkhour     = 'deleteWorkhour';
$lang->resource->task->report             = 'reportChart';
if($config->edition != 'open') $lang->resource->task->exportTemplate = 'exportTemplate';
if($config->edition != 'open') $lang->resource->task->import        = 'import';

$lang->task->methodOrder[5]   = 'create';
$lang->task->methodOrder[10]  = 'batchCreate';
$lang->task->methodOrder[15]  = 'batchEdit';
$lang->task->methodOrder[20]  = 'edit';
$lang->task->methodOrder[25]  = 'assignTo';
$lang->task->methodOrder[30]  = 'batchAssignTo';
$lang->task->methodOrder[35]  = 'start';
$lang->task->methodOrder[40]  = 'pause';
$lang->task->methodOrder[45]  = 'restart';
$lang->task->methodOrder[50]  = 'finish';
$lang->task->methodOrder[55]  = 'cancel';
$lang->task->methodOrder[60]  = 'close';
$lang->task->methodOrder[65]  = 'batchClose';
$lang->task->methodOrder[70]  = 'activate';
$lang->task->methodOrder[75]  = 'delete';
$lang->task->methodOrder[80]  = 'view';
$lang->task->methodOrder[85]  = 'export';
$lang->task->methodOrder[90]  = 'confirmStoryChange';
$lang->task->methodOrder[95]  = 'recordWorkhour';
$lang->task->methodOrder[100] = 'editEffort';
$lang->task->methodOrder[105] = 'deleteWorkhour';
$lang->task->methodOrder[110] = 'report';
$lang->task->methodOrder[115] = 'batchChangeModule';
$lang->task->methodOrder[120] = 'exportTemplate';
$lang->task->methodOrder[125] = 'import';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->index              = 'index';
$lang->resource->doc->mySpace            = 'mySpace';
$lang->resource->doc->quick              = 'quick';
$lang->resource->doc->createSpace        = 'createSpace';
$lang->resource->doc->createLib          = 'createLibAction';
$lang->resource->doc->editLib            = 'editLibAction';
$lang->resource->doc->deleteLib          = 'deleteLibAction';
$lang->resource->doc->moveLib            = 'moveLibAction';
$lang->resource->doc->moveDoc            = 'moveDocAction';
$lang->resource->doc->create             = 'create';
$lang->resource->doc->view               = 'view';
$lang->resource->doc->edit               = 'edit';
$lang->resource->doc->delete             = 'delete';
$lang->resource->doc->deleteFile         = 'deleteFile';
$lang->resource->doc->collect            = 'collectAction';
$lang->resource->doc->projectSpace       = 'projectSpace';
$lang->resource->doc->teamSpace          = 'teamSpace';
$lang->resource->doc->showFiles          = 'showFiles';
$lang->resource->doc->addCatalog         = 'addCatalog';
$lang->resource->doc->editCatalog        = 'editCatalog';
$lang->resource->doc->sortDoclib         = 'sortDoclib';
$lang->resource->doc->sortCatalog        = 'sortCatalog';
$lang->resource->doc->sortDoc            = 'sortDoc';
$lang->resource->doc->deleteCatalog      = 'deleteCatalog';
$lang->resource->doc->browseTemplate     = 'browseTemplate';
$lang->resource->doc->createTemplate     = 'createTemplate';
$lang->resource->doc->editTemplate       = 'editTemplate';
$lang->resource->doc->moveTemplate       = 'moveTemplate';
$lang->resource->doc->sortTemplate       = 'sortTemplate';
$lang->resource->doc->deleteTemplate     = 'deleteTemplate';
$lang->resource->doc->viewTemplate       = 'viewTemplate';
$lang->resource->doc->addTemplateType    = 'addTemplateType';
$lang->resource->doc->editTemplateType   = 'editTemplateType';
$lang->resource->doc->deleteTemplateType = 'deleteTemplateType';
$lang->resource->doc->manageScope        = 'manageScope';

$lang->doc->methodOrder[5]   = 'index';
$lang->doc->methodOrder[10]  = 'mySpace';
$lang->doc->methodOrder[15]  = 'myView';
$lang->doc->methodOrder[20]  = 'myCollection';
$lang->doc->methodOrder[25]  = 'myCreation';
$lang->doc->methodOrder[30]  = 'createSpace';
$lang->doc->methodOrder[35]  = 'createLib';
$lang->doc->methodOrder[40]  = 'editLib';
$lang->doc->methodOrder[45]  = 'deleteLib';
$lang->doc->methodOrder[50]  = 'moveLib';
$lang->doc->methodOrder[55]  = 'create';
$lang->doc->methodOrder[60]  = 'edit';
$lang->doc->methodOrder[65]  = 'view';
$lang->doc->methodOrder[70]  = 'delete';
$lang->doc->methodOrder[75]  = 'deleteFile';
$lang->doc->methodOrder[80]  = 'collect';
$lang->doc->methodOrder[85]  = 'projectSpace';
$lang->doc->methodOrder[90]  = 'showFiles';
$lang->doc->methodOrder[95]  = 'addCatalog';
$lang->doc->methodOrder[100] = 'editCatalog';
$lang->doc->methodOrder[105] = 'sortDoclib';
$lang->doc->methodOrder[110] = 'sortCatalog';
$lang->doc->methodOrder[115] = 'sortDoc';
$lang->doc->methodOrder[120] = 'deleteCatalog';
// $lang->doc->methodOrder[125] = 'displaySetting';

/* Mail. */
$lang->resource->mail = new stdclass();
$lang->resource->mail->index       = 'index';
$lang->resource->mail->detect      = 'detectAction';
$lang->resource->mail->edit        = 'edit';
$lang->resource->mail->save        = 'saveAction';
$lang->resource->mail->test        = 'test';
$lang->resource->mail->reset       = 'resetAction';
$lang->resource->mail->browse      = 'browse';
$lang->resource->mail->delete      = 'delete';
$lang->resource->mail->resend      = 'resendAction';
$lang->resource->mail->batchDelete = 'batchDelete';

$lang->mail->methodOrder[5]  = 'index';
$lang->mail->methodOrder[10] = 'detect';
$lang->mail->methodOrder[15] = 'edit';
$lang->mail->methodOrder[20] = 'save';
$lang->mail->methodOrder[25] = 'test';
$lang->mail->methodOrder[30] = 'reset';
$lang->mail->methodOrder[35] = 'browse';
$lang->mail->methodOrder[40] = 'delete';
$lang->mail->methodOrder[45] = 'batchDelete';
$lang->mail->methodOrder[50] = 'resend';

/* Custom. */
$lang->resource->custom = new stdclass();
$lang->resource->custom->set                = 'set';
$lang->resource->custom->product            = 'productName';
$lang->resource->custom->execution          = 'executionCommon';
$lang->resource->custom->required           = 'required';
$lang->resource->custom->restore            = 'restore';
$lang->resource->custom->flow               = 'flow';
$lang->resource->custom->timezone           = 'timezone';
$lang->resource->custom->setStoryConcept    = 'setStoryConcept';
$lang->resource->custom->editStoryConcept   = 'editStoryConcept';
$lang->resource->custom->browseStoryConcept = 'browseStoryConcept';
$lang->resource->custom->setDefaultConcept  = 'setDefaultConcept';
$lang->resource->custom->deleteStoryConcept = 'deleteStoryConcept';
if($config->edition != 'open') $lang->resource->custom->libreoffice = 'libreOffice';

$lang->custom->methodOrder[10] = 'set';
$lang->custom->methodOrder[15] = 'product';
$lang->custom->methodOrder[20] = 'execution';
$lang->custom->methodOrder[25] = 'required';
$lang->custom->methodOrder[30] = 'restore';
$lang->custom->methodOrder[35] = 'flow';
$lang->custom->methodOrder[50] = 'timezone';
$lang->custom->methodOrder[55] = 'setStoryConcept';
$lang->custom->methodOrder[60] = 'editStoryConcept';
$lang->custom->methodOrder[65] = 'browseStoryConcept';
$lang->custom->methodOrder[70] = 'setDefaultConcept';
$lang->custom->methodOrder[75] = 'deleteStoryConcept';
$lang->custom->methodOrder[80] = 'libreoffice';

$lang->resource->datatable = new stdclass();
$lang->resource->datatable->setGlobal = 'setGlobal';

$lang->datatable->methodOrder[5]  = 'setGlobal';

/* Company. */
$lang->resource->company = new stdclass();
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->view   = 'view';
$lang->resource->company->dynamic= 'dynamic';

$lang->company->methodOrder[5]  = 'browse';
$lang->company->methodOrder[15] = 'edit';
$lang->company->methodOrder[25] = 'dynamic';

/* Department. */
$lang->resource->dept = new stdclass();
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChild';
$lang->resource->dept->edit        = 'edit';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'edit';
$lang->dept->methodOrder[25] = 'delete';

/* Group. */
$lang->resource->group = new stdclass();
$lang->resource->group->browse             = 'browse';
$lang->resource->group->create             = 'create';
$lang->resource->group->edit               = 'edit';
$lang->resource->group->copy               = 'copy';
$lang->resource->group->delete             = 'delete';
$lang->resource->group->manageView         = 'manageView';
$lang->resource->group->managePriv         = 'managePriv';
$lang->resource->group->manageMember       = 'manageMember';
$lang->resource->group->manageProjectAdmin = 'manageProjectAdmin';

$lang->group->methodOrder[5]  = 'browse';
$lang->group->methodOrder[10] = 'create';
$lang->group->methodOrder[15] = 'edit';
$lang->group->methodOrder[20] = 'copy';
$lang->group->methodOrder[25] = 'delete';
$lang->group->methodOrder[30] = 'managePriv';
$lang->group->methodOrder[35] = 'manageMember';
$lang->group->methodOrder[40] = 'manageProjectAdmin';

/* User. */
$lang->resource->user = new stdclass();
$lang->resource->user->create         = 'create';
$lang->resource->user->batchCreate    = 'batchCreate';
$lang->resource->user->view           = 'view';
$lang->resource->user->edit           = 'edit';
$lang->resource->user->unlock         = 'unlock';
$lang->resource->user->delete         = 'delete';
$lang->resource->user->todo           = 'todo';
$lang->resource->user->story          = 'story';
$lang->resource->user->task           = 'task';
//$lang->resource->user->bug            = 'bug';
//$lang->resource->user->testTask       = 'testTask';
//$lang->resource->user->testCase       = 'testCase';
$lang->resource->user->execution      = 'execution';
$lang->resource->user->dynamic        = 'dynamic';
$lang->resource->user->profile        = 'profile';
$lang->resource->user->batchEdit      = 'batchEdit';
$lang->resource->user->unbind         = 'unbind';
$lang->resource->user->setPublicTemplate = 'setPublicTemplate';

$lang->user->methodOrder[5]  = 'create';
$lang->user->methodOrder[7]  = 'batchCreate';
$lang->user->methodOrder[10] = 'view';
$lang->user->methodOrder[15] = 'edit';
$lang->user->methodOrder[20] = 'unlock';
$lang->user->methodOrder[25] = 'delete';
$lang->user->methodOrder[30] = 'todo';
$lang->user->methodOrder[35] = 'task';
$lang->user->methodOrder[40] = 'bug';
$lang->user->methodOrder[45] = 'project';
$lang->user->methodOrder[60] = 'dynamic';
$lang->user->methodOrder[70] = 'profile';
$lang->user->methodOrder[75] = 'batchEdit';
$lang->user->methodOrder[80] = 'unbind';
$lang->user->methodOrder[85] = 'setPublicTemplate';

/* Tree. */
$lang->resource->tree = new stdclass();
$lang->resource->tree->browse      = 'browse';
$lang->resource->tree->browseTask  = 'browseTask';
$lang->resource->tree->updateOrder = 'updateOrder';
$lang->resource->tree->manageChild = 'manageChild';
$lang->resource->tree->edit        = 'edit';
$lang->resource->tree->fix         = 'fix';
$lang->resource->tree->delete      = 'delete';

$lang->tree->methodOrder[5]  = 'browse';
$lang->tree->methodOrder[10] = 'browseTask';
$lang->tree->methodOrder[15] = 'updateOrder';
$lang->tree->methodOrder[20] = 'manageChild';
$lang->tree->methodOrder[25] = 'edit';
$lang->tree->methodOrder[30] = 'delete';

/* Search. */
$lang->resource->search = new stdclass();
$lang->resource->search->buildForm   = 'buildForm';
$lang->resource->search->buildQuery  = 'buildQuery';
$lang->resource->search->saveQuery   = 'saveQuery';
$lang->resource->search->deleteQuery = 'deleteQuery';
$lang->resource->search->select      = 'select';
$lang->resource->search->index       = 'index';
$lang->resource->search->buildIndex  = 'buildIndex';

$lang->search->methodOrder[5]  = 'buildForm';
$lang->search->methodOrder[10] = 'buildQuery';
$lang->search->methodOrder[15] = 'saveQuery';
$lang->search->methodOrder[20] = 'deleteQuery';
$lang->search->methodOrder[25] = 'select';
$lang->search->methodOrder[30] = 'index';
$lang->search->methodOrder[35] = 'buildIndex';

/* Admin. */
$lang->resource->admin = new stdclass();
$lang->resource->admin->index     = 'index';
$lang->resource->admin->safe      = 'safeIndex';
$lang->resource->admin->checkWeak = 'checkWeak';
$lang->resource->admin->sso       = 'ssoAction';
$lang->resource->admin->register  = 'register';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[10] = 'safeIndex';
$lang->admin->methodOrder[15] = 'checkWeak';
$lang->admin->methodOrder[20] = 'sso';
$lang->admin->methodOrder[25] = 'register';

if(isset($config->xuanxuan))
{
    $lang->resource->admin->xuanxuan = 'xuanxuan';
}

/* Extension. */
$lang->resource->extension = new stdclass();
$lang->resource->extension->browse     = 'browseAction';
$lang->resource->extension->obtain     = 'obtain';
$lang->resource->extension->structure  = 'structureAction';
$lang->resource->extension->install    = 'install';
$lang->resource->extension->uninstall  = 'uninstallAction';
$lang->resource->extension->activate   = 'activateAction';
$lang->resource->extension->deactivate = 'deactivateAction';
$lang->resource->extension->upload     = 'upload';
$lang->resource->extension->erase      = 'eraseAction';
$lang->resource->extension->upgrade    = 'upgrade';

$lang->extension->methodOrder[5]  = 'browse';
$lang->extension->methodOrder[10] = 'obtain';
$lang->extension->methodOrder[15] = 'structure';
$lang->extension->methodOrder[20] = 'install';
$lang->extension->methodOrder[25] = 'uninstall';
$lang->extension->methodOrder[30] = 'activate';
$lang->extension->methodOrder[35] = 'deactivate';
$lang->extension->methodOrder[40] = 'upload';
$lang->extension->methodOrder[45] = 'erase';
$lang->extension->methodOrder[50] = 'upgrade';

/* Webhook. */
$lang->resource->webhook = new stdclass();
$lang->resource->webhook->browse     = 'list';
$lang->resource->webhook->create     = 'create';
$lang->resource->webhook->edit       = 'edit';
$lang->resource->webhook->delete     = 'delete';
$lang->resource->webhook->log        = 'logAction';
$lang->resource->webhook->bind       = 'bind';
$lang->resource->webhook->chooseDept = 'chooseDept';

$lang->webhook->methodOrder[5]  = 'browse';
$lang->webhook->methodOrder[10] = 'create';
$lang->webhook->methodOrder[15] = 'edit';
$lang->webhook->methodOrder[20] = 'delete';
$lang->webhook->methodOrder[25] = 'log';
$lang->webhook->methodOrder[30] = 'bind';
$lang->webhook->methodOrder[35] = 'chooseDept';

/* Others. */
$lang->resource->file = new stdclass();
$lang->resource->file->download     = 'download';
$lang->resource->file->edit         = 'edit';
$lang->resource->file->delete       = 'delete';
$lang->resource->file->uploadImages = 'uploadImages';
$lang->resource->file->setPublic     = 'setPublic';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'edit';
$lang->file->methodOrder[15] = 'delete';
$lang->file->methodOrder[20] = 'uploadImages';
$lang->file->methodOrder[25] = 'setPublic';

$lang->resource->misc = new stdclass();
$lang->resource->misc->ping = 'ping';

$lang->misc->methodOrder[5] = 'ping';

$lang->resource->message = new stdclass();
$lang->resource->message->index   = 'index';
$lang->resource->message->browser = 'browser';
$lang->resource->message->setting = 'setting';

$lang->message->methodOrder[5]  = 'index';
$lang->message->methodOrder[10] = 'browser';
$lang->message->methodOrder[15] = 'setting';

$lang->resource->action = new stdclass();
$lang->resource->action->trash    = 'trash';
$lang->resource->action->undelete = 'undeleteAction';
$lang->resource->action->hideOne  = 'hideOneAction';
$lang->resource->action->hideAll  = 'hideAll';
$lang->resource->action->comment  = 'comment';
$lang->resource->action->editComment = 'editComment';

$lang->action->methodOrder[5]  = 'trash';
$lang->action->methodOrder[10] = 'undelete';
$lang->action->methodOrder[15] = 'hideOne';
$lang->action->methodOrder[20] = 'hideAll';
$lang->action->methodOrder[25] = 'comment';
$lang->action->methodOrder[30] = 'editComment';

$lang->resource->backup = new stdclass();
$lang->resource->backup->index       = 'index';
$lang->resource->backup->backup      = 'backup';
$lang->resource->backup->restore     = 'restore';
$lang->resource->backup->change      = 'change';
$lang->resource->backup->delete      = 'delete';
$lang->resource->backup->setting     = 'settingAction';
$lang->resource->backup->rmPHPHeader = 'rmPHPHeader';

$lang->backup->methodOrder[5]  = 'index';
$lang->backup->methodOrder[10] = 'backup';
$lang->backup->methodOrder[15] = 'restore';
$lang->backup->methodOrder[20] = 'delete';
$lang->backup->methodOrder[25] = 'setting';
$lang->backup->methodOrder[30] = 'rmPHPHeader';

$lang->resource->cron = new stdclass();
$lang->resource->cron->index   = 'index';
$lang->resource->cron->turnon  = 'turnon';
$lang->resource->cron->create  = 'createAction';
$lang->resource->cron->edit    = 'edit';
$lang->resource->cron->toggle  = 'toggle';
$lang->resource->cron->delete  = 'delete';
$lang->resource->cron->openProcess = 'restart';

$lang->cron->methodOrder[5]  = 'index';
$lang->cron->methodOrder[10] = 'turnon';
$lang->cron->methodOrder[15] = 'create';
$lang->cron->methodOrder[20] = 'edit';
$lang->cron->methodOrder[25] = 'toggle';
$lang->cron->methodOrder[30] = 'delete';
$lang->cron->methodOrder[35] = 'openProcess';

$lang->resource->contact            = new stdclass();
$lang->resource->programstakeholder = new stdclass();
$lang->resource->researchplan       = new stdclass();
$lang->resource->workestimation     = new stdclass();
$lang->resource->gapanalysis        = new stdclass();
$lang->resource->executionview      = new stdclass();
$lang->resource->managespace        = new stdclass();
$lang->resource->systemteam         = new stdclass();
$lang->resource->systemschedule     = new stdclass();
$lang->resource->systemeffort       = new stdclass();
$lang->resource->systemdynamic      = new stdclass();
$lang->resource->systemcompany      = new stdclass();
$lang->resource->pipeline           = new stdclass();
$lang->resource->devopssetting      = new stdclass();
$lang->resource->featureswitch      = new stdclass();
$lang->resource->importdata         = new stdclass();
$lang->resource->systemsetting      = new stdclass();
$lang->resource->staffmanage        = new stdclass();
$lang->resource->modelconfig        = new stdclass();
$lang->resource->featureconfig      = new stdclass();
$lang->resource->doctemplate        = new stdclass();
$lang->resource->notifysetting      = new stdclass();
$lang->resource->bidesign           = new stdclass();
$lang->resource->personalsettings   = new stdclass();
$lang->resource->projectsettings    = new stdclass();
$lang->resource->dataaccess         = new stdclass();
$lang->resource->executiongantt     = new stdclass();
$lang->resource->executionkanban    = new stdclass();
$lang->resource->executionburn      = new stdclass();
$lang->resource->executioncfd       = new stdclass();
$lang->resource->executionstory     = new stdclass();
$lang->resource->executionqa        = new stdclass();
$lang->resource->executionsettings  = new stdclass();
$lang->resource->generalcomment     = new stdclass();
$lang->resource->generalping        = new stdclass();
$lang->resource->generaltemplate    = new stdclass();
$lang->resource->generaleffort      = new stdclass();
$lang->resource->productsettings    = new stdclass();
$lang->resource->projectreview      = new stdclass();
$lang->resource->projecttrack       = new stdclass();
$lang->resource->projectqa          = new stdclass();

if($config->edition != 'open')
{
    /* My module. */
    $lang->resource->my->effort = 'effort';  // pro effort

    /* Pro effort */
    $lang->resource->effort = new stdclass();
    $lang->resource->effort->batchCreate     = 'batchCreate';
    $lang->resource->effort->createForObject = 'createForObject';
    $lang->resource->effort->edit            = 'edit';
    $lang->resource->effort->batchEdit       = 'batchEdit';
    $lang->resource->effort->view            = 'view';
    $lang->resource->effort->delete          = 'delete';
    $lang->resource->effort->export          = 'export';
    $lang->resource->effort->calendar        = 'calendar';  // pro calendar

    /* Todo. */
    $lang->resource->todo->calendar  = 'calendar';  // pro calendar

    /* Feedback. */
    $lang->resource->feedback = new stdclass();
    $lang->resource->feedback->create         = 'create';
    $lang->resource->feedback->batchCreate    = 'batchCreate';
    $lang->resource->feedback->edit           = 'edit';
    $lang->resource->feedback->browse         = 'browse';
    $lang->resource->feedback->view           = 'view';
    $lang->resource->feedback->comment        = 'comment';
    $lang->resource->feedback->ask            = 'ask';
    $lang->resource->feedback->delete         = 'delete';
    $lang->resource->feedback->close          = 'closeAction';
    $lang->resource->feedback->activate       = 'activate';
    $lang->resource->feedback->batchEdit      = 'batchEdit';
    $lang->resource->feedback->batchClose     = 'batchClose';
    $lang->resource->feedback->batchAssignTo  = 'batchAssignTo';
    $lang->resource->feedback->export         = 'exportAction';
    $lang->resource->feedback->import         = 'import';
    $lang->resource->feedback->exportTemplate = 'exportTemplate';
    $lang->resource->feedback->assignTo       = 'assignAction';
    $lang->resource->feedback->products       = 'products';
    $lang->resource->feedback->manageProduct  = 'manageProduct';
    $lang->resource->feedback->productSetting = 'productSetting';

    if($config->visions == ',lite,') unset($lang->resource->feedback);

    /* Faq. */
    $lang->resource->faq = new stdclass();
    $lang->resource->faq->browse = 'browse';

    /* Ticket */
    $lang->resource->ticket = new stdclass();
    $lang->resource->ticket->create         = 'create';
    $lang->resource->ticket->edit           = 'edit';
    $lang->resource->ticket->view           = 'view';
    $lang->resource->ticket->browse         = 'browse';
    $lang->resource->ticket->assignTo       = 'assign';
    $lang->resource->ticket->close          = 'close';
    $lang->resource->ticket->activate       = 'activate';
    $lang->resource->ticket->delete         = 'delete';
    $lang->resource->ticket->exportTemplate = 'exportTemplate';
    $lang->resource->ticket->import         = 'import';
    $lang->resource->ticket->export         = 'exportAction';
    $lang->resource->ticket->batchCreate    = 'batchCreate';
    $lang->resource->ticket->batchEdit      = 'batchEdit';
    $lang->resource->ticket->batchActivate  = 'batchActivate';
    $lang->resource->ticket->batchFinish    = 'batchFinish';
    $lang->resource->ticket->batchAssignTo  = 'batchAssignTo';

    /* Attend */
    $lang->resource->attend = new stdclass();
    $lang->resource->attend->department       = 'department';
    $lang->resource->attend->company          = 'company';
    $lang->resource->attend->browseReview     = 'browseReview';
    $lang->resource->attend->review           = 'review';
    $lang->resource->attend->export           = 'exportAction';
    $lang->resource->attend->stat             = 'reportAction';
    $lang->resource->attend->saveStat         = 'saveStatAction';
    $lang->resource->attend->exportStat       = 'exportStat';
    $lang->resource->attend->detail           = 'detailAction';
    $lang->resource->attend->exportDetail     = 'exportDetail';
    $lang->resource->attend->settings         = 'settings';
    $lang->resource->attend->personalSettings = 'personalSettings';
    $lang->resource->attend->setManager       = 'setManager';

    $lang->resource->attend->personal         = 'personal';
    $lang->resource->attend->edit             = 'editAction';

    $lang->attend->methodOrder[5]  = 'department';
    $lang->attend->methodOrder[10] = 'company';
    $lang->attend->methodOrder[15] = 'browseReview';
    $lang->attend->methodOrder[20] = 'review';
    $lang->attend->methodOrder[25] = 'export';
    $lang->attend->methodOrder[30] = 'stat';
    $lang->attend->methodOrder[35] = 'saveStat';
    $lang->attend->methodOrder[40] = 'exportStat';
    $lang->attend->methodOrder[45] = 'detail';
    $lang->attend->methodOrder[60] = 'exportDetail';
    $lang->attend->methodOrder[65] = 'settings';
    $lang->attend->methodOrder[70] = 'personalSettings';
    $lang->attend->methodOrder[75] = 'setManager';

    $lang->attend->methodOrder[80] = 'personal';
    $lang->attend->methodOrder[85] = 'edit';

    /* Holiday */
    //$lang->resource->holiday = new stdclass();
    //$lang->resource->holiday->create = 'createAction';
    //$lang->resource->holiday->edit   = 'editAction';
    //$lang->resource->holiday->delete = 'deleteAction';

    //$lang->resource->holiday->browse = 'browse';

    //$lang->holiday->methodOrder[0]  = 'browse';
    //$lang->holiday->methodOrder[5]  = 'create';
    //$lang->holiday->methodOrder[10] = 'edit';
    //$lang->holiday->methodOrder[15] = 'delete';

    /* Leave */
    $lang->resource->leave = new stdclass();
    $lang->resource->leave->browseReview   = 'browseReview';
    $lang->resource->leave->company        = 'companyAction';
    $lang->resource->leave->review         = 'reviewAction';
    $lang->resource->leave->export         = 'exportAction';
    $lang->resource->leave->setReviewer    = 'setReviewerAction';
    $lang->resource->leave->personalAnnual = 'personalAnnual';

    $lang->resource->leave->personal     = 'personal';
    $lang->resource->leave->create       = 'createAction';
    $lang->resource->leave->edit         = 'editAction';
    $lang->resource->leave->delete       = 'deleteAction';
    $lang->resource->leave->view         = 'viewAction';
    $lang->resource->leave->switchstatus = 'switchstatus';
    $lang->resource->leave->back         = 'backAction';

    $lang->leave->methodOrder[0]  = 'browseReview';
    $lang->leave->methodOrder[5]  = 'company';
    $lang->leave->methodOrder[10] = 'review';
    $lang->leave->methodOrder[15] = 'export';
    $lang->leave->methodOrder[20] = 'setReviewer';
    $lang->leave->methodOrder[25] = 'personalAnnual';

    $lang->leave->methodOrder[30] = 'personal';
    $lang->leave->methodOrder[35] = 'create';
    $lang->leave->methodOrder[40] = 'edit';
    $lang->leave->methodOrder[45] = 'delete';
    $lang->leave->methodOrder[50] = 'view';
    $lang->leave->methodOrder[55] = 'switchstatus';
    $lang->leave->methodOrder[60] = 'back';

    /* Makeup */
    $lang->resource->makeup = new stdclass();
    $lang->resource->makeup->browseReview = 'browseReview';
    $lang->resource->makeup->company      = 'companyAction';
    $lang->resource->makeup->review       = 'reviewAction';
    $lang->resource->makeup->export       = 'exportAction';
    $lang->resource->makeup->setReviewer  = 'setReviewerAction';

    $lang->resource->makeup->personal     = 'personal';
    $lang->resource->makeup->create       = 'createAction';
    $lang->resource->makeup->edit         = 'editAction';
    $lang->resource->makeup->view         = 'viewAction';
    $lang->resource->makeup->delete       = 'deleteAction';
    $lang->resource->makeup->switchstatus = 'switchstatus';

    $lang->makeup->methodOrder[0]  = 'browseReview';
    $lang->makeup->methodOrder[5]  = 'company';
    $lang->makeup->methodOrder[10] = 'review';
    $lang->makeup->methodOrder[15] = 'export';
    $lang->makeup->methodOrder[20] = 'setReviewer';

    $lang->makeup->methodOrder[25]  = 'personal';
    $lang->makeup->methodOrder[30]  = 'create';
    $lang->makeup->methodOrder[35] = 'edit';
    $lang->makeup->methodOrder[40] = 'view';
    $lang->makeup->methodOrder[45] = 'delete';
    $lang->makeup->methodOrder[50] = 'switchstatus';

    /* Overtime */
    $lang->resource->overtime = new stdclass();
    $lang->resource->overtime->browseReview = 'browseReview';
    $lang->resource->overtime->company      = 'companyAction';
    $lang->resource->overtime->review       = 'reviewAction';
    $lang->resource->overtime->export       = 'exportAction';
    $lang->resource->overtime->setReviewer  = 'setReviewerAction';

    $lang->resource->overtime->personal     = 'personal';
    $lang->resource->overtime->create       = 'createAction';
    $lang->resource->overtime->edit         = 'editAction';
    $lang->resource->overtime->view         = 'viewAction';
    $lang->resource->overtime->delete       = 'deleteAction';
    $lang->resource->overtime->switchstatus = 'switchstatus';

    $lang->overtime->methodOrder[0]  = 'browseReview';
    $lang->overtime->methodOrder[5]  = 'company';
    $lang->overtime->methodOrder[10] = 'review';
    $lang->overtime->methodOrder[15] = 'export';
    $lang->overtime->methodOrder[20] = 'setReviewer';

    $lang->overtime->methodOrder[25]  = 'personal';
    $lang->overtime->methodOrder[30]  = 'create';
    $lang->overtime->methodOrder[35] = 'edit';
    $lang->overtime->methodOrder[40] = 'view';
    $lang->overtime->methodOrder[45] = 'delete';
    $lang->overtime->methodOrder[50] = 'switchstatus';

    /* Lieu */
    $lang->resource->lieu = new stdclass();
    $lang->resource->lieu->company      = 'companyAction';
    $lang->resource->lieu->browseReview = 'browseReviewAction';
    $lang->resource->lieu->review       = 'reviewAction';
    $lang->resource->lieu->setReviewer  = 'setReviewerAction';

    $lang->resource->lieu->personal     = 'personal';
    $lang->resource->lieu->create       = 'createAction';
    $lang->resource->lieu->edit         = 'editAction';
    $lang->resource->lieu->delete       = 'deleteAction';
    $lang->resource->lieu->view         = 'viewAction';
    $lang->resource->lieu->switchstatus = 'switchstatus';

    $lang->lieu->methodOrder[0]  = 'company';
    $lang->lieu->methodOrder[5]  = 'browseReview';
    $lang->lieu->methodOrder[10] = 'review';
    $lang->lieu->methodOrder[15] = 'setReviewer';

    $lang->lieu->methodOrder[20]  = 'personal';
    $lang->lieu->methodOrder[25]  = 'create';
    $lang->lieu->methodOrder[30] = 'edit';
    $lang->lieu->methodOrder[35] = 'delete';
    $lang->lieu->methodOrder[40] = 'view';
    $lang->lieu->methodOrder[45] = 'switchstatus';

    /* Ops */
    $lang->resource->tree->editHost = 'editHost';
    $lang->resource->tree->browsehost = 'groupMaintenance';

    $lang->tree->methodOrder[35] = 'editHost';
    $lang->host->methodOrder[40] = 'groupMaintenance';

    $lang->resource->doc->diff             = 'diffAction';
    $lang->resource->doc->mine2export      = 'mine2export';
    $lang->resource->doc->product2export   = 'product2export';
    $lang->resource->doc->project2export   = 'project2export';
    $lang->resource->doc->custom2export    = 'custom2export';
    $lang->resource->doc->execution2export = 'execution2export';

    $lang->resource->my->review = 'review';

    /* workflow */
    $lang->resource->workflow = new stdclass();
    $lang->resource->workflow->browseFlow = 'browseFlow';
    $lang->resource->workflow->browseDB   = 'browseDB';
    $lang->resource->workflow->create     = 'create';
    $lang->resource->workflow->copy       = 'copy';
    $lang->resource->workflow->edit       = 'edit';
    $lang->resource->workflow->backup     = 'backup';
    $lang->resource->workflow->upgrade    = 'upgradeAction';
    $lang->resource->workflow->view       = 'view';
    $lang->resource->workflow->delete     = 'delete';
    $lang->resource->workflow->flowchart  = 'flowchart';
    $lang->resource->workflow->ui         = 'ui';
    $lang->resource->workflow->release    = 'release';
    $lang->resource->workflow->deactivate = 'deactivate';
    $lang->resource->workflow->activate   = 'activate';
    $lang->resource->workflow->setJS      = 'setJS';
    $lang->resource->workflow->setCSS     = 'setCSS';

    $lang->workflow->methodOrder[5]  = 'browseFlow';
    $lang->workflow->methodOrder[10] = 'browseDB';
    $lang->workflow->methodOrder[15] = 'create';
    $lang->workflow->methodOrder[20] = 'copy';
    $lang->workflow->methodOrder[25] = 'edit';
    $lang->workflow->methodOrder[30] = 'backup';
    $lang->workflow->methodOrder[35] = 'upgrade';
    $lang->workflow->methodOrder[40] = 'view';
    $lang->workflow->methodOrder[45] = 'delete';
    $lang->workflow->methodOrder[50] = 'flowchart';
    $lang->workflow->methodOrder[55] = 'ui';
    $lang->workflow->methodOrder[60] = 'release';
    $lang->workflow->methodOrder[65] = 'deactivate';
    $lang->workflow->methodOrder[70] = 'activate';
    $lang->workflow->methodOrder[75] = 'setJS';
    $lang->workflow->methodOrder[80] = 'setCSS';

    /* workflowfield */
    $lang->resource->workflowfield = new stdclass();
    $lang->resource->workflowfield->browse         = 'browse';
    $lang->resource->workflowfield->create         = 'create';
    $lang->resource->workflowfield->edit           = 'edit';
    $lang->resource->workflowfield->delete         = 'delete';
    $lang->resource->workflowfield->import         = 'import';
    $lang->resource->workflowfield->showImport     = 'showImport';
    $lang->resource->workflowfield->sort           = 'sort';
    $lang->resource->workflowfield->exportTemplate = 'exportTemplate';
    $lang->resource->workflowfield->setValue       = 'setValue';
    $lang->resource->workflowfield->setExport      = 'setExport';
    $lang->resource->workflowfield->setSearch      = 'setSearch';

    $lang->workflowfield->methodOrder[5]  = 'browse';
    $lang->workflowfield->methodOrder[10] = 'create';
    $lang->workflowfield->methodOrder[15] = 'edit';
    $lang->workflowfield->methodOrder[20] = 'delete';
    $lang->workflowfield->methodOrder[25] = 'sort';
    $lang->workflowfield->methodOrder[30] = 'import';
    $lang->workflowfield->methodOrder[35] = 'showImport';
    $lang->workflowfield->methodOrder[40] = 'exportTemplate';
    $lang->workflowfield->methodOrder[45] = 'setValue';
    $lang->workflowfield->methodOrder[50] = 'setExport';
    $lang->workflowfield->methodOrder[55] = 'setSearch';

    /* workflowaction */
    $lang->resource->workflowaction = new stdclass();
    $lang->resource->workflowaction->browse          = 'browse';
    $lang->resource->workflowaction->create          = 'create';
    $lang->resource->workflowaction->edit            = 'edit';
    $lang->resource->workflowaction->view            = 'view';
    $lang->resource->workflowaction->delete          = 'delete';
    $lang->resource->workflowaction->sort            = 'sort';
    $lang->resource->workflowaction->setVerification = 'setVerification';
    $lang->resource->workflowaction->setNotice       = 'setNotice';
    $lang->resource->workflowaction->setJS           = 'setJS';
    $lang->resource->workflowaction->setCSS          = 'setCSS';

    $lang->workflowaction->methodOrder[5]  = 'browse';
    $lang->workflowaction->methodOrder[10] = 'create';
    $lang->workflowaction->methodOrder[15] = 'edit';
    $lang->workflowaction->methodOrder[20] = 'view';
    $lang->workflowaction->methodOrder[25] = 'delete';
    $lang->workflowaction->methodOrder[30] = 'sort';
    $lang->workflowaction->methodOrder[35] = 'setVerification';
    $lang->workflowaction->methodOrder[40] = 'setNotice';
    $lang->workflowaction->methodOrder[45] = 'setJS';
    $lang->workflowaction->methodOrder[50] = 'setCSS';

    /* workflowcondition */
    $lang->resource->workflowcondition = new stdclass();
    $lang->resource->workflowcondition->browse = 'browse';
    $lang->resource->workflowcondition->create = 'create';
    $lang->resource->workflowcondition->edit   = 'edit';
    $lang->resource->workflowcondition->delete = 'delete';

    $lang->workflowcondition->methodOrder[5]  = 'browse';
    $lang->workflowcondition->methodOrder[10] = 'create';
    $lang->workflowcondition->methodOrder[15] = 'edit';
    $lang->workflowcondition->methodOrder[20] = 'delete';

    /* workflowlayout */
    $lang->resource->workflowlayout = new stdclass();
    $lang->resource->workflowlayout->admin    = 'admin';
    $lang->resource->workflowlayout->block    = 'block';
    $lang->resource->workflowlayout->addUI    = 'addUI';
    $lang->resource->workflowlayout->editUI   = 'editUI';
    $lang->resource->workflowlayout->deleteUI = 'deleteUI';


    $lang->workflowlayout->methodOrder[5]  = 'admin';
    $lang->workflowlayout->methodOrder[10] = 'block';
    $lang->workflowlayout->methodOrder[15] = 'addUI';
    $lang->workflowlayout->methodOrder[20] = 'editUI';
    $lang->workflowlayout->methodOrder[25] = 'deleteUI';

    /* workflowlinkage */
    $lang->resource->workflowlinkage = new stdclass();
    $lang->resource->workflowlinkage->browse = 'browse';
    $lang->resource->workflowlinkage->create = 'create';
    $lang->resource->workflowlinkage->edit   = 'edit';
    $lang->resource->workflowlinkage->delete = 'delete';

    $lang->workflowlinkage->methodOrder[5]  = 'browse';
    $lang->workflowlinkage->methodOrder[10] = 'create';
    $lang->workflowlinkage->methodOrder[15] = 'edit';
    $lang->workflowlinkage->methodOrder[20] = 'delete';

    /* workflowhook */
    $lang->resource->workflowhook = new stdclass();
    $lang->resource->workflowhook->browse = 'browse';
    $lang->resource->workflowhook->create = 'create';
    $lang->resource->workflowhook->edit   = 'edit';
    $lang->resource->workflowhook->delete = 'delete';

    $lang->workflowhook->methodOrder[5]  = 'browse';
    $lang->workflowhook->methodOrder[10] = 'create';
    $lang->workflowhook->methodOrder[15] = 'edit';
    $lang->workflowhook->methodOrder[20] = 'delete';

    /* workflowlabel */
    $lang->resource->workflowlabel = new stdclass();
    $lang->resource->workflowlabel->browse = 'browse';
    $lang->resource->workflowlabel->create = 'create';
    $lang->resource->workflowlabel->edit   = 'edit';
    $lang->resource->workflowlabel->delete = 'delete';
    $lang->resource->workflowlabel->sort   = 'sort';

    $lang->workflowlabel->methodOrder[5]  = 'browse';
    $lang->workflowlabel->methodOrder[10] = 'create';
    $lang->workflowlabel->methodOrder[15] = 'edit';
    $lang->workflowlabel->methodOrder[20] = 'delete';
    $lang->workflowlabel->methodOrder[25] = 'sort';

    /* workflowrelation */
    $lang->resource->workflowrelation = new stdclass();
    $lang->resource->workflowrelation->admin = 'admin';

    $lang->workflowrelation->methodOrder[5] = 'admin';

    /* workflowreport*/
    $lang->resource->workflowreport = new stdclass();
    $lang->resource->workflowreport->browse = 'brow';
    $lang->resource->workflowreport->create = 'create';
    $lang->resource->workflowreport->edit   = 'edit';
    $lang->resource->workflowreport->delete = 'delete';
    $lang->resource->workflowreport->sort   = 'sort';

    $lang->workflowreport->methodOrder[5]  = 'browse';
    $lang->workflowreport->methodOrder[10] = 'create';
    $lang->workflowreport->methodOrder[15] = 'edit';
    $lang->workflowreport->methodOrder[20] = 'delete';
    $lang->workflowreport->methodOrder[25] = 'sort';

    /* workflowdatasource */
    $lang->resource->workflowdatasource = new stdclass();
    $lang->resource->workflowdatasource->browse = 'browse';
    $lang->resource->workflowdatasource->create = 'create';
    $lang->resource->workflowdatasource->edit   = 'edit';
    $lang->resource->workflowdatasource->delete = 'delete';

    $lang->workflowdatasource->methodOrder[5]  = 'browse';
    $lang->workflowdatasource->methodOrder[10] = 'create';
    $lang->workflowdatasource->methodOrder[15] = 'edit';
    $lang->workflowdatasource->methodOrder[20] = 'delete';

    /* workflowrule */
    $lang->resource->workflowrule = new stdclass();
    $lang->resource->workflowrule->browse = 'browse';
    $lang->resource->workflowrule->create = 'create';
    $lang->resource->workflowrule->edit   = 'edit';
    $lang->resource->workflowrule->view   = 'view';
    $lang->resource->workflowrule->delete = 'delete';

    $lang->workflowrule->methodOrder[5]  = 'browse';
    $lang->workflowrule->methodOrder[10] = 'create';
    $lang->workflowrule->methodOrder[15] = 'edit';
    $lang->workflowrule->methodOrder[20] = 'view';
    $lang->workflowrule->methodOrder[25] = 'delete';

    $lang->resource->workflow->release    = 'releaseAction';
    $lang->resource->workflow->deactivate = 'deactivateAction';
    $lang->resource->workflow->activate   = 'activateAction';
    $lang->resource->workflow->setJS      = 'setJSAction';
    $lang->resource->workflow->setCSS     = 'setCSSAction';

    $lang->resource->workflowfield->browse = 'browseAction';

    $lang->resource->workflowaction->browse = 'browseAction';
    $lang->resource->workflowaction->setJS  = 'setJSAction';
    $lang->resource->workflowaction->setCSS = 'setCSSAction';
}
// include (dirname(__FILE__) . '/changelog.php');
