#!/usr/bin/env php
<?php

/**
title=运营界面评审目标
timeout=0
cid=0

- 评审通过
 - 测试结果 @评审目标成功
 - 最终测试状态 @SUCCESS
- 评审需求不明确
 - 测试结果 @评审目标成功
 - 最终测试状态 @SUCCESS
- 评审拒绝
 - 测试结果 @评审目标成功
 - 最终测试状态 @SUCCESS
- 评审撤销变更
 - 测试结果 @评审目标成功
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
$story->id->range('1-4');
$story->vision->range('lite');
$story->parent->range('0');
$story->isparent->range('0');
$story->root->range('1');
$story->path->range('1-4')->prefix(',')->postfix(',');
$story->grade->range('1');
$story->product->range('1');
$story->title->range('1-3, 变更')->prefix('目标');
$story->type->range('story');
$story->status->range('reviewing');
$story->stage->range('projected');
$story->version->range('1{3}, 2');
$story->openedBy->range('admin');
$story->closedBy->range('[]');
$story->closedReason->range('[]');
$story->gen(4);

$storyspec = ZenData('storyspec');
$storyspec->story->range('1-4, 4');
$storyspec->version->range('1{4}, 2');
$storyspec->title->range('1-4, 变更')->prefix('目标');
$storyspec->gen(5);

$storyreview = ZenData('storyreview');
$storyreview->story->range('1-4');
$storyreview->version->range('1{3}, 2');
$storyreview->reviewer->range('admin');
$storyreview->result->range('[]');
$storyreview->gen(4);

$projectstory = ZenData('projectstory');
$projectstory->project->range('1');
$projectstory->product->range('1');
$projectstory->story->range('1-4');
$projectstory->version->range('1');
$projectstory->gen(4);

$history = ZenData('history')->gen(0);
$action = ZenData('action')->gen(0);

$result = array(
    'pass'    => 'pass',
    'revert'  => 'revert',
    'clarify' => 'clarify',
    'reject'  => 'reject'
);

$status  = array(
    'active' => 'active',
    'draft'  => 'draft',
    'closed' => 'closed'
);

$tester = new changeStatus();
$tester->login();

r($tester->reviewStory('1', $result['pass'], $status['active']))   && p('message,status') && e('评审目标成功,SUCCESS');//评审通过
r($tester->reviewStory('2', $result['clarify'], $status['draft'])) && p('message,status') && e('评审目标成功,SUCCESS');//评审需求不明确
r($tester->reviewStory('3', $result['reject'], $status['closed'])) && p('message,status') && e('评审目标成功,SUCCESS');//评审拒绝
r($tester->reviewStory('4', $result['revert'], $status['active'])) && p('message,status') && e('评审目标成功,SUCCESS');//评审撤销变更

$tester->closeBrowser();
