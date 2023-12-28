<?php
/**
 * The all avaliabe actions in ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        https://www.zentao.net
 */

/* Module order. */
$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';

$lang->moduleOrder[15]  = 'program';
$lang->moduleOrder[20]  = 'personnel';
$lang->moduleOrder[25]  = 'product';
$lang->moduleOrder[30]  = 'story';
$lang->moduleOrder[31]  = 'requirement';
$lang->moduleOrder[35]  = 'productplan';
$lang->moduleOrder[40]  = 'release';

$lang->moduleOrder[45]  = 'project';
$lang->moduleOrder[48]  = 'projectplan';
$lang->moduleOrder[50]  = 'projectstory';
$lang->moduleOrder[55]  = 'execution';
$lang->moduleOrder[56]  = 'kanban';
$lang->moduleOrder[57]  = 'programplan';
$lang->moduleOrder[60]  = 'task';
$lang->moduleOrder[65]  = 'build';
$lang->moduleOrder[66]  = 'design';

$lang->moduleOrder[70]  = 'qa';
$lang->moduleOrder[75]  = 'bug';
$lang->moduleOrder[80]  = 'testcase';
$lang->moduleOrder[85]  = 'testtask';
$lang->moduleOrder[90]  = 'testsuite';
$lang->moduleOrder[95]  = 'testreport';
$lang->moduleOrder[100] = 'caselib';
$lang->moduleOrder[105] = 'zahost';
$lang->moduleOrder[108] = 'zanode';

$lang->moduleOrder[110] = 'doc';
$lang->moduleOrder[111] = 'screen';
$lang->moduleOrder[112] = 'pivot';
$lang->moduleOrder[113] = 'chart';
$lang->moduleOrder[117] = 'metric';
$lang->moduleOrder[119] = 'report';

$lang->moduleOrder[120] = 'company';
$lang->moduleOrder[125] = 'dept';
$lang->moduleOrder[130] = 'group';
$lang->moduleOrder[135] = 'user';

$lang->moduleOrder[140] = 'admin';
$lang->moduleOrder[142] = 'stage';
$lang->moduleOrder[145] = 'extension';
$lang->moduleOrder[150] = 'custom';
$lang->moduleOrder[155] = 'action';

$lang->moduleOrder[160] = 'mail';
$lang->moduleOrder[165] = 'svn';
$lang->moduleOrder[170] = 'git';
$lang->moduleOrder[175] = 'search';
$lang->moduleOrder[180] = 'tree';
$lang->moduleOrder[185] = 'api';
$lang->moduleOrder[190] = 'file';
$lang->moduleOrder[195] = 'misc';
$lang->moduleOrder[200] = 'backup';
$lang->moduleOrder[205] = 'cron';
$lang->moduleOrder[210] = 'dev';
$lang->moduleOrder[215] = 'editor';
$lang->moduleOrder[220] = 'message';
$lang->moduleOrder[225] = 'gitlab';
$lang->moduleOrder[230] = 'mr';
$lang->moduleOrder[235] = 'app';
$lang->moduleOrder[240] = 'gogs';
$lang->moduleOrder[245] = 'gitea';
$lang->moduleOrder[250] = 'holiday';

$lang->resource = new stdclass();

/* My module. */
$lang->resource->my = new stdclass();
$lang->resource->my->index           = 'indexAction';
$lang->resource->my->todo            = 'todoAction';
$lang->resource->my->calendar        = 'calendarAction';
$lang->resource->my->work            = 'workAction';
$lang->resource->my->audit           = 'audit';
$lang->resource->my->contribute      = 'contributeAction';
$lang->resource->my->project         = 'project';
$lang->resource->my->uploadAvatar    = 'uploadAvatar';
$lang->resource->my->dynamic         = 'dynamicAction';
$lang->resource->my->editProfile     = 'editProfile';
$lang->resource->my->manageContacts  = 'manageContacts';
$lang->resource->my->deleteContacts  = 'deleteContacts';
$lang->resource->my->score           = 'score';
$lang->resource->my->team            = 'team';
$lang->resource->my->execution       = 'execution';

$lang->my->methodOrder[1]  = 'index';
$lang->my->methodOrder[5]  = 'todo';
$lang->my->methodOrder[10] = 'work';
$lang->my->methodOrder[15] = 'contribute';
$lang->my->methodOrder[20] = 'project';
$lang->my->methodOrder[30] = 'uploadAvatar';
$lang->my->methodOrder[35] = 'preference';
$lang->my->methodOrder[40] = 'dynamic';
$lang->my->methodOrder[45] = 'editProfile';
$lang->my->methodOrder[55] = 'manageContacts';
$lang->my->methodOrder[60] = 'deleteContacts';
$lang->my->methodOrder[65] = 'score';
$lang->my->methodOrder[70] = 'unbind';
$lang->my->methodOrder[75] = 'team';
$lang->my->methodOrder[80] = 'execution';
$lang->my->methodOrder[85] = 'doc';
$lang->my->methodOrder[90] = 'audit';

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
$lang->resource->personnel->accessible   = 'accessible';
$lang->resource->personnel->invest       = 'invest';
$lang->resource->personnel->whitelist    = 'whitelist';
$lang->resource->personnel->addWhitelist = 'addWhitelist';

$lang->personnel->methodOrder[5]  = 'accessible';
$lang->personnel->methodOrder[10] = 'invest';
$lang->personnel->methodOrder[15] = 'whitelist';
$lang->personnel->methodOrder[20] = 'addWhitelist';

/* Project. */
$lang->resource->project = new stdclass();
$lang->resource->project->index             = 'index';
$lang->resource->project->browse            = 'browse';
$lang->resource->project->kanban            = 'kanban';
$lang->resource->project->create            = 'create';
$lang->resource->project->edit              = 'edit';
$lang->resource->project->batchEdit         = 'batchEdit';
$lang->resource->project->group             = 'group';
$lang->resource->project->createGroup       = 'createGroup';
$lang->resource->project->managePriv        = 'managePriv';
$lang->resource->project->manageMembers     = 'manageMembers';
$lang->resource->project->manageGroupMember = 'manageGroupMember';
$lang->resource->project->copyGroup         = 'copyGroup';
$lang->resource->project->editGroup         = 'editGroup';
$lang->resource->project->start             = 'start';
$lang->resource->project->suspend           = 'suspend';
$lang->resource->project->close             = 'close';
$lang->resource->project->activate          = 'activate';
$lang->resource->project->delete            = 'delete';
$lang->resource->project->view              = 'view';
$lang->resource->project->whitelist         = 'whitelist';
$lang->resource->project->addWhitelist      = 'addWhitelist';
$lang->resource->project->unbindWhitelist   = 'unbindWhitelist';
$lang->resource->project->manageProducts    = 'manageProducts';
$lang->resource->project->dynamic           = 'dynamic';
$lang->resource->project->bug               = 'bug';
$lang->resource->project->testcase          = 'testcase';
$lang->resource->project->testtask          = 'testtask';
$lang->resource->project->testreport        = 'testreport';
$lang->resource->project->execution         = 'execution';
$lang->resource->project->export            = 'export';
$lang->resource->project->updateOrder       = 'updateOrder';
$lang->resource->project->team              = 'teamAction';
$lang->resource->project->unlinkMember      = 'unlinkMemberAction';
$lang->resource->project->programTitle      = 'moduleOpenAction';

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
$lang->project->methodOrder[135] = 'bug';
$lang->project->methodOrder[140] = 'testcase';
$lang->project->methodOrder[145] = 'testtask';
$lang->project->methodOrder[150] = 'testreport';
$lang->project->methodOrder[155] = 'execution';
$lang->project->methodOrder[160] = 'export';
$lang->project->methodOrder[165] = 'updateOrder';
$lang->project->methodOrder[170] = 'team';
$lang->project->methodOrder[175] = 'unlinkMember';

$lang->resource->projectbuild = new stdclass();
$lang->resource->projectbuild->browse           = 'browse';
$lang->resource->projectbuild->view             = 'view';
$lang->resource->projectbuild->create           = 'create';
$lang->resource->projectbuild->edit             = 'edit';
$lang->resource->projectbuild->delete           = 'delete';
$lang->resource->projectbuild->linkStory        = 'linkStory';
$lang->resource->projectbuild->unlinkStory      = 'unlinkStory';
$lang->resource->projectbuild->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->projectbuild->linkBug          = 'linkBug';
$lang->resource->projectbuild->unlinkBug        = 'unlinkBug';
$lang->resource->projectbuild->batchUnlinkBug   = 'batchUnlinkBug';

$lang->projectbuild->methodOrder[5]  = 'browse';
$lang->projectbuild->methodOrder[10] = 'view';
$lang->projectbuild->methodOrder[15] = 'create';
$lang->projectbuild->methodOrder[20] = 'edit';
$lang->projectbuild->methodOrder[25] = 'delete';
$lang->projectbuild->methodOrder[30] = 'linkStory';
$lang->projectbuild->methodOrder[35] = 'unlinkStory';
$lang->projectbuild->methodOrder[40] = 'batchUnlinkStory';
$lang->projectbuild->methodOrder[45] = 'linkBug';
$lang->projectbuild->methodOrder[50] = 'unlinkBug';
$lang->projectbuild->methodOrder[55] = 'batchUnlinkBug';

$lang->resource->projectplan = new stdclass();
$lang->resource->projectplan->browse = 'browse';
$lang->resource->projectplan->create = 'create';
$lang->resource->projectplan->edit   = 'edit';
$lang->resource->projectplan->view   = 'view';

if(!isset($lang->projectplan)) $lang->projectplan = new stdclass();
$lang->projectplan->methodOrder[5]  = 'browse';
$lang->projectplan->methodOrder[10] = 'create';
$lang->projectplan->methodOrder[15] = 'edit';
$lang->projectplan->methodOrder[20] = 'view';

/* Project Story. */
$lang->resource->projectstory = new stdclass();
$lang->resource->projectstory->story             = 'story';
$lang->resource->projectstory->track             = 'trackAction';
$lang->resource->projectstory->view              = 'view';
$lang->resource->projectstory->linkStory         = 'linkStory';
$lang->resource->projectstory->unlinkStory       = 'unlinkStory';
$lang->resource->projectstory->batchUnlinkStory  = 'batchUnlinkStory';
$lang->resource->projectstory->importplanstories = 'importplanstories';

