#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=目标详情页测试
timeout=0
cid=90

*/
chdir (__DIR__);
include '../lib/ui/viewinlite.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('1');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('lite');
$product->gen(1);

$story = zenData('story');
$story->id->range('1');
$story->vision->range('lite');
$story->root->range('1');
$story->path->range('`,1,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->source->range('[]');
$story->title->range('运营界面目标1');
$story->type->range('story');
$story->stage->range('wait');
$story->status->range('active');
$story->openedBy->range('admin');
$story->version->range('1');
$story->assignedTo->range('[]');
$story->reviewedBy->range('[]');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(1);

$storyspec = zenData('storyspec');
$storyspec->story->range('1');
$storyspec->version->range('1');
$storyspec->title->range('运营界面目标1');
$storyspec->gen(1);

$storyreview = zenData('storyreview');
$storyreview->gen(0);

$project = zenData('project');
$project->id->range('1');
$project->model->range('kanban');
$project->type->range('project');
$project->path->range('`,1,`');
$project->team->range('运营项目1');
$project->acl->range('open');
$project->vision->range('lite');
$project->name->range('运营项目1');
$project->gen(1);

$projectadmin = zenData('projectadmin');
$projectadmin->group->range('1');
$projectadmin->account->range('admin');
$projectadmin->projects->range('`,`1`,`');
$projectadmin->gen(1);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1');
$projectproduct->product->range('1');
$projectproduct->gen(1);

$action = zenData('action')->gen(0);

$projectstory = zenData('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->branch->range('0');
$projectstory->story->range('1');
$projectstory->version->range('1');
$projectstory->order->range('1');
$projectstory->gen(1);

$team = zenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->limited->range('no');
$team->gen(1);

$tester = new viewInLiteTester();
$tester->login();


r($tester->viewInLite()) && p('message') && e('目标详情页内容正确');

$tester->closeBrowser();
