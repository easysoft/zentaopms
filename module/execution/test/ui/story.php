<?php

/**
title=执行下需求列表操作检查
timeout=0
cid=1
 */

chdir(__DIR__);
include '../lib/story.ui.class.php';

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1-5');
$project->project->range('0, 1, 1, 0, 4');
$project->model->range('scrum, []{2}, scrum, []');
$project->type->range('project, sprint{2}, project, sprint');
$project->auth->range('extend, []{2}, extend, []');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,4,`, `,4,5,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目2, 项目2执行1');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-5');
$projectProduct->product->range('1');
$projectProduct->gen(5);

$story = zenData('story');
$story->id->range('1-7');
$story->parent->range('0');
$story->isParent->range('0');
$story->root->range('1-7');
$story->path->range('`,1,`, `,2,`, `,3,`, `,4,`, `,5,`, `,6,`, `,7,`');
$story->grade->range('1');
$story->product->range('1');
$story->module->range('0');
$story->plan->range('0');
$story->title->range('研需1, 研需2, 研需3, 研需4, 研需5, 研需6, 研需7');
$story->type->range('story');
$story->estimate->range('0');
$story->status->range('active{3}, closed, reviewing, draft, changing');
$story->stage->range('projected');
$story->version->range('1');
$story->gen(7);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{7}, 2{7}');
$projectStory->product->range('1');
$projectStory->branch->range('0');
$projectStory->story->range('1-7, 1-7');
$projectStory->version->range('1');
$projectStory->order->range('1{7}, 2{7}');
$projectStory->gen(14);

$tester = new storyTester();
$tester->login();

r($tester->checkTab('allTab', '7'))       && p('message') && e('allTab下显示条数正确');       //检查全部标签下显示条数
r($tester->checkTab('unclosedTab', '6'))  && p('message') && e('unclosedTab下显示条数正确');  //检查未关闭标签下显示条数
r($tester->checkTab('draftTab', '1'))     && p('message') && e('draftTab下显示条数正确');     //检查草稿标签下显示条数
r($tester->checkTab('reviewingTab', '1')) && p('message') && e('reviewingTab下显示条数正确'); //检查评审中标签下显示条数
r($tester->unlinkStory())                 && p('message') && e('需求移除成功');               //移除需求
r($tester->batchUnlinkStory())            && p('message') && e('需求批量移除成功');           //批量移除需求
$tester->closeBrowser();