$lang->projectstory->methodOrder[5]  = 'story';
$lang->projectstory->methodOrder[10] = 'track';
$lang->projectstory->methodOrder[15] = 'view';
$lang->projectstory->methodOrder[20] = 'linkStory';
$lang->projectstory->methodOrder[25] = 'unlinkStory';
$lang->projectstory->methodOrder[23] = 'importplanstories';

/* Release. */
$lang->resource->projectrelease = new stdclass();
$lang->resource->projectrelease->browse           = 'browseAction';
$lang->resource->projectrelease->create           = 'create';
$lang->resource->projectrelease->edit             = 'edit';
$lang->resource->projectrelease->delete           = 'delete';
$lang->resource->projectrelease->view             = 'view';
$lang->resource->projectrelease->export           = 'export';
$lang->resource->projectrelease->linkStory        = 'linkStory';
$lang->resource->projectrelease->unlinkStory      = 'unlinkStory';
$lang->resource->projectrelease->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->projectrelease->linkBug          = 'linkBug';
$lang->resource->projectrelease->unlinkBug        = 'unlinkBug';
$lang->resource->projectrelease->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->projectrelease->changeStatus     = 'changeStatus';
$lang->resource->projectrelease->notify           = 'notify';

$lang->projectrelease->methodOrder[5]  = 'browse';
$lang->projectrelease->methodOrder[10] = 'create';
$lang->projectrelease->methodOrder[15] = 'edit';
$lang->projectrelease->methodOrder[20] = 'delete';
$lang->projectrelease->methodOrder[25] = 'view';
$lang->projectrelease->methodOrder[35] = 'export';
$lang->projectrelease->methodOrder[40] = 'linkStory';
$lang->projectrelease->methodOrder[45] = 'unlinkStory';
$lang->projectrelease->methodOrder[50] = 'batchUnlinkStory';
$lang->projectrelease->methodOrder[55] = 'linkBug';
$lang->projectrelease->methodOrder[60] = 'unlinkBug';
$lang->projectrelease->methodOrder[65] = 'batchUnlinkBug';
$lang->projectrelease->methodOrder[70] = 'changeStatus';
$lang->projectrelease->methodOrder[75] = 'notify';

/* Stakeholer. */
$lang->resource->stakeholder = new stdclass();
$lang->resource->stakeholder->browse       = 'browse';
$lang->resource->stakeholder->create       = 'create';
$lang->resource->stakeholder->batchCreate  = 'batchCreate';
$lang->resource->stakeholder->edit         = 'edit';
$lang->resource->stakeholder->delete       = 'delete';
$lang->resource->stakeholder->view         = 'viewAction';
$lang->resource->stakeholder->communicate  = 'communicate';
$lang->resource->stakeholder->expect       = 'expect';
$lang->resource->stakeholder->userIssue    = 'userIssue';

$lang->stakeholder->methodOrder[5]  = 'browse';
$lang->stakeholder->methodOrder[10] = 'create';
$lang->stakeholder->methodOrder[13] = 'batchCreate';
$lang->stakeholder->methodOrder[15] = 'edit';
$lang->stakeholder->methodOrder[25] = 'delete';
$lang->stakeholder->methodOrder[30] = 'view';
$lang->stakeholder->methodOrder[45] = 'communicate';
$lang->stakeholder->methodOrder[50] = 'expect';
$lang->stakeholder->methodOrder[80] = 'userIssue';

/* Design. */
$lang->resource->design = new stdclass();
$lang->resource->design->browse       = 'browse';
$lang->resource->design->view         = 'view';
$lang->resource->design->create       = 'create';
$lang->resource->design->batchCreate  = 'batchCreate';
$lang->resource->design->edit         = 'edit';
$lang->resource->design->assignTo     = 'assignTo';
$lang->resource->design->delete       = 'delete';
$lang->resource->design->linkCommit   = 'linkCommit';
$lang->resource->design->viewCommit   = 'viewCommit';
$lang->resource->design->unlinkCommit = 'unlinkCommit';
$lang->resource->design->revision     = 'revision';

$lang->design->methodOrder[5]  = 'browse';
$lang->design->methodOrder[10] = 'view';
$lang->design->methodOrder[15] = 'create';
$lang->design->methodOrder[20] = 'batchCreate';
$lang->design->methodOrder[25] = 'edit';
$lang->design->methodOrder[30] = 'assignTo';
$lang->design->methodOrder[35] = 'delete';
$lang->design->methodOrder[40] = 'linkCommit';
$lang->design->methodOrder[45] = 'viewCommit';
$lang->design->methodOrder[50] = 'unlinkCommit';
$lang->design->methodOrder[55] = 'revision';

/* Program plan. */
$lang->resource->programplan = new stdclass();
$lang->resource->programplan->create = 'create';
$lang->resource->programplan->edit   = 'edit';

$lang->programplan->methodOrder[0] = 'create';
$lang->programplan->methodOrder[5] = 'edit';

/* Stage. */
$lang->resource->stage = new stdclass();
$lang->resource->stage->browse      = 'browse';
$lang->resource->stage->create      = 'create';
$lang->resource->stage->batchCreate = 'batchCreate';
$lang->resource->stage->edit        = 'edit';
$lang->resource->stage->setType     = 'setType';
$lang->resource->stage->delete      = 'delete';
$lang->resource->stage->plusBrowse  = 'plusBrowse';

$lang->stage->methodOrder[5]  = 'browse';
$lang->stage->methodOrder[10] = 'create';
$lang->stage->methodOrder[15] = 'batchCreate';
$lang->stage->methodOrder[20] = 'edit';
$lang->stage->methodOrder[25] = 'setType';
$lang->stage->methodOrder[30] = 'delete';
$lang->stage->methodOrder[35] = 'plusBrowse';

/* Program. */
$lang->resource->program = new stdclass();
$lang->resource->program->browse                  = 'projectView';
$lang->resource->program->productView             = 'productView';
$lang->resource->program->kanban                  = 'kanbanAction';
$lang->resource->program->product                 = 'product';
$lang->resource->program->create                  = 'create';
$lang->resource->program->edit                    = 'edit';
$lang->resource->program->start                   = 'start';
$lang->resource->program->suspend                 = 'suspend';
$lang->resource->program->activate                = 'activate';
$lang->resource->program->close                   = 'close';
$lang->resource->program->delete                  = 'delete';
$lang->resource->program->project                 = 'project';
$lang->resource->program->view                    = 'view';
$lang->resource->program->stakeholder             = 'stakeholder';
$lang->resource->program->createStakeholder       = 'createStakeholder';
$lang->resource->program->unlinkStakeholder       = 'unlinkStakeholder';
$lang->resource->program->batchUnlinkStakeholders = 'batchUnlinkStakeholders';
$lang->resource->program->unbindWhitelist         = 'unbindWhitelist';
$lang->resource->program->updateOrder             = 'updateOrder';

$lang->program->methodOrder[5]  = 'browse';
$lang->program->methodOrder[10] = 'kanban';
$lang->program->methodOrder[15] = 'view';
$lang->program->methodOrder[20] = 'product';
$lang->program->methodOrder[25] = 'create';
$lang->program->methodOrder[30] = 'edit';
$lang->program->methodOrder[35] = 'view';
$lang->program->methodOrder[40] = 'start';
$lang->program->methodOrder[45] = 'suspend';
$lang->program->methodOrder[50] = 'activate';
$lang->program->methodOrder[55] = 'close';
$lang->program->methodOrder[60] = 'delete';
$lang->program->methodOrder[65] = 'project';
$lang->program->methodOrder[70] = 'stakeholder';
$lang->program->methodOrder[75] = 'createStakeholder';
$lang->program->methodOrder[80] = 'unlinkStakeholder';
$lang->program->methodOrder[85] = 'batchUnlinkStakeholders';
$lang->program->methodOrder[90] = 'unbindWhitelist';
$lang->program->methodOrder[95] = 'updateOrder';

/* Product. */
$lang->resource->product = new stdclass();
$lang->resource->product->index           = 'indexAction';
$lang->resource->product->browse          = 'browse';
$lang->resource->product->requirement     = 'requirement';
$lang->resource->product->create          = 'create';
$lang->resource->product->view            = 'view';
$lang->resource->product->edit            = 'edit';
$lang->resource->product->batchEdit       = 'batchEdit';
$lang->resource->product->delete          = 'delete';
$lang->resource->product->roadmap         = 'roadmap';
$lang->resource->product->track           = 'track';
$lang->resource->product->dynamic         = 'dynamic';
$lang->resource->product->project         = 'project';
$lang->resource->product->dashboard       = 'dashboard';
$lang->resource->product->close           = 'closeAction';
$lang->resource->product->sort            = 'orderAction';
$lang->resource->product->activate        = 'activateAction';
$lang->resource->product->updateOrder     = 'orderAction';
$lang->resource->product->all             = 'list';
$lang->resource->product->kanban          = 'kanban';
$lang->resource->product->manageLine      = 'manageLine';
$lang->resource->product->export          = 'exportAction';
$lang->resource->product->whitelist       = 'whitelist';
$lang->resource->product->addWhitelist    = 'addWhitelist';
$lang->resource->product->unbindWhitelist = 'unbindWhitelist';

$lang->product->methodOrder[0]   = 'index';
$lang->product->methodOrder[5]   = 'browse';
$lang->product->methodOrder[6]   = 'requirement';
$lang->product->methodOrder[10]  = 'create';
$lang->product->methodOrder[15]  = 'view';
$lang->product->methodOrder[20]  = 'edit';
$lang->product->methodOrder[25]  = 'batchEdit';
$lang->product->methodOrder[35]  = 'delete';
$lang->product->methodOrder[40]  = 'roadmap';
$lang->product->methodOrder[45]  = 'track';
$lang->product->methodOrder[50]  = 'dynamic';
$lang->product->methodOrder[55]  = 'project';
$lang->product->methodOrder[60]  = 'dashboard';
$lang->product->methodOrder[65]  = 'close';
$lang->product->methodOrder[70]  = 'sort';
$lang->product->methodOrder[75]  = 'activate';
$lang->product->methodOrder[80]  = 'updateOrder';
$lang->product->methodOrder[85]  = 'all';
$lang->product->methodOrder[90]  = 'kanban';
$lang->product->methodOrder[95]  = 'manageLine';
$lang->product->methodOrder[100] = 'build';
$lang->product->methodOrder[105] = 'export';
$lang->product->methodOrder[110] = 'whitelist';
$lang->product->methodOrder[115] = 'addWhitelist';
$lang->product->methodOrder[120] = 'unbindWhitelist';

