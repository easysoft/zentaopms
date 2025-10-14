#!/usr/bin/env php
<?php

/**

title=运营界面批量创建目标
timeout=0
- 目标名称必填校验
 - 测试结果 @目标名称必填提示信息正确
 - 最终测试状态 @SUCCESS
- 评审人必填校验
 - 测试结果 @评审人必填提示信息正确
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/ui/batchcreateinlite.ui.class.php';

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

$projectadmin = ZenData('projectadmin');
$projectadmin->group->range('1');
$projectadmin->account->range('admin');
$projectadmin->projects->range('1');
$projectadmin->gen(1);

zendata('story')->loadYaml('story', false, 2)->gen(0);
zendata('storyspec')->loadYaml('storyspec', false, 2)->gen(0);
zendata('storyreview')->loadYaml('storyreview', false, 2)->gen(0);

$storyUrl = array(
    'productID' => '1',
    'branch'    => '',
    'moduleID'  => '0',
    'storyID'   => '0',
    'project'   => 1,
    'plan'      => '0',
    'storyType' => 'story'
);
$project = array(
    'projectID' => 1
);

$tester = new batchCreateStory();
$tester->login();

$story = new stdClass();
$story->name = '';
r($tester->batchCreateStory($project, $storyUrl, $story)) && p('message,status') && e('目标名称必填提示信息正确,SUCCESS');//目标名称必填校验

$story->name = '目标A';
r($tester->batchCreateStory($project, $storyUrl, $story)) && p('message,status') && e('评审人必填提示信息正确,SUCCESS');//评审人必填校验

$tester->closeBrowser();
