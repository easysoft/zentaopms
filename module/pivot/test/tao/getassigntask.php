#!/usr/bin/env php
<?php

/**

title=测试 pivotTao->getAssignTask();
timeout=0
cid=1

- 查询单人任务
 - 第0条的user属性 @admin
 - 第0条的left属性 @1
- 查询多人任务
 - 第1条的user属性 @user1
 - 第1条的left属性 @1
- 查询单人未开始的任务
 - 第2条的user属性 @user16
 - 第2条的left属性 @2
- 查询多人进行中的任务
 - 第3条的user属性 @user7
 - 第3条的left属性 @3
- 查询指派给不存在的用户的任务 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

zenData('task')->loadYaml('task')->gen(10);
zenData('project')->loadYaml('project_getassigntask')->gen(200);
zenData('taskteam')->gen(10);

global $tester;

$pivot = new pivotTest();

$deptUsers = array();

r($pivot->getAssignTask($deptUsers)) && p('0:user,left')  && e('admin,1');  //查询单人任务
r($pivot->getAssignTask($deptUsers)) && p('1:user,left')  && e('user1,1');  //查询多人任务
r($pivot->getAssignTask($deptUsers)) && p('2:user,left')  && e('user16,2'); //查询单人未开始的任务
r($pivot->getAssignTask($deptUsers)) && p('3:user,left')  && e('user7,3');  //查询多人进行中的任务

$deptUsers = array('user1000');
r($pivot->getAssignTask($deptUsers)) && p('')  && e('0');  //查询指派给不存在的用户的任务