/* Branch. */
$lang->resource->branch = new stdclass();
$lang->resource->branch->manage      = 'manage';
$lang->resource->branch->create      = 'createAction';
$lang->resource->branch->edit        = 'editAction';
$lang->resource->branch->close       = 'closeAction';
$lang->resource->branch->activate    = 'activateAction';
$lang->resource->branch->sort        = 'sort';
$lang->resource->branch->batchEdit   = 'batchEdit';
$lang->resource->branch->mergeBranch = 'mergeBranchAction';

$lang->branch->methodOrder[0]  = 'manage';
$lang->branch->methodOrder[5]  = 'create';
$lang->branch->methodOrder[10] = 'edit';
$lang->branch->methodOrder[15] = 'close';
$lang->branch->methodOrder[20] = 'activate';
$lang->branch->methodOrder[25] = 'sort';
$lang->branch->methodOrder[30] = 'batchEdit';
$lang->branch->methodOrder[35] = 'mergeBranch';

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
$lang->resource->story->bugs               = 'bugs';
$lang->resource->story->cases              = 'cases';
$lang->resource->story->report             = 'reportAction';
$lang->resource->story->batchChangePlan    = 'batchChangePlan';
$lang->resource->story->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->story->batchChangeStage   = 'batchChangeStage';
$lang->resource->story->batchAssignTo      = 'batchAssignTo';
$lang->resource->story->batchChangeModule  = 'batchChangeModule';
$lang->resource->story->batchToTask        = 'batchToTask';
$lang->resource->story->processStoryChange = 'processStoryChange';
$lang->resource->story->linkStories        = 'linkStoriesAB';
$lang->resource->story->relieved           = 'relievedTwins';

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
$lang->story->methodOrder[105] = 'report';
$lang->story->methodOrder[110] = 'linkStory';
$lang->story->methodOrder[115] = 'batchChangeBranch';
$lang->story->methodOrder[120] = 'batchChangeModule';
$lang->story->methodOrder[125] = 'batchToTask';
$lang->story->methodOrder[130] = 'processStoryChange';
$lang->story->methodOrder[135] = 'linkStories';
$lang->story->methodOrder[140] = 'relieved';

/* Requirement. */
$lang->resource->requirement = new stdclass();
$lang->resource->requirement->create            = 'create';
$lang->resource->requirement->batchCreate       = 'batchCreate';
$lang->resource->requirement->edit              = 'editAction';
$lang->resource->requirement->linkStory         = 'linkStory';
$lang->resource->requirement->batchEdit         = 'batchEdit';
$lang->resource->requirement->export            = 'exportAction';
$lang->resource->requirement->delete            = 'deleteAction';
$lang->resource->requirement->view              = 'view';
$lang->resource->requirement->change            = 'changeAction';
$lang->resource->requirement->review            = 'reviewAction';
$lang->resource->requirement->submitReview      = 'submitReview';
$lang->resource->requirement->batchReview       = 'batchReview';
$lang->resource->requirement->recall            = 'recall';
$lang->resource->requirement->assignTo          = 'assignAction';
$lang->resource->requirement->close             = 'closeAction';
$lang->resource->requirement->batchClose        = 'batchClose';
$lang->resource->requirement->activate          = 'activateAction';
$lang->resource->requirement->report            = 'reportAction';
$lang->resource->requirement->batchChangeBranch = 'batchChangeBranch';
$lang->resource->requirement->batchAssignTo     = 'batchAssignTo';
$lang->resource->requirement->batchChangeModule = 'batchChangeModule';
$lang->resource->requirement->linkRequirements  = 'linkRequirementsAB';

$lang->requirement->methodOrder[5]   = 'create';
$lang->requirement->methodOrder[10]  = 'batchCreate';
$lang->requirement->methodOrder[15]  = 'edit';
$lang->requirement->methodOrder[20]  = 'export';
$lang->requirement->methodOrder[25]  = 'delete';
$lang->requirement->methodOrder[30]  = 'view';
$lang->requirement->methodOrder[35]  = 'change';
$lang->requirement->methodOrder[40]  = 'review';
$lang->requirement->methodOrder[44]  = 'submitReview';
$lang->requirement->methodOrder[45]  = 'batchReview';
$lang->requirement->methodOrder[50]  = 'recall';
$lang->requirement->methodOrder[55]  = 'close';
$lang->requirement->methodOrder[60]  = 'batchClose';
$lang->requirement->methodOrder[65]  = 'assignTo';
$lang->requirement->methodOrder[70]  = 'batchAssignTo';
$lang->requirement->methodOrder[75]  = 'activate';
$lang->requirement->methodOrder[80]  = 'report';
$lang->requirement->methodOrder[85]  = 'linkStory';
$lang->requirement->methodOrder[90]  = 'batchChangeBranch';
$lang->requirement->methodOrder[95]  = 'batchChangeModule';
$lang->requirement->methodOrder[100] = 'linkRequirements';

/* Product plan. */
$lang->resource->productplan = new stdclass();
$lang->resource->productplan->browse            = 'browse';
$lang->resource->productplan->create            = 'create';
$lang->resource->productplan->edit              = 'edit';
$lang->resource->productplan->delete            = 'delete';
$lang->resource->productplan->view              = 'view';
$lang->resource->productplan->linkStory         = 'linkStory';
$lang->resource->productplan->unlinkStory       = 'unlinkStory';
$lang->resource->productplan->batchUnlinkStory  = 'batchUnlinkStory';
$lang->resource->productplan->linkBug           = 'linkBug';
$lang->resource->productplan->unlinkBug         = 'unlinkBug';
$lang->resource->productplan->batchUnlinkBug    = 'batchUnlinkBug';
$lang->resource->productplan->batchEdit         = 'batchEditAction';
$lang->resource->productplan->start             = 'start';
$lang->resource->productplan->finish            = 'finish';
$lang->resource->productplan->close             = 'close';
$lang->resource->productplan->activate          = 'activate';
$lang->resource->productplan->batchChangeStatus = 'batchChangeStatus';

$lang->productplan->methodOrder[5]  = 'browse';
$lang->productplan->methodOrder[10] = 'create';
$lang->productplan->methodOrder[15] = 'edit';
$lang->productplan->methodOrder[20] = 'delete';
$lang->productplan->methodOrder[25] = 'view';
$lang->productplan->methodOrder[30] = 'linkStory';
$lang->productplan->methodOrder[35] = 'unlinkStory';
$lang->productplan->methodOrder[40] = 'batchUnlinkStory';
$lang->productplan->methodOrder[45] = 'linkBug';
$lang->productplan->methodOrder[50] = 'unlinkBug';
$lang->productplan->methodOrder[55] = 'batchUnlinkBug';
$lang->productplan->methodOrder[60] = 'batchEdit';
$lang->productplan->methodOrder[65] = 'start';
$lang->productplan->methodOrder[70] = 'finish';
$lang->productplan->methodOrder[75] = 'close';
$lang->productplan->methodOrder[80] = 'activate';
$lang->productplan->methodOrder[85] = 'batchChangeStatus';

/* Release. */
$lang->resource->release = new stdclass();
$lang->resource->release->browse           = 'browse';
$lang->resource->release->create           = 'create';
$lang->resource->release->edit             = 'edit';
$lang->resource->release->delete           = 'delete';
$lang->resource->release->view             = 'view';
$lang->resource->release->export           = 'export';
$lang->resource->release->linkStory        = 'linkStory';
$lang->resource->release->unlinkStory      = 'unlinkStory';
$lang->resource->release->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->release->linkBug          = 'linkBug';
$lang->resource->release->unlinkBug        = 'unlinkBug';
$lang->resource->release->batchUnlinkBug   = 'batchUnlinkBug';
$lang->resource->release->changeStatus     = 'changeStatus';
$lang->resource->release->notify           = 'notify';

$lang->release->methodOrder[5]  = 'browse';
$lang->release->methodOrder[10] = 'create';
$lang->release->methodOrder[15] = 'edit';
$lang->release->methodOrder[20] = 'delete';
$lang->release->methodOrder[25] = 'view';
$lang->release->methodOrder[35] = 'export';
$lang->release->methodOrder[40] = 'linkStory';
$lang->release->methodOrder[45] = 'unlinkStory';
$lang->release->methodOrder[50] = 'batchUnlinkStory';
$lang->release->methodOrder[55] = 'linkBug';
$lang->release->methodOrder[60] = 'unlinkBug';
$lang->release->methodOrder[65] = 'batchUnlinkBug';
$lang->release->methodOrder[70] = 'changeStatus';
$lang->release->methodOrder[75] = 'notify';

/* Kanban */
$lang->resource->kanban = new stdclass();
$lang->resource->kanban->space              = 'spaceCommon';
$lang->resource->kanban->createSpace        = 'createSpace';
$lang->resource->kanban->editSpace          = 'editSpace';
$lang->resource->kanban->closeSpace         = 'closeSpace';
$lang->resource->kanban->deleteSpace        = 'deleteSpace';
$lang->resource->kanban->activateSpace      = 'activateSpace';
$lang->resource->kanban->create             = 'create';
$lang->resource->kanban->edit               = 'edit';
$lang->resource->kanban->setting            = 'setting';
$lang->resource->kanban->view               = 'view';
$lang->resource->kanban->activate           = 'activate';
$lang->resource->kanban->close              = 'close';
$lang->resource->kanban->delete             = 'delete';
$lang->resource->kanban->createRegion       = 'createRegion';
$lang->resource->kanban->editRegion         = 'editRegion';
$lang->resource->kanban->sortRegion         = 'sortRegion';
$lang->resource->kanban->sortGroup          = 'sortGroup';
$lang->resource->kanban->deleteRegion       = 'deleteRegion';
$lang->resource->kanban->createLane         = 'createLane';
$lang->resource->kanban->sortLane           = 'sortLane';
$lang->resource->kanban->editLaneColor      = 'editLaneColor';
$lang->resource->kanban->editLaneName       = 'editLaneName';
$lang->resource->kanban->deleteLane         = 'deleteLane';
$lang->resource->kanban->createColumn       = 'createColumn';
$lang->resource->kanban->splitColumn        = 'splitColumn';
$lang->resource->kanban->archiveColumn      = 'archiveColumn';
$lang->resource->kanban->restoreColumn      = 'restoreColumn';
$lang->resource->kanban->setColumn          = 'editColumn';
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
$lang->resource->kanban->finishCard         = 'finishCard';
$lang->resource->kanban->activateCard       = 'activateCard';

