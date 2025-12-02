#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

zenData('action')->loadYaml('action_year')->gen(35);
zenData('actionrecent')->loadYaml('action_year')->gen(35);
zenData('doclib')->gen(15);
zenData('doc')->gen(15);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);
zenData('userview')->loadYaml('userview')->gen(2);

/**

title=测试 actionModel->getDynamicByAccount();
timeout=0
cid=14894

- 查找用户admin动态 @12
- 查找用户admin动态 @12
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找今天的动态 @2
- 查找昨天的动态 @0
- 查找今天的动态 @0
- 查找用户admin动态 @12
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找今天的动态 @1
- 查找昨天的动态 @0
- 查找上周的动态 @0
- 查找今天的动态 @0

*/

$accountList     = array('', 'admin', 'dev17', 'test18');
$typeList        = array('all', 'today', 'yesterday', 'lastweek');
$dateList        = array('', 'today');

$action = new actionTest();

su('admin');
r($action->getDynamicByAccountTest($accountList[0])) && p() && e('12');  // 查找用户admin动态
r($action->getDynamicByAccountTest($accountList[1])) && p() && e('12');  // 查找用户admin动态
r($action->getDynamicByAccountTest($accountList[2])) && p() && e('12');  // 查找用户dev17动态
r($action->getDynamicByAccountTest($accountList[3])) && p() && e('11');  // 查找用户test18动态

r($action->getDynamicByAccountTest($accountList[1], $typeList[1])) && p() && e('2');   // 查找今天的动态
r($action->getDynamicByAccountTest($accountList[1], $typeList[2])) && p() && e('0');   // 查找昨天的动态

r($action->getDynamicByAccountTest($accountList[0], $typeList[0], $dateList[1])) && p() && e('0');  // 查找今天的动态

su('dev17');
global $app;
unset($app->user->rights['acls']['actions']);
r($action->getDynamicByAccountTest($accountList[1])) && p() && e('12');  // 查找用户admin动态
r($action->getDynamicByAccountTest($accountList[2])) && p() && e('12');  // 查找用户dev17动态
r($action->getDynamicByAccountTest($accountList[3])) && p() && e('11');  // 查找用户test18动态

r($action->getDynamicByAccountTest($accountList[0], $typeList[1]))               && p() && e('1');  // 查找今天的动态
r($action->getDynamicByAccountTest($accountList[0], $typeList[2]))               && p() && e('0');  // 查找昨天的动态
r($action->getDynamicByAccountTest($accountList[0], $typeList[3]))               && p() && e('0');  // 查找上周的动态
r($action->getDynamicByAccountTest($accountList[0], $typeList[0], $dateList[1])) && p() && e('0');  // 查找今天的动态
