#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->parseComment();
timeout=0
cid=1

- 解析完成任务第tasks条的8属性 @8
- 解析完成多个任务
 - 第tasks条的1属性 @1
 - 第tasks条的8属性 @8
 - 第tasks条的12属性 @12
- 解析修复bug第bugs条的3属性 @3
- 解析修复多个bug
 - 第bugs条的3属性 @3
 - 第bugs条的5属性 @5
 - 第bugs条的12属性 @12
- 解析需求第stories条的1属性 @1
- 解析多个需求
 - 第stories条的1属性 @1
 - 第stories条的2属性 @2
 - 第stories条的3属性 @3

*/

$repo = new repoTest();

$finishTaskComment  = 'Finish task#8.';
$finishTaskComment2 = 'Finish task#1,8,12.';
$fixBugComment      = 'Fix bug#3';
$fixBugComment2     = 'Fix bug#3,5,12';
$storyComment       = 'Story#1';
$storyComment2      = 'Story#1,2,3';

r($repo->parseCommentTest($finishTaskComment))  && p('tasks:8')       && e('8'); //解析完成任务
r($repo->parseCommentTest($finishTaskComment2)) && p('tasks:1,8,12')  && e('1,8,12'); //解析完成多个任务
r($repo->parseCommentTest($fixBugComment))      && p('bugs:3')        && e('3'); //解析修复bug
r($repo->parseCommentTest($fixBugComment2))     && p('bugs:3,5,12')   && e('3,5,12'); //解析修复多个bug
r($repo->parseCommentTest($storyComment))       && p('stories:1')     && e('1'); //解析需求
r($repo->parseCommentTest($storyComment2))      && p('stories:1,2,3') && e('1,2,3'); //解析多个需求