$lang->kanban->methodOrder[5]   = 'space';
$lang->kanban->methodOrder[10]  = 'createSpace';
$lang->kanban->methodOrder[15]  = 'editSpace';
$lang->kanban->methodOrder[20]  = 'closeSpace';
$lang->kanban->methodOrder[25]  = 'deleteSpace';
$lang->kanban->methodOrder[35]  = 'create';
$lang->kanban->methodOrder[40]  = 'edit';
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
$lang->kanban->methodOrder[215] = 'batchCreateCard';
$lang->kanban->methodorder[220] = 'activate';
$lang->kanban->methodorder[225] = 'activateSpace';

/* Execution. */
$lang->resource->execution = new stdclass();
$lang->resource->execution->view              = 'view';
$lang->resource->execution->create            = 'createExec';
$lang->resource->execution->edit              = 'editAction';
$lang->resource->execution->batchedit         = 'batchEditAction';
$lang->resource->execution->batchchangestatus = 'batchChangeStatus';
$lang->resource->execution->start             = 'startAction';
$lang->resource->execution->activate          = 'activateAction';
$lang->resource->execution->putoff            = 'delayAction';
$lang->resource->execution->suspend           = 'suspendAction';
$lang->resource->execution->close             = 'closeAction';
$lang->resource->execution->delete            = 'deleteAB';
$lang->resource->execution->task              = 'task';
$lang->resource->execution->grouptask         = 'groupTask';
$lang->resource->execution->importtask        = 'importTask';
$lang->resource->execution->importplanstories = 'importPlanStories';
$lang->resource->execution->importBug         = 'importBug';
$lang->resource->execution->story             = 'story';
$lang->resource->execution->build             = 'build';
//$lang->resource->execution->qa                = 'qa';
$lang->resource->execution->testtask          = 'testtask';
$lang->resource->execution->testcase          = 'testcase';
$lang->resource->execution->bug               = 'bug';
$lang->resource->execution->testreport        = 'testreport';
$lang->resource->execution->burn              = 'burn';
$lang->resource->execution->computeBurn       = 'computeBurn';
$lang->resource->execution->cfd               = 'CFD';
$lang->resource->execution->computeCFD        = 'computeCFD';
$lang->resource->execution->fixFirst          = 'fixFirst';
$lang->resource->execution->team              = 'teamAction';
$lang->resource->execution->doc               = 'doc';
$lang->resource->execution->dynamic           = 'dynamic';
$lang->resource->execution->manageProducts    = 'manageProducts';
//$lang->resource->execution->manageChilds    = 'manageChilds';
$lang->resource->execution->manageMembers     = 'manageMembers';
$lang->resource->execution->unlinkMember      = 'unlinkMember';
$lang->resource->execution->linkStory         = 'linkStory';
$lang->resource->execution->unlinkStory       = 'unlinkStory';
$lang->resource->execution->batchUnlinkStory  = 'batchUnlinkStory';
$lang->resource->execution->updateOrder       = 'updateOrder';
$lang->resource->execution->taskKanban        = 'taskKanban';
$lang->resource->execution->printKanban       = 'printKanbanAction';
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
$lang->resource->execution->storyEstimate     = 'storyEstimate';
$lang->resource->execution->storyView         = 'storyView';
$lang->resource->execution->executionkanban   = 'kanbanAction';
$lang->resource->execution->kanban            = 'RDKanban';
$lang->resource->execution->setKanban         = 'setKanban';

$lang->execution->methodOrder[5]   = 'view';
$lang->execution->methodOrder[15]  = 'create';
$lang->execution->methodOrder[20]  = 'edit';
$lang->execution->methodOrder[25]  = 'batchedit';
$lang->execution->methodOrder[27]  = 'batchchangestatus';
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
$lang->execution->methodOrder[132] = 'cfd';
$lang->execution->methodOrder[133] = 'computeCFD';
$lang->execution->methodOrder[135] = 'fixFirst';
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
$lang->execution->methodOrder[260] = 'storyEstimate';
$lang->execution->methodOrder[265] = 'executionkanban';
$lang->execution->methodOrder[270] = 'kanban';
$lang->execution->methodOrder[275] = 'setKanban';

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

/* Build. */
$lang->resource->build = new stdclass();
$lang->resource->build->create           = 'create';
$lang->resource->build->edit             = 'edit';
$lang->resource->build->delete           = 'delete';
$lang->resource->build->view             = 'view';
$lang->resource->build->linkStory        = 'linkStory';
$lang->resource->build->unlinkStory      = 'unlinkStory';
$lang->resource->build->batchUnlinkStory = 'batchUnlinkStory';
$lang->resource->build->linkBug          = 'linkBug';
$lang->resource->build->unlinkBug        = 'unlinkBug';
$lang->resource->build->batchUnlinkBug   = 'batchUnlinkBug';

$lang->build->methodOrder[5]  = 'create';
$lang->build->methodOrder[10] = 'createProjectBuild';
$lang->build->methodOrder[15] = 'edit';
$lang->build->methodOrder[20] = 'editProjectBuild';
$lang->build->methodOrder[25] = 'delete';
$lang->build->methodOrder[30] = 'view';
$lang->build->methodOrder[35] = 'linkStory';
$lang->build->methodOrder[40] = 'unlinkStory';
$lang->build->methodOrder[45] = 'batchUnlinkStory';
$lang->build->methodOrder[50] = 'linkBug';
$lang->build->methodOrder[55] = 'unlinkBug';
$lang->build->methodOrder[60] = 'batchUnlinkBug';

/* QA. */
$lang->resource->qa = new stdclass();
$lang->resource->qa->index = 'indexAction';

$lang->qa->methodOrder[0] = 'index';

/* Bug. */
$lang->resource->bug = new stdclass();
$lang->resource->bug->browse             = 'browse';
$lang->resource->bug->create             = 'create';
$lang->resource->bug->batchCreate        = 'batchCreate';
$lang->resource->bug->confirm            = 'confirm';
$lang->resource->bug->batchConfirm       = 'batchConfirm';
$lang->resource->bug->view               = 'view';
$lang->resource->bug->edit               = 'edit';
$lang->resource->bug->linkBugs           = 'linkBugs';
$lang->resource->bug->batchEdit          = 'batchEdit';
$lang->resource->bug->batchClose         = 'batchClose';
$lang->resource->bug->assignTo           = 'assignAction';
$lang->resource->bug->batchAssignTo      = 'batchAssignTo';
$lang->resource->bug->resolve            = 'resolveAction';
$lang->resource->bug->batchResolve       = 'batchResolve';
$lang->resource->bug->activate           = 'activateAction';
$lang->resource->bug->batchActivate      = 'batchActivate';
$lang->resource->bug->close              = 'closeAction';
$lang->resource->bug->report             = 'reportAction';
$lang->resource->bug->export             = 'exportAction';
$lang->resource->bug->confirmStoryChange = 'confirmStoryChange';
$lang->resource->bug->delete             = 'deleteAction';
$lang->resource->bug->batchChangeModule  = 'batchChangeModule';
$lang->resource->bug->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->bug->batchChangePlan    = 'batchChangePlan';

$lang->bug->methodOrder[0]   = 'index';
$lang->bug->methodOrder[5]   = 'browse';
$lang->bug->methodOrder[10]  = 'create';
$lang->bug->methodOrder[15]  = 'batchCreate';
$lang->bug->methodOrder[20]  = 'batchEdit';
$lang->bug->methodOrder[25]  = 'confirm';
$lang->bug->methodOrder[30]  = 'batchConfirm';
$lang->bug->methodOrder[35]  = 'view';
$lang->bug->methodOrder[40]  = 'edit';
$lang->bug->methodOrder[45]  = 'assignTo';
$lang->bug->methodOrder[50]  = 'batchAssignTo';
$lang->bug->methodOrder[55]  = 'resolve';
$lang->bug->methodOrder[60]  = 'batchResolve';
$lang->bug->methodOrder[65]  = 'batchClose';
$lang->bug->methodOrder[67]  = 'batchActivate';
$lang->bug->methodOrder[70]  = 'activate';
$lang->bug->methodOrder[75]  = 'close';
$lang->bug->methodOrder[80]  = 'report';
$lang->bug->methodOrder[85]  = 'export';
$lang->bug->methodOrder[90]  = 'confirmStoryChange';
$lang->bug->methodOrder[95]  = 'delete';
$lang->bug->methodOrder[100] = 'linkBugs';
$lang->bug->methodOrder[105] = 'batchChangeModule';
$lang->bug->methodOrder[110] = 'batchChangeBranch';

/* Test case. */
$lang->resource->testcase = new stdclass();
$lang->resource->testcase->browse                  = 'browse';
$lang->resource->testcase->groupCase               = 'groupCase';
$lang->resource->testcase->zeroCase                = 'zeroCase';
$lang->resource->testcase->create                  = 'create';
$lang->resource->testcase->batchCreate             = 'batchCreate';
$lang->resource->testcase->createBug               = 'createBug';
$lang->resource->testcase->view                    = 'view';
$lang->resource->testcase->edit                    = 'edit';
$lang->resource->testcase->showScript              = 'showScript';
$lang->resource->testcase->linkCases               = 'linkCases';
$lang->resource->testcase->linkBugs                = 'linkBugs';
$lang->resource->testcase->batchEdit               = 'batchEdit';
$lang->resource->testcase->delete                  = 'deleteAction';
$lang->resource->testcase->batchDelete             = 'batchDelete';
$lang->resource->testcase->export                  = 'exportAction';
$lang->resource->testcase->exportTemplate          = 'exportTemplate';
$lang->resource->testcase->import                  = 'importAction';
$lang->resource->testcase->confirmChange           = 'confirmChange';
$lang->resource->testcase->confirmStoryChange      = 'confirmStoryChange';
$lang->resource->testcase->batchChangeModule       = 'batchChangeModule';
$lang->resource->testcase->batchChangeBranch       = 'batchChangeBranch';
$lang->resource->testcase->bugs                    = 'bugs';
$lang->resource->testcase->review                  = 'review';
$lang->resource->testcase->batchReview             = 'batchReview';
$lang->resource->testcase->importFromLib           = 'importFromLib';
$lang->resource->testcase->batchChangeType         = 'batchChangeType';
$lang->resource->testcase->confirmLibcaseChange    = 'confirmLibcaseChange';
$lang->resource->testcase->ignoreLibcaseChange     = 'ignoreLibcaseChange';
$lang->resource->testcase->batchConfirmStoryChange = 'batchConfirmStoryChange';
$lang->resource->testcase->importToLib             = 'importToLib';
$lang->resource->testcase->automation              = 'automation';

