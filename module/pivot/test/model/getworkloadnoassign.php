#!/usr/bin/env php
<?php

/**

title=测试 pivotModel->getWorkload();
timeout=0
cid=1

- 测试部门id为0，执行状态未分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。
 - 第0条的user属性 @user1
 - 第0条的executionName属性 @空
 - 第0条的projectName属性 @项目集2
 - 第3条的user属性 @user4
 - 第3条的executionName属性 @空
 - 第3条的projectName属性 @项目集5
- 测试部门id为1，执行状态未分配，工时为7的透视表数据是否正常生成, 此返回值只包含一条数据。
 - 第0条的user属性 @user1
 - 第0条的executionName属性 @空
 - 第0条的projectName属性 @项目集2
- 测试部门id为10000，执行状态未分配，工时为7的透视表数据是否正常生成, 部门id存在但是部门不存在的情况下不会返回数据。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';

su('admin');

zdTable('user')->gen(20);
zdTable('dept')->gen(4);
zdTable('task')->config('task')->gen(10);
zdTable('project')->gen(10);
zdTable('project')->config('execution_workload')->gen(10, false, false);
zdTable('team')->config('team')->gen(5);

$pivot = new pivotTest();

global $tester;
$users = $tester->loadModel('user')->getPairs('noletter|noclosed');

$deptList    = array(0, 1, 3);
$assignList  = array('noassign', 'assign');
$allHourList = array(7, 7.5, 8);
$usersList   = array($users, array());

r($pivot->getWorkload($deptList[0], $assignList[0], $usersList[0], $allHourList[0])) && p('0:user,executionName,projectName;3:user,executionName,projectName') && e('user1,空,项目集2;user4,空,项目集5');  //测试部门id为0，执行状态未分配，工时为7的透视表数据是否正常生成,此返回值包含四条数据。
r($pivot->getWorkload($deptList[1], $assignList[0], $usersList[0], $allHourList[0])) && p('0:user,executionName,projectName')                                  && e('user1,空,项目集2');                   //测试部门id为1，执行状态未分配，工时为7的透视表数据是否正常生成, 此返回值只包含一条数据。
r($pivot->getWorkload($deptList[2], $assignList[0], $usersList[0], $allHourList[0])) && p('')                                                                  && e('0');                                  //测试部门id为10000，执行状态未分配，工时为7的透视表数据是否正常生成, 部门id存在但是部门不存在的情况下不会返回数据。