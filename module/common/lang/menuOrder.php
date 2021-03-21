<?php
$lang->mainNav->menuOrder[5]  = 'my';
if($config->systemMode == 'new') $lang->mainNav->menuOrder[10] = 'program';
$lang->mainNav->menuOrder[15] = 'product';
$lang->mainNav->menuOrder[20] = 'project';
$lang->mainNav->menuOrder[21] = 'execution';
$lang->mainNav->menuOrder[23] = 'qa';
$lang->mainNav->menuOrder[25] = 'repo';
$lang->mainNav->menuOrder[30] = 'doc';
$lang->mainNav->menuOrder[35] = 'report';
$lang->mainNav->menuOrder[60] = 'system';
$lang->mainNav->menuOrder[70] = 'admin';

/* Waterfall menu order. */
$lang->waterfall->menuOrder[5]  = 'index';
$lang->waterfall->menuOrder[10] = 'execution';
$lang->waterfall->menuOrder[15] = 'programplan';
$lang->waterfall->menuOrder[20] = 'projectstory';
$lang->waterfall->menuOrder[25] = 'design';
$lang->waterfall->menuOrder[30] = 'ci';
$lang->waterfall->menuOrder[35] = 'track';
$lang->waterfall->menuOrder[38] = 'review';
$lang->waterfall->menuOrder[39] = 'cm';
$lang->waterfall->menuOrder[40] = 'qa';
$lang->waterfall->menuOrder[45] = 'doc';
$lang->waterfall->menuOrder[50] = 'build';
$lang->waterfall->menuOrder[55] = 'projectrelease';
$lang->waterfall->menuOrder[60] = 'weekly';
$lang->waterfall->menuOrder[65] = 'other';
$lang->waterfall->menuOrder[68] = 'dynamic';
$lang->waterfall->menuOrder[70] = 'projectsetting';

/* Sort of main menu. */
$lang->scrum->menuOrder[5]  = 'index';
$lang->scrum->menuOrder[10] = 'execution';
$lang->scrum->menuOrder[15] = 'projectstory';
$lang->scrum->menuOrder[20] = 'qa';
$lang->scrum->menuOrder[25] = 'ci';
$lang->scrum->menuOrder[30] = 'doc';
$lang->scrum->menuOrder[35] = 'build';
$lang->scrum->menuOrder[40] = 'projectrelease';
$lang->scrum->menuOrder[45] = 'other';
$lang->scrum->menuOrder[48] = 'dynamic';
$lang->scrum->menuOrder[50] = 'projectsetting';

/* Index menu order. */
$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

/* My menu order. */
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'myWork';
$lang->my->menuOrder[15] = 'myProject';
$lang->my->menuOrder[20] = 'myExecution';
$lang->my->menuOrder[25] = 'contribute';
$lang->my->menuOrder[30] = 'score';
$lang->my->menuOrder[35] = 'dynamic';
$lang->my->menuOrder[40] = 'follow';
$lang->my->menuOrder[45] = 'contacts';
$lang->todo->menuOrder = $lang->my->menuOrder;

/* Program menu order. */
$lang->program->menuOrder[5]  = 'product';
$lang->program->menuOrder[10] = 'project';
$lang->program->menuOrder[15] = 'personnel';
$lang->program->menuOrder[20] = 'stakeholder';

/* Product menu order. */
$lang->product->menuOrder[5]  = 'dashboard';
$lang->product->menuOrder[10] = 'story';
$lang->product->menuOrder[15] = 'plan';
$lang->product->menuOrder[20] = 'project';
$lang->product->menuOrder[25] = 'release';
$lang->product->menuOrder[30] = 'roadmap';
$lang->product->menuOrder[35] = 'requirement';
$lang->product->menuOrder[40] = 'track';
$lang->product->menuOrder[45] = 'doc';
$lang->product->menuOrder[50] = 'dynamic';
$lang->product->menuOrder[55] = 'setting';
$lang->product->menuOrder[60] = 'create';
$lang->product->menuOrder[65] = 'all';

$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;
$lang->branch->menuOrder      = $lang->product->menuOrder;