$lang->resource->testcase->createScene      = 'createScene';
$lang->resource->testcase->editScene        = 'editScene';
$lang->resource->testcase->deleteScene      = 'deleteScene';
$lang->resource->testcase->changeScene      = 'changeScene';
$lang->resource->testcase->batchChangeScene = 'batchChangeScene';
$lang->resource->testcase->updateOrder      = 'updateOrder';

$lang->resource->testcase->importXmind     = 'importXmind';
$lang->resource->testcase->exportXmind     = 'exportXmind';

$lang->testcase->methodOrder[0]   = 'index';
$lang->testcase->methodOrder[5]   = 'browse';
$lang->testcase->methodOrder[10]  = 'groupCase';
$lang->testcase->methodOrder[15]  = 'zeroCase';
$lang->testcase->methodOrder[20]  = 'create';
$lang->testcase->methodOrder[25]  = 'batchCreate';
$lang->testcase->methodOrder[30]  = 'createBug';
$lang->testcase->methodOrder[35]  = 'view';
$lang->testcase->methodOrder[40]  = 'edit';
$lang->testcase->methodOrder[45]  = 'delete';
$lang->testcase->methodOrder[50]  = 'export';
$lang->testcase->methodOrder[55]  = 'confirmChange';
$lang->testcase->methodOrder[60]  = 'confirmStoryChange';
$lang->testcase->methodOrder[65]  = 'batchEdit';
$lang->testcase->methodOrder[70]  = 'batchDelete';
$lang->testcase->methodOrder[75]  = 'batchChangeModule';
$lang->testcase->methodOrder[80]  = 'batchChangeBranch';
$lang->testcase->methodOrder[85]  = 'linkCases';
$lang->testcase->methodOrder[87]  = 'linkBugs';
$lang->testcase->methodOrder[90]  = 'bugs';
$lang->testcase->methodOrder[95]  = 'review';
$lang->testcase->methodOrder[100] = 'batchReview';
$lang->testcase->methodOrder[110] = 'batchConfirmStoryChange';
$lang->testcase->methodOrder[115] = 'importFromLib';
$lang->testcase->methodOrder[120] = 'batchChangeType';
$lang->testcase->methodOrder[125] = 'confirmLibcaseChange';
$lang->testcase->methodOrder[130] = 'ignoreLibcaseChange';
$lang->testcase->methodOrder[135] = 'batchConfirmStoryChange';
$lang->testcase->methodOrder[140] = 'importToLib';
$lang->testcase->methodOrder[145] = 'automation';
$lang->testcase->methodOrder[150] = 'showScript';

$lang->testcase->methodOrder[155] = 'createScene';
$lang->testcase->methodOrder[160] = 'editScene';
$lang->testcase->methodOrder[165] = 'deleteScene';
$lang->testcase->methodOrder[170] = 'changeScene';
$lang->testcase->methodOrder[175] = 'batchChangeScene';
$lang->testcase->methodOrder[180] = 'updateOrder';
$lang->testcase->methodOrder[185] = 'importXmind';
$lang->testcase->methodOrder[190] = 'exportXmind';

/* Test task. */
$lang->resource->testtask = new stdclass();
$lang->resource->testtask->create           = 'create';
$lang->resource->testtask->browse           = 'browse';
$lang->resource->testtask->view             = 'viewAction';
$lang->resource->testtask->cases            = 'casesAction';
$lang->resource->testtask->groupCase        = 'groupCase';
$lang->resource->testtask->edit             = 'edit';
$lang->resource->testtask->start            = 'startAction';
$lang->resource->testtask->close            = 'closeAction';
$lang->resource->testtask->delete           = 'delete';
$lang->resource->testtask->batchAssign      = 'batchAssign';
$lang->resource->testtask->linkcase         = 'linkCase';
$lang->resource->testtask->unlinkcase       = 'lblUnlinkCase';
$lang->resource->testtask->batchUnlinkCases = 'batchUnlinkCases';
$lang->resource->testtask->runcase          = 'lblRunCase';
$lang->resource->testtask->results          = 'resultsAction';
$lang->resource->testtask->batchRun         = 'batchRun';
$lang->resource->testtask->activate         = 'activateAction';
$lang->resource->testtask->block            = 'blockAction';
$lang->resource->testtask->report           = 'reportAction';
$lang->resource->testtask->browseUnits      = 'browseUnits';
$lang->resource->testtask->unitCases        = 'unitCases';
$lang->resource->testtask->importUnitResult = 'importUnitResult';

$lang->testtask->methodOrder[0]   = 'index';
$lang->testtask->methodOrder[5]   = 'create';
$lang->testtask->methodOrder[10]  = 'browse';
$lang->testtask->methodOrder[15]  = 'view';
$lang->testtask->methodOrder[20]  = 'cases';
$lang->testtask->methodOrder[25]  = 'groupCase';
$lang->testtask->methodOrder[30]  = 'edit';
$lang->testtask->methodOrder[35]  = 'start';
$lang->testtask->methodOrder[40]  = 'activate';
$lang->testtask->methodOrder[45]  = 'block';
$lang->testtask->methodOrder[50]  = 'close';
$lang->testtask->methodOrder[55]  = 'delete';
$lang->testtask->methodOrder[60]  = 'batchAssign';
$lang->testtask->methodOrder[65]  = 'linkcase';
$lang->testtask->methodOrder[70]  = 'unlinkcase';
$lang->testtask->methodOrder[75]  = 'runcase';
$lang->testtask->methodOrder[80]  = 'results';
$lang->testtask->methodOrder[85]  = 'batchUnlinkCases';
$lang->testtask->methodOrder[90]  = 'report';
$lang->testtask->methodOrder[95]  = 'browseUnits';
$lang->testtask->methodOrder[100] = 'unitCases';
$lang->testtask->methodOrder[105] = 'importUnitResult';

$lang->resource->testreport = new stdclass();
$lang->resource->testreport->browse     = 'browse';
$lang->resource->testreport->create     = 'create';
$lang->resource->testreport->view       = 'view';
$lang->resource->testreport->delete     = 'delete';
$lang->resource->testreport->edit       = 'edit';

$lang->testreport->methodOrder[0]  = 'browse';
$lang->testreport->methodOrder[5]  = 'create';
$lang->testreport->methodOrder[10] = 'view';
$lang->testreport->methodOrder[15] = 'delete';
$lang->testreport->methodOrder[20] = 'edit';

$lang->resource->testsuite = new stdclass();
$lang->resource->testsuite->browse           = 'browse';
$lang->resource->testsuite->create           = 'create';
$lang->resource->testsuite->view             = 'view';
$lang->resource->testsuite->edit             = 'edit';
$lang->resource->testsuite->delete           = 'delete';
$lang->resource->testsuite->linkCase         = 'linkCase';
$lang->resource->testsuite->unlinkCase       = 'unlinkCaseAction';
$lang->resource->testsuite->batchUnlinkCases = 'batchUnlinkCases';

$lang->testsuite->methodOrder[5]  = 'browse';
$lang->testsuite->methodOrder[10] = 'create';
$lang->testsuite->methodOrder[15] = 'view';
$lang->testsuite->methodOrder[20] = 'edit';
$lang->testsuite->methodOrder[25] = 'delete';
$lang->testsuite->methodOrder[30] = 'linkCase';
$lang->testsuite->methodOrder[35] = 'unlinkCase';
$lang->testsuite->methodOrder[40] = 'batchUnlinkCases';

$lang->resource->caselib = new stdclass();
$lang->resource->caselib->browse           = 'browseAction';
$lang->resource->caselib->create           = 'create';
$lang->resource->caselib->edit             = 'edit';
$lang->resource->caselib->delete           = 'deleteAction';
$lang->resource->caselib->view             = 'view';
$lang->resource->caselib->createCase       = 'createCase';
$lang->resource->caselib->batchCreateCase  = 'batchCreateCase';
$lang->resource->caselib->exportTemplate   = 'exportTemplate';
$lang->resource->caselib->import           = 'importAction';
$lang->resource->caselib->showImport       = 'showImport';

$lang->caselib->methodOrder[5]  = 'browse';
$lang->caselib->methodOrder[10] = 'create';
$lang->caselib->methodOrder[15] = 'edit';
$lang->caselib->methodOrder[20] = 'delete';
$lang->caselib->methodOrder[25] = 'view';
$lang->caselib->methodOrder[30] = 'createCase';
$lang->caselib->methodOrder[35] = 'batchCreateCase';
$lang->caselib->methodOrder[40] = 'exportTemplate';
$lang->caselib->methodOrder[45] = 'import';
$lang->caselib->methodOrder[50] = 'showImport';

$lang->resource->host = new stdclass();
$lang->resource->host->browse       = 'browse';
$lang->resource->host->create       = 'create';
$lang->resource->host->edit         = 'editAction';
$lang->resource->host->delete       = 'deleteAction';
$lang->resource->host->view         = 'view';
$lang->resource->host->changeStatus = 'changeStatus';
$lang->resource->host->treemap      = 'treemap';

$lang->host->methodOrder[0]  = 'browse';
$lang->host->methodOrder[5]  = 'create';
$lang->host->methodOrder[10] = 'edit';
$lang->host->methodOrder[15] = 'delete';
$lang->host->methodOrder[20] = 'view';
$lang->host->methodOrder[25] = 'changeStatus';
$lang->host->methodOrder[30] = 'treemap';

