#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

$action = zenData('action')->loadYaml('action_year');
$action->execution->range('101');
$action->gen(35);
$actionrecent = zenData('actionrecent')->loadYaml('action_year');
$actionrecent->execution->range('101');
$actionrecent->gen(35);

zenData('doclib')->loadYaml('doclib')->gen(15);
zenData('doc')->loadYaml('doc')->gen(5);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);
zenData('userview')->loadYaml('userview')->gen(2);

/**

title=测试 actionModel->getDynamicByExecution();
timeout=0
cid=1

- 查找所有用户动态 @34
- 查找用户admin动态 @11
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找今天的动态 @0
- 查找昨天的动态 @1
- 查找上周的动态 @2
- 查找今天的动态 @5

*/

$executionID = '101';
$accountList = array('all', 'admin', 'dev17', 'test18');
$typeList    = array('all', 'today', 'yesterday', 'lastweek');
$dateList    = array('', 'today');

$actionTest = new actionTest();

su('admin');
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[0])) && p() && e('34');  // 查找所有用户动态
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[1])) && p() && e('11');  // 查找用户admin动态
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[2])) && p() && e('12');  // 查找用户dev17动态
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[3])) && p() && e('11');  // 查找用户test18动态

$action = zenData('action')->loadYaml('action_week');
$action->execution->range('101');
$action->gen(35, true, false);
$actionrecent = zenData('actionrecent')->loadYaml('action_week');
$actionrecent->execution->range('101');
$actionrecent->gen(35, true, false);
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[1], $typeList[1])) && p() && e('0');   // 查找今天的动态
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[1], $typeList[2])) && p() && e('1');   // 查找昨天的动态
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[1], $typeList[3])) && p() && e('2');   // 查找上周的动态

$action = zenData('action')->loadYaml('action_year');
$action->execution->range('101');
$action->gen(35, true, false);
$actionrecent = zenData('actionrecent')->loadYaml('action_year');
$actionrecent->execution->range('101');
$actionrecent->gen(35, true, false);
r($actionTest->getDynamicByExecutionTest($executionID, $accountList[0], $typeList[1])) && p() && e('5');  // 查找今天的动态