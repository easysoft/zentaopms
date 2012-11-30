<?php
/* Sort of main menu. */
$lang->menuOrder[5]  = 'my';
$lang->menuOrder[10] = 'product';
$lang->menuOrder[15] = 'project';
$lang->menuOrder[20] = 'qa';
$lang->menuOrder[25] = 'doc';
$lang->menuOrder[30] = 'report';
$lang->menuOrder[35] = 'webapp';
$lang->menuOrder[40] = 'company';
$lang->menuOrder[45] = 'admin';

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
$lang->product->menuOrder[5]  = 'story';
$lang->product->menuOrder[10] = 'dynamic';
$lang->product->menuOrder[15] = 'plan';
$lang->product->menuOrder[20] = 'release';
$lang->product->menuOrder[25] = 'roadmap';
$lang->product->menuOrder[30] = 'doc';
$lang->product->menuOrder[35] = 'project';
$lang->product->menuOrder[40] = 'view';
$lang->product->menuOrder[45] = 'module';
$lang->product->menuOrder[50] = 'order';
$lang->product->menuOrder[55] = 'create';
$lang->product->menuOrder[60] = 'all';
$lang->story->menuOrder       = $lang->product->menuOrder;
$lang->productplan->menuOrder = $lang->product->menuOrder;
$lang->release->menuOrder     = $lang->product->menuOrder;

/* project menu order. */
$lang->project->menuOrder[5]  = 'task';
$lang->project->menuOrder[10] = 'story';
$lang->project->menuOrder[15] = 'bug';
$lang->project->menuOrder[20] = 'build';
$lang->project->menuOrder[25] = 'testtask';
$lang->project->menuOrder[30] = 'burn';
$lang->project->menuOrder[35] = 'team';
$lang->project->menuOrder[40] = 'dynamic';
$lang->project->menuOrder[45] = 'doc';
$lang->project->menuOrder[50] = 'product';
$lang->project->menuOrder[55] = 'linkstory';
$lang->project->menuOrder[60] = 'view';
$lang->project->menuOrder[65] = 'order';
$lang->project->menuOrder[70] = 'create';
$lang->project->menuOrder[75] = 'copy';
$lang->project->menuOrder[80] = 'all';
$lang->task->menuOrder        = $lang->project->menuOrder;
$lang->build->menuOrder       = $lang->project->menuOrder;

/* bug menu order. */
$lang->bug->menuOrder[0]  = 'product';
$lang->bug->menuOrder[5]  = 'bug';
$lang->bug->menuOrder[10] = 'testcase';
$lang->bug->menuOrder[15] = 'testtask';

/* testcase menu order. */
$lang->testcase->menuOrder[0]  = 'product';
$lang->testcase->menuOrder[5]  = 'bug';
$lang->testcase->menuOrder[10] = 'testcase';
$lang->testcase->menuOrder[15] = 'testtask';
$lang->testtask->menuOrder     = $lang->testcase->menuOrder;

/* doc menu order. */
$lang->doc->menuOrder[5]  = 'browse';
$lang->doc->menuOrder[10] = 'edit';
$lang->doc->menuOrder[15] = 'module';
$lang->doc->menuOrder[20] = 'delete';
$lang->doc->menuOrder[25] = 'create';

/* report menu order. */
$lang->report->menuOrder[5]  = 'product';
$lang->report->menuOrder[10] = 'prj';
$lang->report->menuOrder[15] = 'test';
$lang->report->menuOrder[20] = 'staff';

/* company menu order. */
$lang->company->menuOrder[0]  = 'name';
$lang->company->menuOrder[5]  = 'browseUser';
$lang->company->menuOrder[10] = 'dept';
$lang->company->menuOrder[15] = 'browseGroup';
$lang->company->menuOrder[20] = 'edit';
$lang->company->menuOrder[25] = 'dynamic';
$lang->company->menuOrder[30] = 'addGroup';
$lang->company->menuOrder[35] = 'addUser';
$lang->dept->menuOrder        = $lang->company->menuOrder;
$lang->group->menuOrder       = $lang->company->menuOrder;
$lang->user->menuOrder        = $lang->company->menuOrder;

/* admin menu order. */
$lang->admin->menuOrder[5]  = 'index';
$lang->admin->menuOrder[10] = 'extension';
$lang->admin->menuOrder[15] = 'editor';
$lang->admin->menuOrder[20] = 'mail';
$lang->admin->menuOrder[25] = 'clearData';
$lang->admin->menuOrder[30] = 'convert';
$lang->admin->menuOrder[35] = 'trashes';
$lang->convert->menuOrder   = $lang->admin->menuOrder;
$lang->upgrade->menuOrder   = $lang->admin->menuOrder;
$lang->action->menuOrder    = $lang->admin->menuOrder;
$lang->extension->menuOrder = $lang->admin->menuOrder;
$lang->editor->menuOrder    = $lang->admin->menuOrder;
$lang->mail->menuOrder      = $lang->admin->menuOrder;