$lang->resource->zahost = new stdclass();
$lang->resource->zahost->browse         = 'browse';
$lang->resource->zahost->create         = 'create';
$lang->resource->zahost->edit           = 'editAction';
$lang->resource->zahost->delete         = 'deleteAction';
$lang->resource->zahost->view           = 'view';
$lang->resource->zahost->browseImage    = 'browseImage';
$lang->resource->zahost->downloadImage  = 'downloadImage';
$lang->resource->zahost->cancelDownload = 'cancel';

$lang->zahost->methodOrder[0]  = 'browse';
$lang->zahost->methodOrder[5]  = 'create';
$lang->zahost->methodOrder[10] = 'edit';
$lang->zahost->methodOrder[15] = 'delete';
$lang->zahost->methodOrder[20] = 'view';
$lang->zahost->methodOrder[25] = 'browseImage';
$lang->zahost->methodOrder[30] = 'downloadImage';
$lang->zahost->methodOrder[35] = 'cancelDownload';

$lang->resource->zanode = new stdclass();
$lang->resource->zanode->browse          = 'browse';
$lang->resource->zanode->create          = 'create';
$lang->resource->zanode->edit            = 'edit';
$lang->resource->zanode->destroy         = 'destroy';
$lang->resource->zanode->reboot          = 'reboot';
$lang->resource->zanode->suspend         = 'suspend';
$lang->resource->zanode->resume          = 'resume';
$lang->resource->zanode->getVNC          = 'getVNC';
$lang->resource->zanode->start           = 'boot';
$lang->resource->zanode->close           = 'shutdown';
$lang->resource->zanode->view            = 'view';
$lang->resource->zanode->createImage     = 'createImage';
$lang->resource->zanode->browseSnapshot  = 'browseSnapshot';
$lang->resource->zanode->createSnapshot  = 'createSnapshot';
$lang->resource->zanode->editSnapshot    = 'editSnapshot';
$lang->resource->zanode->restoreSnapshot = 'restoreSnapshot';
$lang->resource->zanode->deleteSnapshot  = 'deleteSnapshot';

$lang->zanode->methodOrder[0]  = 'browse';
$lang->zanode->methodOrder[5]  = 'create';
$lang->zanode->methodOrder[10] = 'edit';
$lang->zanode->methodOrder[15] = 'destroy';
$lang->zanode->methodOrder[20] = 'reboot';
$lang->zanode->methodOrder[35] = 'suspend';
$lang->zanode->methodOrder[30] = 'resume';
$lang->zanode->methodOrder[35] = 'getVNC';
$lang->zanode->methodOrder[40] = 'start';
$lang->zanode->methodOrder[45] = 'close';
$lang->zanode->methodOrder[50] = 'view';
$lang->zanode->methodOrder[55] = 'createImage';
$lang->zanode->methodOrder[60] = 'browseSnapshot';
$lang->zanode->methodOrder[65] = 'createSnapshot';
$lang->zanode->methodOrder[70] = 'editSnapshot';
$lang->zanode->methodOrder[75] = 'restoreSnapshot';
$lang->zanode->methodOrder[80] = 'deleteSnapshot';

$lang->resource->repo = new stdclass();
$lang->resource->repo->browse          = 'browseAction';
$lang->resource->repo->view            = 'view';
$lang->resource->repo->log             = 'log';
$lang->resource->repo->revision        = 'revisionAction';
$lang->resource->repo->blame           = 'blameAction';
$lang->resource->repo->create          = 'createAction';
$lang->resource->repo->edit            = 'editAction';
$lang->resource->repo->delete          = 'delete';
$lang->resource->repo->showSyncCommit  = 'showSyncCommit';
$lang->resource->repo->diff            = 'diffAction';
$lang->resource->repo->download        = 'downloadAction';
$lang->resource->repo->maintain        = 'maintain';
$lang->resource->repo->setRules        = 'setRules';
$lang->resource->repo->apiGetRepoByUrl = 'apiGetRepoByUrl';
$lang->resource->repo->downloadCode    = 'downloadCode';
$lang->resource->repo->linkStory       = 'linkStory';
$lang->resource->repo->linkBug         = 'linkBug';
$lang->resource->repo->linkTask        = 'linkTask';
$lang->resource->repo->unlink          = 'unlink';
$lang->resource->repo->import          = 'importAction';
$lang->resource->repo->createRepo      = 'createRepoAction';
$lang->resource->repo->createBranch    = 'createBranchAction';

$lang->repo->methodOrder[5]   = 'create';
$lang->repo->methodOrder[10]  = 'edit';
$lang->repo->methodOrder[15]  = 'delete';
$lang->repo->methodOrder[20]  = 'showSyncCommit';
$lang->repo->methodOrder[25]  = 'maintain';
$lang->repo->methodOrder[30]  = 'browse';
$lang->repo->methodOrder[35]  = 'view';
$lang->repo->methodOrder[40]  = 'diff';
$lang->repo->methodOrder[45]  = 'log';
$lang->repo->methodOrder[50]  = 'revision';
$lang->repo->methodOrder[55]  = 'blame';
$lang->repo->methodOrder[60]  = 'download';
$lang->repo->methodOrder[70]  = 'apiGetRepoByUrl';
$lang->repo->methodOrder[75]  = 'downloadCode';
$lang->repo->methodOrder[80]  = 'linkStory';
$lang->repo->methodOrder[85]  = 'linkBug';
$lang->repo->methodOrder[90]  = 'linkTask';
$lang->repo->methodOrder[95]  = 'unlink';
$lang->repo->methodOrder[100] = 'import';
$lang->repo->methodOrder[105] = 'createRepo';
$lang->repo->methodOrder[110] = 'createBranch';

$lang->resource->ci = new stdclass();
$lang->resource->ci->commitResult       = 'commitResult';
$lang->resource->ci->checkCompileStatus = 'checkCompileStatus';

$lang->ci->methodOrder[5]  = 'commitResult';
$lang->ci->methodOrder[10] = 'checkCompileStatus';

$lang->resource->compile = new stdclass();
$lang->resource->compile->browse      = 'browse';
$lang->resource->compile->logs        = 'logs';
$lang->resource->compile->syncCompile = 'syncCompile';

$lang->compile->methodOrder[5]  = 'browse';
$lang->compile->methodOrder[10] = 'logs';
$lang->compile->methodOrder[15] = 'syncCompile';

$lang->resource->job = new stdclass();
$lang->resource->job->browse = 'browseAction';
$lang->resource->job->create = 'create';
$lang->resource->job->edit   = 'edit';
$lang->resource->job->delete = 'delete';
$lang->resource->job->exec   = 'exec';
$lang->resource->job->view   = 'view';

$lang->job->methodOrder[5]  = 'browse';
$lang->job->methodOrder[10] = 'create';
$lang->job->methodOrder[15] = 'edit';
$lang->job->methodOrder[20] = 'delete';
$lang->job->methodOrder[25] = 'exec';

$lang->resource->account = new stdclass();
$lang->resource->account->browse = 'browse';
$lang->resource->account->create = 'create';
$lang->resource->account->edit   = 'editAction';
$lang->resource->account->delete = 'deleteAction';
$lang->resource->account->view   = 'view';

$lang->account->methodOrder[0]  = 'browse';
$lang->account->methodOrder[5]  = 'create';
$lang->account->methodOrder[10] = 'edit';
$lang->account->methodOrder[15] = 'delete';
$lang->account->methodOrder[20] = 'view';

$lang->resource->serverroom = new stdclass();
$lang->resource->serverroom->browse = 'browse';
$lang->resource->serverroom->create = 'create';
$lang->resource->serverroom->edit   = 'editAction';
$lang->resource->serverroom->delete = 'delete';
$lang->resource->serverroom->view   = 'view';

$lang->serverroom->methodOrder[0]  = 'browse';
$lang->serverroom->methodOrder[5]  = 'create';
$lang->serverroom->methodOrder[10] = 'edit';
$lang->serverroom->methodOrder[15] = 'delete';
$lang->serverroom->methodOrder[20] = 'view';

$lang->resource->instance = new stdclass();
$lang->resource->instance->manage = 'manage';

$lang->resource->space = new stdclass();
$lang->resource->space->browse = 'browse';

$lang->resource->system = new stdclass();
$lang->resource->system->dashboard    = 'dashboard';
$lang->resource->system->dblist       = 'dbList';
$lang->resource->system->configdomain = 'configDomain';
$lang->resource->system->ossview      = 'ossView';

$lang->resource->ops = new stdclass();
$lang->resource->ops->provider = 'provider';
$lang->resource->ops->city     = 'city';
$lang->resource->ops->cpubrand = 'cpuBrand';
$lang->resource->ops->os       = 'os';

$lang->resource->artifactrepo = new stdclass();
$lang->resource->artifactrepo->browse                  = 'browse';
$lang->resource->artifactrepo->ajaxGetArtifactRepos    = 'ajaxGetArtifactRepos';
$lang->resource->artifactrepo->create                  = 'create';
$lang->resource->artifactrepo->edit                    = 'edit';
$lang->resource->artifactrepo->ajaxUpdateArtifactRepos = 'ajaxUpdateArtifactRepos';
$lang->resource->artifactrepo->delete                  = 'delete';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->index          = 'index';
$lang->resource->doc->mySpace        = 'mySpace';
$lang->resource->doc->myView         = 'myView';
$lang->resource->doc->myCollection   = 'myCollection';
$lang->resource->doc->myCreation     = 'myCreation';
$lang->resource->doc->myEdited       = 'myEdited';
$lang->resource->doc->createLib      = 'createLib';
$lang->resource->doc->editLib        = 'editLib';
$lang->resource->doc->deleteLib      = 'deleteLib';
$lang->resource->doc->create         = 'createOrUpload';
$lang->resource->doc->edit           = 'edit';
$lang->resource->doc->view           = 'view';
$lang->resource->doc->delete         = 'delete';
$lang->resource->doc->deleteFile     = 'deleteFile';
$lang->resource->doc->collect        = 'collectAction';
$lang->resource->doc->productSpace   = 'productSpace';
$lang->resource->doc->projectSpace   = 'projectSpace';
$lang->resource->doc->teamSpace      = 'teamSpace';
$lang->resource->doc->showFiles      = 'showFiles';
$lang->resource->doc->addCatalog     = 'addCatalog';
$lang->resource->doc->editCatalog    = 'editCatalog';
$lang->resource->doc->sortCatalog    = 'sortCatalog';
$lang->resource->doc->deleteCatalog  = 'deleteCatalog';
$lang->resource->doc->displaySetting = 'displaySetting';
$lang->resource->doc->exportFiles    = 'exportFiles';

