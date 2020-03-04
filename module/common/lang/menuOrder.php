<?php
/* Sort of main menu. */
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'ci';
$lang->menuOrder[30] = 'doc';
$lang->menuOrder[35] = 'report';
$lang->menuOrder[40] = 'company';
$lang->menuOrder[45] = 'admin';

/* index menu order. */
$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

/* my menu order. */
$lang->my->menuOrder[5]  = 'index';
$lang->my->menuOrder[10] = 'calendar';
$lang->my->menuOrder[15] = 'task';
$lang->my->menuOrder[20] = 'bug';
$lang->my->menuOrder[25] = 'testtask';
$lang->my->menuOrder[30] = 'story';
$lang->my->menuOrder[35] = 'myProject';
$lang->my->menuOrder[40] = 'dynamic';
$lang->my->menuOrder[45] = 'profile';
$lang->my->menuOrder[50] = 'changePassword';
$lang->my->menuOrder[55] = 'score';
$lang->todo->menuOrder   = $lang->my->menuOrder;

/* product menu order. */
$lang->product->menuOrder[5]  = 'story';
$lang->product->menuOrder[10] = 'plan';
$lang->product->menuOrder[15] = 'release';
$lang->product->menuOrder[20] = 'roadmap';
$lang->product->menuOrder[25] = 'project';
$lang->product->menuOrder[30] = 'dynamic';
$lang->product->menuOrder[35] = 'doc';
$lang->product->menuOrder[40] = 'branch';
$lang->product->menuOrder[45] = 'module';
$lang->product->menuOrder[50] = 'view';
$lang->product->menuOrder[55] = 'create';
$lang->product->menuOrder[60] = 'all';
$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;
$lang->branch->menuOrder      = $lang->product->menuOrder;

/* project menu order. */
$lang->project->menuOrder[5]  = 'task';
$lang->project->menuOrder[10] = 'kanban';
$lang->project->menuOrder[15] = 'burn';
$lang->project->menuOrder[20] = 'list';
$lang->project->menuOrder[25] = 'story';
$lang->project->menuOrder[30] = 'qa';
$lang->project->menuOrder[35] = 'doc';
$lang->project->menuOrder[40] = 'team';
$lang->project->menuOrder[45] = 'action';
$lang->project->menuOrder[50] = 'product';
$lang->project->menuOrder[55] = 'view';
$lang->task->menuOrder        = $lang->project->menuOrder;
$lang->build->menuOrder       = $lang->project->menuOrder;

/* qa menu order. */
$lang->qa->menuOrder[5]      = 'product';
$lang->qa->menuOrder[10]     = 'index';
$lang->qa->menuOrder[15]     = 'bug';
$lang->qa->menuOrder[20]     = 'testcase';
$lang->qa->menuOrder[25]     = 'testtask';
$lang->qa->menuOrder[30]     = 'report';
$lang->qa->menuOrder[35]     = 'testsuite';
$lang->qa->menuOrder[40]     = 'caselib';
$lang->bug->menuOrder        = $lang->qa->menuOrder;
$lang->testcase->menuOrder   = $lang->bug->menuOrder;
$lang->testtask->menuOrder   = $lang->testcase->menuOrder;
$lang->testsuite->menuOrder  = $lang->testcase->menuOrder;
$lang->caselib->menuOrder    = $lang->testcase->menuOrder;
$lang->testreport->menuOrder = $lang->testcase->menuOrder;

$lang->ci->menuOrder[5]  = 'code';
$lang->ci->menuOrder[10] = 'build';
$lang->ci->menuOrder[15] = 'jenkins';
$lang->ci->menuOrder[20] = 'maintain';
$lang->ci->menuOrder[25] = 'rules';

$lang->repo->menuOrder    = $lang->ci->menuOrder;
$lang->jenkins->menuOrder = $lang->ci->menuOrder;

/* doc menu order. */
$lang->doc->menuOrder[5]  = 'list';
$lang->doc->menuOrder[10] = 'product';
$lang->doc->menuOrder[15] = 'project';
$lang->doc->menuOrder[20] = 'custom';
$lang->doc->menuOrder[25] = 'index';
$lang->doc->menuOrder[30] = 'create';

/* report menu order. */
$lang->report->menuOrder[5]  = 'annual';
$lang->report->menuOrder[10] = 'product';
$lang->report->menuOrder[15] = 'prj';
$lang->report->menuOrder[20] = 'test';
$lang->report->menuOrder[25] = 'staff';

/* company menu order. */
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'dynamic';
$lang->company->menuOrder[25] = 'view';
$lang->company->menuOrder[30] = 'addGroup';
$lang->company->menuOrder[35] = 'batchAddUser';
$lang->company->menuOrder[40] = 'addUser';
$lang->dept->menuOrder        = $lang->company->menuOrder;
$lang->group->menuOrder       = $lang->company->menuOrder;
$lang->user->menuOrder        = $lang->company->menuOrder;

/* admin menu order. */
$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[10] = 'message';
$lang->admin->menuOrder[15] = 'custom';
$lang->admin->menuOrder[20] = 'sso';
$lang->admin->menuOrder[25] = 'extension';
$lang->admin->menuOrder[30] = 'dev';
$lang->admin->menuOrder[35] = 'translate';
$lang->admin->menuOrder[40] = 'data';
$lang->admin->menuOrder[45] = 'safe';
$lang->admin->menuOrder[50] = 'system';

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
$lang->admin->subMenuOrder->data[5]     = 'backup';
$lang->admin->subMenuOrder->data[10]    = 'trash';
$lang->admin->subMenuOrder->system[5]   = 'cron';
$lang->admin->subMenuOrder->system[10]  = 'timezone';

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
