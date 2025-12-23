#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 mailTao::getObjectTitle();
timeout=0
cid=17033

- 查看需求标题属性title @需求标题
- 查看任务标题属性name @任务标题
- 查看Bug标题属性title @Bug标题
- 查看用例标题属性title @用例标题
- 查看待办标题属性name @待办标题

*/

global $tester;
$tester->loadModel('mail');

$story = new stdclass();
$story->title = '需求标题';

$task = new stdclass();
$task->name = '任务标题';

$bug = new stdclass();
$bug->title = 'Bug标题';

$case = new stdclass();
$case->title = '用例标题';

$todo = new stdclass();
$todo->name = '待办标题';

r($tester->mail->getObjectTitle($story, 'story')) && p('title') && e('需求标题'); // 查看需求标题
r($tester->mail->getObjectTitle($task, 'task'))   && p('name')  && e('任务标题'); // 查看任务标题
r($tester->mail->getObjectTitle($bug, 'bug'))     && p('title') && e('Bug标题'); // 查看Bug标题
r($tester->mail->getObjectTitle($case, 'case'))   && p('title') && e('用例标题'); // 查看用例标题
r($tester->mail->getObjectTitle($todo, 'todo'))   && p('name')  && e('待办标题'); // 查看待办标题