$lang->doc->methodOrder[5]   = 'index';
$lang->doc->methodOrder[10]  = 'mySpace';
$lang->doc->methodOrder[15]  = 'myView';
$lang->doc->methodOrder[20]  = 'myCollection';
$lang->doc->methodOrder[25]  = 'myCreation';
$lang->doc->methodOrder[30]  = 'myEdited';
$lang->doc->methodOrder[35]  = 'createLib';
$lang->doc->methodOrder[40]  = 'editLib';
$lang->doc->methodOrder[45]  = 'deleteLib';
$lang->doc->methodOrder[50]  = 'create';
$lang->doc->methodOrder[55]  = 'edit';
$lang->doc->methodOrder[60]  = 'view';
$lang->doc->methodOrder[65]  = 'delete';
$lang->doc->methodOrder[70]  = 'deleteFile';
$lang->doc->methodOrder[75]  = 'collect';
$lang->doc->methodOrder[80]  = 'productSpace';
$lang->doc->methodOrder[85]  = 'projectSpace';
$lang->doc->methodOrder[90]  = 'teamSpace';
$lang->doc->methodOrder[95]  = 'showFiles';
$lang->doc->methodOrder[100] = 'addCatalog';
$lang->doc->methodOrder[105] = 'editCatalog';
$lang->doc->methodOrder[110] = 'sortCatalog';
$lang->doc->methodOrder[115] = 'deleteCatalog';
$lang->doc->methodOrder[120] = 'displaySetting';
$lang->doc->methodOrder[125] = 'exportFiles';

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
$lang->resource->custom->kanban             = 'kanban';
$lang->resource->custom->code               = 'code';
$lang->resource->custom->hours              = 'hours';
$lang->resource->custom->percent            = 'percent';
$lang->resource->custom->limitTaskDate      = 'limitTaskDateAction';

$lang->custom->methodOrder[10] = 'set';
$lang->custom->methodOrder[15] = 'product';
$lang->custom->methodOrder[20] = 'execution';
$lang->custom->methodOrder[25] = 'required';
$lang->custom->methodOrder[30] = 'restore';
$lang->custom->methodOrder[35] = 'flow';
$lang->custom->methodOrder[45] = 'timezone';
$lang->custom->methodOrder[50] = 'setStoryConcept';
$lang->custom->methodOrder[55] = 'editStoryConcept';
$lang->custom->methodOrder[60] = 'browseStoryConcept';
$lang->custom->methodOrder[65] = 'setDefaultConcept';
$lang->custom->methodOrder[70] = 'deleteStoryConcept';
$lang->custom->methodOrder[75] = 'kanban';
$lang->custom->methodOrder[80] = 'code';
$lang->custom->methodOrder[85] = 'hours';
$lang->custom->methodOrder[90] = 'percent';
$lang->custom->methodOrder[95] = 'limitTaskDate';

$lang->resource->datatable = new stdclass();
$lang->resource->datatable->setGlobal = 'setGlobal';

$lang->datatable->methodOrder[5]  = 'setGlobal';

/* Subversion. */
$lang->resource->svn = new stdclass();
$lang->resource->svn->diff    = 'diff';
$lang->resource->svn->cat     = 'cat';
$lang->resource->svn->apiSync = 'apiSync';

$lang->svn->methodOrder[5]  = 'diff';
$lang->svn->methodOrder[10] = 'cat';
$lang->svn->methodOrder[15] = 'apiSync';

/* merge request. */
$lang->resource->mr = new stdclass();
$lang->resource->mr->create    = 'create';
$lang->resource->mr->browse    = 'browseAction';
$lang->resource->mr->edit      = 'edit';
$lang->resource->mr->delete    = 'delete';
$lang->resource->mr->view      = 'viewAction';
$lang->resource->mr->accept    = 'accept';
$lang->resource->mr->diff      = 'viewDiff';
$lang->resource->mr->link      = 'linkList';
$lang->resource->mr->linkStory = 'linkStory';
$lang->resource->mr->linkBug   = 'linkBug';
$lang->resource->mr->linkTask  = 'linkTask';
$lang->resource->mr->unlink    = 'unlink';
$lang->resource->mr->approval  = 'approval';
$lang->resource->mr->close     = 'close';
$lang->resource->mr->reopen    = 'reopen';

$lang->mr->methodOrder[10] = 'create';
$lang->mr->methodOrder[15] = 'browse';
$lang->mr->methodOrder[20] = 'edit';
$lang->mr->methodOrder[25] = 'delete';
$lang->mr->methodOrder[35] = 'view';
$lang->mr->methodOrder[45] = 'accept';
$lang->mr->methodOrder[50] = 'diff';
$lang->mr->methodOrder[55] = 'link';
$lang->mr->methodOrder[60] = 'linkStory';
$lang->mr->methodOrder[65] = 'linkBug';
$lang->mr->methodOrder[70] = 'linkTask';
$lang->mr->methodOrder[75] = 'unlink';
$lang->mr->methodOrder[80] = 'approval';
$lang->mr->methodOrder[85] = 'close';
$lang->mr->methodOrder[90] = 'reopen';
$lang->mr->methodOrder[95] = 'addReview';

/* App. */
$lang->resource->app = new stdclass();

/* Git. */
$lang->resource->git = new stdclass();
$lang->resource->git->diff    = 'diff';
$lang->resource->git->cat     = 'cat';
$lang->resource->git->apiSync = 'apiSync';

$lang->git->methodOrder[5]  = 'diff';
$lang->git->methodOrder[10] = 'cat';
$lang->git->methodOrder[15] = 'apiSync';

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
$lang->resource->dept->manageChild = 'manageChildAction';
$lang->resource->dept->edit        = 'edit';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'edit';
$lang->dept->methodOrder[25] = 'delete';

/* Group. */
$lang->resource->group = new stdclass();
$lang->resource->group->browse              = 'browseAction';
$lang->resource->group->create              = 'create';
$lang->resource->group->edit                = 'edit';
$lang->resource->group->copy                = 'copy';
$lang->resource->group->delete              = 'delete';
$lang->resource->group->manageView          = 'manageView';
$lang->resource->group->managePriv          = 'managePriv';
$lang->resource->group->manageMember        = 'manageMember';
$lang->resource->group->manageProjectAdmin  = 'manageProjectAdmin';
//$lang->resource->group->editManagePriv      = 'editManagePriv';
//$lang->resource->group->managePrivPackage   = 'managePrivPackage';
//$lang->resource->group->createPrivPackage   = 'createPrivPackage';
//$lang->resource->group->editPrivPackage     = 'editPrivPackage';
//$lang->resource->group->deletePrivPackage   = 'deletePrivPackage';
//$lang->resource->group->sortPrivPackages    = 'sortPrivPackages';
//$lang->resource->group->addRelation         = 'addRelation';
//$lang->resource->group->deleteRelation      = 'deleteRelation';
//$lang->resource->group->batchDeleteRelation = 'batchDeleteRelation';
//$lang->resource->group->createPriv          = 'createPriv';
//$lang->resource->group->editPriv            = 'editPriv';
//$lang->resource->group->deletePriv          = 'deletePriv';
//$lang->resource->group->batchChangePackage  = 'batchChangePackage';

$lang->group->methodOrder[5]   = 'browse';
$lang->group->methodOrder[10]  = 'create';
$lang->group->methodOrder[15]  = 'edit';
$lang->group->methodOrder[20]  = 'copy';
$lang->group->methodOrder[25]  = 'delete';
$lang->group->methodOrder[30]  = 'managePriv';
$lang->group->methodOrder[35]  = 'manageMember';
$lang->group->methodOrder[40]  = 'manageProjectAdmin';
$lang->group->methodOrder[45]  = 'editManagePriv';
$lang->group->methodOrder[50]  = 'managePrivPackage';
$lang->group->methodOrder[55]  = 'createPrivPackage';
$lang->group->methodOrder[60]  = 'editPrivPackage';
$lang->group->methodOrder[65]  = 'deletePrivPackage';
$lang->group->methodOrder[70]  = 'sortPrivPackages';
$lang->group->methodOrder[75]  = 'batchChangePackage';
$lang->group->methodOrder[80]  = 'addRelation';
$lang->group->methodOrder[85]  = 'deleteRelation';
$lang->group->methodOrder[90]  = 'batchDeleteRelation';
$lang->group->methodOrder[95]  = 'createPriv';
$lang->group->methodOrder[100] = 'editPriv';
$lang->group->methodOrder[105] = 'deletePriv';

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
$lang->resource->user->bug            = 'bug';
$lang->resource->user->testTask       = 'testTask';
$lang->resource->user->testCase       = 'testCase';
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

/* Screen. */
$lang->resource->screen = new stdclass();
$lang->resource->screen->browse        = 'browse';
$lang->resource->screen->view          = 'view';
$lang->resource->screen->annualData    = 'annualData';
$lang->resource->screen->allAnnualData = 'allAnnualData';

$lang->screen->methodOrder[0]  = 'browse';
$lang->screen->methodOrder[5]  = 'view';
$lang->screen->methodOrder[14] = 'annualData';
$lang->screen->methodOrder[15] = 'allAnnualData';

/* Pivot. */
$lang->resource->pivot = new stdclass();
$lang->resource->pivot->preview          = 'preview';
$lang->resource->pivot->productSummary   = 'productSummary';
$lang->resource->pivot->projectDeviation = 'projectDeviation';
$lang->resource->pivot->bugCreate        = 'bugCreate';
$lang->resource->pivot->bugAssign        = 'bugAssign';
$lang->resource->pivot->workload         = 'workload';

$lang->pivot->methodOrder[2]  = 'preview';
$lang->pivot->methodOrder[10] = 'productSummary';
$lang->pivot->methodOrder[15] = 'projectDeviation';
$lang->pivot->methodOrder[20] = 'bugCreate';
$lang->pivot->methodOrder[25] = 'bugAssign';
$lang->pivot->methodOrder[30] = 'workload';

/* Chart. */
$lang->resource->chart = new stdclass();
$lang->resource->chart->preview = 'preview';

$lang->chart->methodOrder[2] = 'preview';

