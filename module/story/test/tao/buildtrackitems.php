#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildTrackItems();
timeout=0
cid=18607

- 执行$epicItems['lane_11']['epic_1'][0]属性id @11
- 执行$epicItems['lane_10']['epic_1'][0]属性id @1
- 执行$epicItems['lane_10']['requirement_2'][0]属性id @3
- 执行$epicItems['lane_10']['project'][0]属性id @1
- 执行$epicItems['lane_4']['task'][0]属性id @4
- 执行$epicItems['lane_4']['bug'][0]属性id @4
- 执行$epicItems['lane_4']['case'][0]属性id @4
- 执行$epicItems['lane_10']['requirement_2'][0]属性id @3
- 执行$epicItems['lane_10']['project'][0]属性id @1
- 执行$epicItems['lane_4']['task'][0]属性id @4
- 执行$epicItems['lane_4']['bug'][0]属性id @4
- 执行$epicItems['lane_4']['case'][0]属性id @4
- 执行$epicItems['lane_10']['requirement_2'][0]属性id @3
- 执行$epicItems['lane_10']['project'][0]属性id @1
- 执行$epicItems['lane_5']['task'][0]属性id @5
- 执行$epicItems['lane_5']['bug'][0]属性id @5
- 执行$epicItems['lane_5']['case'][0]属性id @5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$projectStory = zenData('projectstory');
$projectStory->project->range('1-2{5}');
$projectStory->product->range('1');
$projectStory->story->range('6-10');
$projectStory->gen(10);

$project = zenData('project');
$project->id->range('1-2');
$project->type->range('project,scrum');
$project->gen('2');

$design = zenData('design');
$design->project->range('2{5}');
$design->product->range('1');
$design->story->range('6-10');
$design->commit->range('5-1');
$design->gen(5);

$commit = zenData('repohistory');
$commit->id->range('1-5');
$commit->comment->range('1-5')->prefix('comment');
$commit->gen('5');

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->isParent->range('1{3},0,0{3},0{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11)->fixPath();

$task = zenData('task');
$task->story->range('1-5');
$task->gen('10')->fixPath();

$bug = zenData('bug');
$bug->story->range('1-5');
$bug->gen('10');

$case = zenData('case');
$case->story->range('1-5');
$case->gen('10');

$storyGrade = zenData('storygrade');
$storyGrade->type->range('epic{2},requirement{2},story{2}');
$storyGrade->grade->range('1,2');
$storyGrade->name->range('BR1,BR2,UR1,UR2,SR1,SR2');
$storyGrade->status->range('enable');
$storyGrade->gen(6);

su('admin');

global $tester;
$tester->loadModel('story');

$allStoryIdList = array(1,2,3,4,5,6,7,8,9,10,11);
$stories        = $tester->story->dao->select('*')->from(TABLE_STORY)->orderBy('id_desc')->fetchAll('id', false);
$leafNodes      = $tester->story->getLeafNodes($stories);
$allStories     = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('root')->in('1,11')->andWhere('deleted')->eq(0)->orderBy('type,grade,parent')->fetchAll('id');

$epicItems = $tester->story->buildTrackItems($allStories, $leafNodes, 'epic');
r((array)$epicItems['lane_11']['epic_1'][0])        && p('id') && e('11');
r((array)$epicItems['lane_10']['epic_1'][0])        && p('id') && e('1');
r((array)$epicItems['lane_10']['requirement_2'][0]) && p('id') && e('3');
r((array)$epicItems['lane_10']['project'][0])       && p('id') && e('1');
r((array)$epicItems['lane_4']['task'][0])           && p('id') && e('4');
r((array)$epicItems['lane_4']['bug'][0])            && p('id') && e('4');
r((array)$epicItems['lane_4']['case'][0])           && p('id') && e('4');

$allStoryIdList = array(2,3,4,5,6,7,8,9,10);
$stories        = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('2,3,4')->orderBy('id_desc')->fetchAll('id', false);
$leafNodes      = $tester->story->getLeafNodes($stories, 'epic');
$allStories     = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('root')->in('1')->andWhere('deleted')->eq(0)->orderBy('type,grade,parent')->fetchAll('id');

$requirementItems = $tester->story->buildTrackItems($allStories, $leafNodes, 'requirement');
r((array)$epicItems['lane_10']['requirement_2'][0]) && p('id') && e('3');
r((array)$epicItems['lane_10']['project'][0])       && p('id') && e('1');
r((array)$epicItems['lane_4']['task'][0])           && p('id') && e('4');
r((array)$epicItems['lane_4']['bug'][0])            && p('id') && e('4');
r((array)$epicItems['lane_4']['case'][0])           && p('id') && e('4');

$allStoryIdList = array(5,6,7,8,9,10);
$stories        = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('5,6,7,8,9,10')->orderBy('id_desc')->fetchAll('id', false);
$leafNodes      = $tester->story->getLeafNodes($stories, 'epic');
$allStories     = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('root')->in('1')->andWhere('deleted')->eq(0)->orderBy('type,grade,parent')->fetchAll('id');

$storyItems = $tester->story->buildTrackItems($allStories, $leafNodes, 'story');
r((array)$epicItems['lane_10']['requirement_2'][0]) && p('id') && e('3');
r((array)$epicItems['lane_10']['project'][0])       && p('id') && e('1');
r((array)$epicItems['lane_5']['task'][0])           && p('id') && e('5');
r((array)$epicItems['lane_5']['bug'][0])            && p('id') && e('5');
r((array)$epicItems['lane_5']['case'][0])           && p('id') && e('5');
