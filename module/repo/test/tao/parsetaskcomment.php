#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->parseTaskComment();
timeout=0
cid=1

- 解析完成任务属性8 @8
- 解析完成多个任务
 - 属性1 @1
 - 属性8 @8
 - 属性12 @12

*/

$repo = new repoTest();

$finishTaskComment  = 'Finish Task #8 Cost:2h';
$finishTaskComment2 = 'Finish Task #1,8,12. Cost:3h';

r($repo->parseTaskCommentTest($finishTaskComment))  && p('8')       && e('8'); //解析完成任务
r($repo->parseTaskCommentTest($finishTaskComment2)) && p('1,8,12')  && e('1,8,12'); //解析完成多个任务