/* Metric. */
$lang->resource->metric = new stdclass();
$lang->resource->metric->browse    = 'browseAction';
$lang->resource->metric->preview   = 'preview';
$lang->resource->metric->details   = 'detailsAction';
$lang->resource->metric->view      = 'viewAction';
$lang->resource->metric->edit      = 'editAction';
$lang->resource->metric->implement = 'implementAction';
$lang->resource->metric->delete    = 'deleteAction';

$lang->metric->methodOrder[0]  = 'browse';
$lang->metric->methodOrder[5]  = 'preview';
$lang->metric->methodOrder[6]  = 'details';
$lang->metric->methodOrder[10] = 'view';
$lang->metric->methodOrder[15] = 'edit';
$lang->metric->methodOrder[20] = 'implement';
$lang->metric->methodOrder[25] = 'delete';

/* Report . */
$lang->resource->report = new stdclass();

/* Search. */
$lang->resource->search = new stdclass();
$lang->resource->search->buildForm   = 'buildForm';
$lang->resource->search->buildQuery  = 'buildQuery';
$lang->resource->search->saveQuery   = 'saveQuery';
$lang->resource->search->deleteQuery = 'deleteQuery';
$lang->resource->search->index       = 'index';
$lang->resource->search->buildIndex  = 'buildIndex';

$lang->search->methodOrder[5]  = 'buildForm';
$lang->search->methodOrder[10] = 'buildQuery';
$lang->search->methodOrder[15] = 'saveQuery';
$lang->search->methodOrder[20] = 'deleteQuery';
$lang->search->methodOrder[30] = 'index';
$lang->search->methodOrder[35] = 'buildIndex';

/* Admin. */
$lang->resource->admin = new stdclass();
$lang->resource->admin->index           = 'index';
$lang->resource->admin->safe            = 'safeIndex';
$lang->resource->admin->checkWeak       = 'checkWeak';
$lang->resource->admin->sso             = 'ssoAction';
$lang->resource->admin->register        = 'register';
$lang->resource->admin->resetPWDSetting = 'resetPWDSetting';
$lang->resource->admin->tableEngine     = 'tableEngine';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[10] = 'safeIndex';
$lang->admin->methodOrder[15] = 'checkWeak';
$lang->admin->methodOrder[20] = 'sso';
$lang->admin->methodOrder[25] = 'register';
$lang->admin->methodOrder[35] = 'resetPWDSetting';
$lang->admin->methodOrder[40] = 'tableEngine';

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
$lang->resource->api = new stdclass();
$lang->resource->api->index         = 'index';
$lang->resource->api->createLib     = 'createLib';
$lang->resource->api->editLib       = 'editLib';
$lang->resource->api->deleteLib     = 'deleteLib';
$lang->resource->api->createRelease = 'createRelease';
$lang->resource->api->releases      = 'releases';
$lang->resource->api->deleteRelease = 'deleteRelease';
$lang->resource->api->struct        = 'struct';
$lang->resource->api->createStruct  = 'createStruct';
$lang->resource->api->editStruct    = 'editStruct';
$lang->resource->api->deleteStruct  = 'deleteStruct';
$lang->resource->api->create        = 'create';
$lang->resource->api->edit          = 'edit';
$lang->resource->api->delete        = 'delete';
$lang->resource->api->addCatalog    = 'addCatalog';
$lang->resource->api->editCatalog   = 'editCatalog';
$lang->resource->api->sortCatalog   = 'sortCatalog';
$lang->resource->api->deleteCatalog = 'deleteCatalog';

$lang->resource->api->getModel     = 'getModel';
$lang->resource->api->debug        = 'debug';
$lang->resource->api->sql          = 'sql';

/* AI methods. */
$lang->resource->ai = new stdclass();
$lang->resource->ai->models                 = 'modelBrowse';
$lang->resource->ai->editModel              = 'modelEdit';
$lang->resource->ai->testConnection         = 'modelTestConnection';
$lang->resource->ai->promptPublish          = 'promptPublish';
$lang->resource->ai->promptUnpublish        = 'promptUnpublish';
$lang->resource->ai->prompts                = 'promptBrowse';
$lang->resource->ai->promptView             = 'promptView';
$lang->resource->ai->promptExecute          = 'promptExecute';
$lang->resource->ai->promptExecutionReset   = 'promptExecutionReset';
$lang->resource->ai->chat                   = 'chat';

$lang->api->methodOrder[0]   = 'index';
$lang->api->methodOrder[5]   = 'createLib';
$lang->api->methodOrder[10]  = 'editLib';
$lang->api->methodOrder[15]  = 'deleteLib';
$lang->api->methodOrder[20]  = 'createRelease';
$lang->api->methodOrder[25]  = 'releases';
$lang->api->methodOrder[30]  = 'deleteRelease';
$lang->api->methodOrder[35]  = 'struct';
$lang->api->methodOrder[40]  = 'createStruct';
$lang->api->methodOrder[45]  = 'editStruct';
$lang->api->methodOrder[50]  = 'deleteStruct';
$lang->api->methodOrder[55]  = 'create';
$lang->api->methodOrder[60]  = 'edit';
$lang->api->methodOrder[65]  = 'delete';
$lang->api->methodOrder[70]  = 'addCatalog';
$lang->api->methodOrder[75]  = 'editCatalog';
$lang->api->methodOrder[80]  = 'sortCatalog';
$lang->api->methodOrder[85]  = 'deleteCatalog';
$lang->api->methodOrder[90]  = 'getModel';
$lang->api->methodOrder[95]  = 'debug';
$lang->api->methodOrder[100] = 'sql';

$lang->resource->file = new stdclass();
$lang->resource->file->download     = 'download';
$lang->resource->file->preview      = 'preview';
$lang->resource->file->edit         = 'edit';
$lang->resource->file->delete       = 'delete';
$lang->resource->file->uploadImages = 'uploadImages';
$lang->resource->file->setPublic     = 'setPublic';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'preview';
$lang->file->methodOrder[15] = 'edit';
$lang->file->methodOrder[20] = 'delete';
$lang->file->methodOrder[25] = 'uploadImages';
$lang->file->methodOrder[30] = 'setPublic';

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

/* Holiday. */
$lang->resource->holiday = new stdclass();
$lang->resource->holiday->create = 'createAction';
$lang->resource->holiday->edit   = 'editAction';
$lang->resource->holiday->delete = 'deleteAction';
$lang->resource->holiday->browse = 'browse';
$lang->resource->holiday->import = 'importAction';

$lang->holiday->methodOrder[0]  = 'browse';
$lang->holiday->methodOrder[5]  = 'create';
$lang->holiday->methodOrder[10] = 'edit';
$lang->holiday->methodOrder[15] = 'delete';
$lang->holiday->methodOrder[20] = 'import';

/* Action. */
$lang->resource->action = new stdclass();
$lang->resource->action->trash    = 'trashAction';
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
$lang->resource->backup->restore     = 'restoreAction';
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
$lang->resource->cron->index       = 'index';
$lang->resource->cron->turnon      = 'turnon';
$lang->resource->cron->create      = 'createAction';
$lang->resource->cron->edit        = 'edit';
$lang->resource->cron->toggle      = 'toggle';
$lang->resource->cron->delete      = 'delete';
$lang->resource->cron->openProcess = 'restart';

$lang->cron->methodOrder[5]  = 'index';
$lang->cron->methodOrder[10] = 'turnon';
$lang->cron->methodOrder[15] = 'create';
$lang->cron->methodOrder[20] = 'edit';
$lang->cron->methodOrder[25] = 'toggle';
$lang->cron->methodOrder[30] = 'delete';
$lang->cron->methodOrder[35] = 'openProcess';

$lang->resource->dev = new stdclass();
$lang->resource->dev->api = 'api';
$lang->resource->dev->db  = 'db';

$lang->dev->methodOrder[5]  = 'api';
$lang->dev->methodOrder[10] = 'db';

$lang->resource->editor = new stdclass();
$lang->resource->editor->index   = 'index';
$lang->resource->editor->extend  = 'extend';
$lang->resource->editor->edit    = 'edit';
$lang->resource->editor->newPage = 'newPage';
$lang->resource->editor->save    = 'save';
$lang->resource->editor->delete  = 'delete';

$lang->editor->methodOrder[5]  = 'index';
$lang->editor->methodOrder[10] = 'extend';
$lang->editor->methodOrder[15] = 'edit';
$lang->editor->methodOrder[20] = 'newPage';
$lang->editor->methodOrder[25] = 'save';
$lang->editor->methodOrder[30] = 'delete';

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
$lang->resource->codereview         = new stdclass();
$lang->resource->repocode           = new stdclass();
$lang->resource->deploy             = new stdclass();

global $config;
$inUpgrade = (defined('IN_UPGRADE') and IN_UPGRADE);
if(!$inUpgrade)
{
    if(!$config->URAndSR)
    {
        unset($lang->resource->product->requirement);
        unset($lang->resource->story->linkStory);
        unset($lang->resource->requirement);
    }
    if($config->systemMode == 'light')
    {
        unset($lang->resource->program);
        unset($lang->resource->project->programTitle);
        unset($lang->resource->product->manageLine);
    }
    if(!helper::hasFeature('waterfall') and !helper::hasFeature('waterfallplus'))
    {
        unset($lang->resource->design);
        unset($lang->resource->programplan);
        unset($lang->resource->stage);
    }
    if(!helper::hasFeature('product_track')) unset($lang->resource->product->track);
    if(!helper::hasFeature('product_roadmap')) unset($lang->resource->product->roadmap);
    if((!helper::hasFeature('waterfall_track') and !helper::hasFeature('waterfallplus_track')) or $config->edition != 'max')  unset($lang->resource->projectstory->track);
    if(!helper::hasFeature('devops'))
    {
        unset($lang->resource->repo);
        unset($lang->resource->svn);
        unset($lang->resource->git);
        unset($lang->resource->app);
        unset($lang->resource->ci);
        unset($lang->resource->compile);
        unset($lang->resource->jenkins);
        unset($lang->resource->job);
        unset($lang->resource->gitlab);
        unset($lang->resource->gogs);
        unset($lang->resource->gitea);
        unset($lang->resource->sonarqube);
        unset($lang->resource->mr);
    }
    if(!helper::hasFeature('kanban')) unset($lang->resource->kanban);

    if(!$config->systemScore) unset($lang->resource->my->score);
}

include (dirname(__FILE__) . '/changelog.php');