/* Execution menu order. */
$lang->execution->menuOrder[5]  = 'task';
$lang->execution->menuOrder[10] = 'kanban';
$lang->execution->menuOrder[15] = 'burn';
$lang->execution->menuOrder[20] = 'view';
$lang->execution->menuOrder[25] = 'story';
$lang->execution->menuOrder[30] = 'qa';
$lang->execution->menuOrder[35] = 'repo';
$lang->execution->menuOrder[40] = 'ci';
$lang->execution->menuOrder[45] = 'doc';
$lang->execution->menuOrder[50] = 'build';
$lang->execution->menuOrder[55] = 'release';
$lang->execution->menuOrder[60] = 'action';
$lang->execution->menuOrder[65] = 'setting';

/* qa menu order. */
$lang->qa->menuOrder[5]      = 'product';
$lang->qa->menuOrder[10]     = 'index';
$lang->qa->menuOrder[15]     = 'bug';
$lang->qa->menuOrder[20]     = 'testcase';
$lang->qa->menuOrder[25]     = 'testtask';
$lang->qa->menuOrder[30]     = 'report';
$lang->qa->menuOrder[35]     = 'testsuite';
$lang->qa->menuOrder[40]     = 'caselib';

$lang->devops->menuOrder[5]  = 'code';
$lang->devops->menuOrder[10] = 'build';
$lang->devops->menuOrder[15] = 'jenkins';
$lang->devops->menuOrder[20] = 'maintain';
$lang->devops->menuOrder[25] = 'rules';

/* Doc menu order. */
$lang->doc->menuOrder[5]  = 'list';
$lang->doc->menuOrder[10] = 'product';
$lang->doc->menuOrder[15] = 'project';
$lang->doc->menuOrder[20] = 'custom';
$lang->doc->menuOrder[25] = 'index';
$lang->doc->menuOrder[30] = 'create';

/* Report menu order. */
$lang->report->menuOrder[5]  = 'annual';
$lang->report->menuOrder[10] = 'product';
$lang->report->menuOrder[15] = 'execution';
$lang->report->menuOrder[20] = 'test';
$lang->report->menuOrder[25] = 'staff';

/* Company menu order. */
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'addGroup';
$lang->company->menuOrder[25] = 'batchAddUser';
$lang->company->menuOrder[30] = 'addUser';

/* System menu order. */
$lang->system->menuOrder[5]  = 'team';
$lang->system->menuOrder[10] = 'calendar';
$lang->system->menuOrder[15] = 'dynamic';
$lang->system->menuOrder[20] = 'view';

/* Admin menu order. */
$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[10] = 'company';
$lang->admin->menuOrder[15] = 'model';
$lang->admin->menuOrder[20] = 'custom';
$lang->admin->menuOrder[25] = 'message';
$lang->admin->menuOrder[30] = 'extension';
$lang->admin->menuOrder[35] = 'dev';
$lang->admin->menuOrder[40] = 'system';

$lang->admin->subMenuOrder = new stdclass();
$lang->admin->subMenuOrder->message[5]  = 'mail';
$lang->admin->subMenuOrder->message[10] = 'webhook';
$lang->admin->subMenuOrder->message[15] = 'browser';
$lang->admin->subMenuOrder->message[20] = 'setting';
$lang->admin->subMenuOrder->sso[5]      = 'ranzhi';
$lang->admin->subMenuOrder->dev[5]      = 'api';
$lang->admin->subMenuOrder->dev[10]     = 'db';
$lang->admin->subMenuOrder->dev[15]     = 'editor';
$lang->admin->subMenuOrder->dev[20]     = 'entry';
$lang->admin->subMenuOrder->system[5]   = 'data';
$lang->admin->subMenuOrder->system[10]  = 'safe';
$lang->admin->subMenuOrder->system[15]  = 'cron';
$lang->admin->subMenuOrder->system[20]  = 'timezone';
$lang->admin->subMenuOrder->system[25]  = 'buildIndex';

$lang->convert->menuOrder   = $lang->admin->menuOrder;
$lang->upgrade->menuOrder   = $lang->admin->menuOrder;
$lang->action->menuOrder    = $lang->admin->menuOrder;
$lang->backup->menuOrder    = $lang->admin->menuOrder;
$lang->cron->menuOrder      = $lang->admin->menuOrder;
$lang->extension->menuOrder = $lang->admin->menuOrder;
$lang->custom->menuOrder    = $lang->admin->menuOrder;
$lang->mail->menuOrder      = $lang->admin->menuOrder;
$lang->dev->menuOrder       = $lang->admin->menuOrder;
$lang->entry->menuOrder     = $lang->admin->menuOrder;
$lang->webhook->menuOrder   = $lang->admin->menuOrder;
