#!/usr/bin/env php
<?php

/**

title=运营界面创建目标测试
timeout=0
cid=0

- 目标名称必填校验
 - 测试结果 @目标名称必填提示信息正确
 - 最终测试状态 @SUCCESS
- 评审人必填校验
 - 测试结果 @评审人必填提示信息正确
 - 最终测试状态 @SUCCESS
- 正常创建目标校验
 - 测试结果 @创建目标成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/createstoryinlite.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->shadow->range('0');
$product->bind->range('0');
$product->acl->range('open');
$product->createdBy->range('admin');
$product->vision->range('lite');
$product->gen(1);

$story = zenData('story');
$story->gen(0);

$storyspec = zenData('storyspec');
$storyspec->gen(0);

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

$projectstory = zenData('projectstory')->gen(0);

$action = zenData('action');
$action->gen(0);

$tester = new createStoryInLiteTester();
$tester->login();

$storys = array(
    'null'  => '',
    'story' => '运营界面目标'
);

$reviewers = array(
    'null'  => array(),
    'admin' => array('admin')
);

r($tester->createDefault($storys['null'],  $reviewers['admin'])) && p('message,status') && e('创建目标页面名称为空提示正确,SUCCESS'); // 名称必填校验，创建失败
r($tester->createDefault($storys['story'], $reviewers['null']))  && p('message,status') && e('创建目标页面评审人为空提示正确,SUCCESS'); // 评审人必填校验，创建失败
r($tester->createDefault($storys['story'], $reviewers['admin'])) && p('message,status') && e('创建运营界面目标成功,SUCCESS'); // 创建成功

$tester->closeBrowser();
