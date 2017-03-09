<?php
/* Sort of main menu. */
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'doc';
$lang->menuOrder[30] = 'report';
$lang->menuOrder[35] = 'company';
$lang->menuOrder[40] = 'admin';

/* index menu order. */
$lang->index->menuOrder[5]  = 'product';
$lang->index->menuOrder[10] = 'project';

/* my menu order. */
$lang->my->menuOrder[5]  = 'account';
$lang->my->menuOrder[10] = 'index';
$lang->my->menuOrder[15] = 'todo';
$lang->my->menuOrder[20] = 'task';
$lang->my->menuOrder[25] = 'bug';
$lang->my->menuOrder[30] = 'testtask';
$lang->my->menuOrder[35] = 'story';
$lang->my->menuOrder[40] = 'myProject';
$lang->my->menuOrder[45] = 'dynamic';
$lang->my->menuOrder[50] = 'profile';
$lang->my->menuOrder[55] = 'changePassword';
$lang->todo->menuOrder   = $lang->my->menuOrder;

/* product menu order. */
$lang->product->menuOrder[5]  = 'list';
$lang->product->menuOrder[10] = 'index';
$lang->product->menuOrder[15] = 'story';
$lang->product->menuOrder[20] = 'dynamic';
$lang->product->menuOrder[25] = 'plan';
$lang->product->menuOrder[30] = 'release';
$lang->product->menuOrder[35] = 'roadmap';
$lang->product->menuOrder[40] = 'doc';
$lang->product->menuOrder[45] = 'project';
$lang->product->menuOrder[50] = 'branch';
$lang->product->menuOrder[55] = 'module';
$lang->product->menuOrder[60] = 'view';
$lang->product->menuOrder[65] = 'create';
$lang->product->menuOrder[70] = 'all';
$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;
$lang->branch->menuOrder      = $lang->product->menuOrder;

/* project menu order. */
$lang->project->menuOrder[5]  = 'list';
$lang->project->menuOrder[10] = 'index';
$lang->project->menuOrder[15] = 'task';
$lang->project->menuOrder[20] = 'story';
$lang->project->menuOrder[25] = 'bug';
$lang->project->menuOrder[30] = 'build';
$lang->project->menuOrder[35] = 'testtask';
$lang->project->menuOrder[40] = 'team';
$lang->project->menuOrder[45] = 'dynamic';
$lang->project->menuOrder[50] = 'doc';
$lang->project->menuOrder[55] = 'product';
$lang->project->menuOrder[60] = 'view';
$lang->project->menuOrder[65] = 'create';
$lang->project->menuOrder[70] = 'all';
$lang->task->menuOrder        = $lang->project->menuOrder;
$lang->build->menuOrder       = $lang->project->menuOrder;

/* qa menu order. */
$lang->qa->menuOrder[5]      = 'product';
$lang->qa->menuOrder[10]     = 'index';
$lang->qa->menuOrder[15]     = 'bug';
$lang->qa->menuOrder[20]     = 'testcase';
$lang->qa->menuOrder[25]     = 'testtask';
$lang->qa->menuOrder[30]     = 'testsuite';
$lang->qa->menuOrder[35]     = 'testreport';
$lang->bug->menuOrder        = $lang->qa->menuOrder;
$lang->testcase->menuOrder   = $lang->bug->menuOrder;
$lang->testtask->menuOrder   = $lang->testcase->menuOrder;
$lang->testsuite->menuOrder  = $lang->testcase->menuOrder;
$lang->testreport->menuOrder = $lang->testcase->menuOrder;

/* doc menu order. */
$lang->doc->menuOrder[5]  = 'list';
$lang->doc->menuOrder[10] = 'product';
$lang->doc->menuOrder[15] = 'project';
$lang->doc->menuOrder[20] = 'custom';
$lang->doc->menuOrder[25] = 'index';
$lang->doc->menuOrder[30] = 'create';

/* report menu order. */
$lang->report->menuOrder[5]  = 'product';
$lang->report->menuOrder[10] = 'prj';
$lang->report->menuOrder[15] = 'test';
$lang->report->menuOrder[20] = 'staff';

/* company menu order. */
$lang->company->menuOrder[5]  = 'name';
$lang->company->menuOrder[10]  = 'browseUser';
$lang->company->menuOrder[15] = 'dept';
$lang->company->menuOrder[20] = 'browseGroup';
$lang->company->menuOrder[25] = 'view';
$lang->company->menuOrder[30] = 'dynamic';
$lang->company->menuOrder[35] = 'addGroup';
$lang->company->menuOrder[40] = 'batchAddUser';
$lang->company->menuOrder[45] = 'addUser';
$lang->dept->menuOrder        = $lang->company->menuOrder;
$lang->group->menuOrder       = $lang->company->menuOrder;
$lang->user->menuOrder        = $lang->company->menuOrder;

/* admin menu order. */
$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[10] = 'extension';
$lang->admin->menuOrder[15] = 'custom';
$lang->admin->menuOrder[20] = 'mail';
$lang->admin->menuOrder[25] = 'custom';
$lang->admin->menuOrder[30] = 'convert';
$lang->admin->menuOrder[35] = 'cron';
$lang->admin->menuOrder[40] = 'backup';
$lang->admin->menuOrder[45] = 'dev';
$lang->admin->menuOrder[50] = 'safe';
$lang->admin->menuOrder[55] = 'sso';
$lang->admin->menuOrder[60] = 'trashes';
$lang->convert->menuOrder   = $lang->admin->menuOrder;
$lang->upgrade->menuOrder   = $lang->admin->menuOrder;
$lang->action->menuOrder    = $lang->admin->menuOrder;
$lang->backup->menuOrder    = $lang->admin->menuOrder;
$lang->cron->menuOrder      = $lang->admin->menuOrder;
$lang->extension->menuOrder = $lang->admin->menuOrder;
$lang->custom->menuOrder    = $lang->admin->menuOrder;
$lang->editor->menuOrder    = $lang->admin->menuOrder;
$lang->mail->menuOrder      = $lang->admin->menuOrder;
$lang->dev->menuOrder       = $lang->admin->menuOrder;
