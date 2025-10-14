#!/usr/bin/env php
<?php

/**

title=运营界面撤销评审目标
timeout=0
cid=0

- 撤销评审
 - 测试结果 @目标撤销评审成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/changestatusinlite.ui.class.php';

$project = zenData('project');
$project->id->range('1');
$project->model->range('kanban');
$project->type->range('project');
$project->name->range('运营项目');
$project->hasProduct->range('0');
$project->acl->range('open');
$project->vision->range('lite');
$project->gen(1);

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('运营项目');
$product->shadow->range('1');
$product->type->range('normal');
$product->acl->range('open');
$product->vision->range('lite');
$product->gen(1);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1');
$projectproduct->product->range('1');
$projectproduct->branch->range('0');
$projectproduct->plan->range('0');
$projectproduct->gen(1);

$team = ZenData('team');
$team->id->range('1');
$team->root->range('1');
$team->type->range('project');
$team->account->range('admin');
$team->gen(1);

$story = ZenData('story');
$story->id->range('1');
$story->vision->range('lite');
$story->parent->range('0');
$story->isparent->range('0');
$story->root->range('1');
$story->path->range(',1,');
$story->product->range('1');
$story->title->range('目标01');
$story->type->range('story');
$story->status->range('reviewing');
$story->stage->range('projected');
$story->version->range('1');
$story->openedBy->range('admin');
$story->gen(1);

$storyspec = ZenData('storyspec');
$storyspec->story->range('1');
$storyspec->version->range('1');
$storyspec->title->range('目标01');
$storyspec->gen(1);

$storyreview = ZenData('storyreview');
$storyreview->story->range('1');
$storyreview->version->range('1');
$storyreview->reviewer->range('admin');
$storyreview->result->range('');
$storyreview->gen(1);

$projectstory = ZenData('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->branch->range('0');
$projectstory->story->range('1');
$projectstory->version->range('1');
$projectstory->order->range('1');
$projectstory->gen(1);

$storyUrl = array(
    'storyID'   => '1',
    'projectID' => '1'
);
$tester = new changeStatus();
$tester->login();

r($tester->revokeStory($storyUrl)) && p('message,status') && e('目标撤销评审成功,SUCCESS');//撤销评审
$tester->closeBrowser();