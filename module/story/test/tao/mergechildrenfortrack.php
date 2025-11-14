#!/usr/bin/env php
<?php

/**

title=测试 storyModel->mergeChildrenForTrack();
timeout=0
cid=18653

- 全部传入空参数，检查newStories。 @0
- allStories传入空参数，检查newStories。属性id @1
- stories传入空参数，检查newStories。 @0
- 传入正常的业务需求，检查newStories。 @1;4;3;10;9;8;2;7;6;5
- 传入正常用户需求，检查newStories。 @3;10;9;8
- 传入错误的用户参数，检查newStories。 @0
- 传入正常的研发需求，检查newStories。属性id @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->isParent->range('1{3},0,0{3},0{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11)->fixPath();

su('admin');

global $tester;
$tester->loadModel('story');

$stories    = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('1')->orderBy('id_desc')->fetchAll('id', false);
$allStories = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id');

$newStories = $tester->story->mergeChildrenForTrack(array(), array(), 'epic');
r(count($newStories))    && p() && e('0');  //全部传入空参数，检查newStories。

$newStories = $tester->story->mergeChildrenForTrack(array(), $stories, 'epic');
r(reset($newStories))    && p('id') && e('1');  //allStories传入空参数，检查newStories。

$newStories = $tester->story->mergeChildrenForTrack($allStories, array(), 'epic');
r(count($newStories))    && p() && e('0');  //stories传入空参数，检查newStories。

$newStories = $tester->story->mergeChildrenForTrack($allStories, $stories, 'epic');
r(implode(';', array_keys($newStories)))    && p() && e('1;4;3;10;9;8;2;7;6;5');  //传入正常的业务需求，检查newStories。

$stories    = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('3')->orderBy('id_desc')->fetchAll('id', false);
$allStories = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id');
$newStories = $tester->story->mergeChildrenForTrack($allStories, $stories, 'requirement');
r(implode(';', array_keys($newStories)))    && p() && e('3;10;9;8');  //传入正常用户需求，检查newStories。

$stories    = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('3')->orderBy('id_desc')->fetchAll('id', false);
$allStories = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id');
$newStories = $tester->story->mergeChildrenForTrack($allStories, $stories, 'story');
r(count($newStories))    && p() && e('0');  //传入错误的用户参数，检查newStories。

$stories    = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('8')->orderBy('id_desc')->fetchAll('id', false);
$allStories = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id');
$newStories = $tester->story->mergeChildrenForTrack($allStories, $stories, 'story');
r(reset($newStories))    && p('id') && e('8');  //传入正常的研发需求，检查newStories。
