#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('action')->loadYaml('action_year')->gen(35);
zenData('actionrecent')->loadYaml('action_year')->gen(35);
zenData('actionproduct')->loadYaml('actionproduct')->gen(35);
zenData('doclib')->loadYaml('doclib')->gen(15);
zenData('doc')->loadYaml('doc')->gen(5);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);
zenData('userview')->loadYaml('userview')->gen(2);

/**

title=测试 actionModel->getActionListByCondition();
timeout=0
cid=14943

- 查找全部动态 @35
- 查找用户admin动态 @12
- 查找用户dev17动态 @12
- 查找用户test18动态 @11
- 查找产品1动态 @7
- 查找项目1动态 @1
- 查找执行1动态 @0
- 查找有产品条件动态 @7
- 查找过滤动作动态 @0
- 查找有项目条件动态 @1
- 查找今天动态 @1
- 查找昨天动态 @1
- 查找上周动态 @7

*/

$accountList     = array('all', 'admin', 'dev17', 'test18');
$typeList        = array('all', 'today', 'yesterday', 'lastweek');
$productIDList   = array('all', 1, 2, 3);
$projectIDList   = array('all', 1, 2, 3);
$executionIDList = array('all', 1, 2, 3);

global $tester;
$actionModel = $tester->loadModel('action');

su('admin');
$app->user->rights['acls'] = array();
$beginAndEnd = $actionModel->computeBeginAndEnd($typeList[0], '', 'next');
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('35');  // 查找全部动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[1], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('12');  // 查找用户admin动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[2], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('12');  // 查找用户dev17动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[3], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('11');  // 查找用户test18动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[1], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('7');  // 查找产品1动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[1], $executionIDList[0], '', 'date_desc'))) && p() && e('1');  // 查找项目1动态
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[1], '', 'date_desc'))) && p() && e('0');  // 查找执行1动态

r(count($actionModel->getActionListByCondition('t2.product=1', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '',     'date_desc'))) && p() && e('7');  // 查找有产品条件动态
r(count($actionModel->getActionListByCondition('t2.product=1', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '1!=1', 'date_desc'))) && p() && e('0');  // 查找过滤动作动态
r(count($actionModel->getActionListByCondition('project=1',    $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '',     'date_desc'))) && p() && e('1');  // 查找有项目条件动态

zenData('action')->loadYaml('action_week')->gen(35, true, false);
zenData('actionrecent')->loadYaml('action_week')->gen(35, true, false);

$beginAndEnd = $actionModel->computeBeginAndEnd($typeList[1], '', 'next');
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('1');  // 查找今天动态
$beginAndEnd = $actionModel->computeBeginAndEnd($typeList[2], '', 'next');
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('1');  // 查找昨天动态
$beginAndEnd = $actionModel->computeBeginAndEnd($typeList[3], '', 'next');
r(count($actionModel->getActionListByCondition('', $beginAndEnd['begin'], $beginAndEnd['end'], $accountList[0], $productIDList[0], $projectIDList[0], $executionIDList[0], '', 'date_desc'))) && p() && e('7');  // 查找上周动态