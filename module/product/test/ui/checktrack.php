#!/usr/bin/env php
<?php

/**
title=检查产品矩阵数据准确性
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/track.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->program->range('0');
$product->name->range('产品1');
$product->PO->range('admin');
$product->status->range('normal');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-2');
$project->project->range('0,1');
$project->model->range('scrum,[]');
$project->type->range('project,sprint');
$project->parent->range('0,1');
$project->auth->range('extend');
$project->storytype->range('story');
$project->path->range('`,1,`,`,1,2,`');
$project->grade->range('1');
$project->name->range('项目1,迭代1');
$project->hasProduct->range('1');
$project->status->range('doing');
$project->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-6');
$projectProduct->product->range('1');
$projectProduct->gen(6);

$story = zenData('story');
$story->id->range('1-4');
$story->parent->range('0,1,2,3');
$story->isParent->range('1{3},0{1}');
$story->root->range('1');
$story->path->range('`,1,`,`,1,2,`,`,1,2,3,`,`,1,2,3,4,`');
$story->grade->range('1{3},2{1}');
$story->product->range('1');
$story->title->range('业需01,用需01,研需01,子需求01');
$story->type->range('epic,requirement,story{2}');
$story->status->range('active');
$story->version->range('1');
$story->gen(4);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-4');
$storyspec->version->range('1');
$storyspec->title->range('业需01,用需01,研需01,子需求01');
$storyspec->gen(4);

$projectstory = zenData('projectstory');
$projectstory->project->range('1{2},2{2}');
$projectstory->product->range('1');
$projectstory->story->range('3,4,3,4');
$projectstory->order->range('1,2,1,2');
$projectstory->gen(4);

$task = zenData('task');
$task->id->range('1');
$task->project->range('1');
$task->execution->range('2');
$task->story->range('4');
$task->name->range('任务1');
$task->type->range('devel');
$task->pri->range('1');
$task->status->range('wait');
$task->gen(1);

$case = zenData('case');
$case->id->range('1');
$case->product->range('1');
$case->execution->range('2');
$case->story->range('4');
$case->title->range('用例1');
$case->type->range('feature');
$case->pri->range('1');
$case->gen(1);

$casestep = zenData('casestep');
$casestep->id->range('1');
$casestep->case->range('1');
$casestep->type->range('step');
$casestep->gen(1);

$bug = zenData('bug');
$bug->id->range('1');
$bug->product->range('1');
$bug->execution->range('2');
$bug->story->range('4');
$bug->title->range('bug1');
$bug->status->range('active');
$bug->gen(1);

$tester = new trackTester();
$tester->login();
$trackurl['productID'] = 1;

r($tester->checkTrackData($trackurl, 'ER','业需01'))       && p('message,status') && e('业务需求显示正确,SUCCESS');//检查业务需求的显示
r($tester->checkTrackData($trackurl, 'UR','用需01'))       && p('message,status') && e('用户需求显示正确,SUCCESS');//检查用户需求的显示
r($tester->checkTrackData($trackurl, 'SR','研需01'))       && p('message,status') && e('研发需求显示正确,SUCCESS');//检查研发需求的显示
r($tester->checkTrackData($trackurl, 'sub_SR','子需求01')) && p('message,status') && e('子研发需求显示正确,SUCCESS');//检查子需求的显示
r($tester->checkTrackData($trackurl, 'project','项目1'))   && p('message,status') && e('所属项目显示正确,SUCCESS');//检查所属项目的显示
r($tester->checkTrackData($trackurl, 'execution','迭代1')) && p('message,status') && e('所属执行显示正确,SUCCESS');//检查所属执行的显示
r($tester->checkTrackData($trackurl, 'task','任务1'))      && p('message,status') && e('相关任务显示正确,SUCCESS');//检查相关任务的显示
r($tester->checkTrackData($trackurl, 'bug','bug1'))        && p('message,status') && e('相关Bug显示正确,SUCCESS');//检查相关Bug的显示
r($tester->checkTrackData($trackurl, 'case','用例1'))      && p('message,status') && e('相关用例显示正确,SUCCESS');//检查相关用例的显示

$tester->closeBrowser();
