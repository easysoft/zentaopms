#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

error_reporting(E_ERROR);

$zd_projectproduct = zenData('projectproduct');
$zd_projectproduct->id->range('1-5');
$zd_projectproduct->project->range('1-5');
$zd_projectproduct->product->range('1-5');
$zd_projectproduct->branch->range('1-5');
$zd_projectproduct->gen(5);
$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('1-1');
$zd_user->last->range('(M)-(w)')->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
$zd_user->feedback->range('0');
$zd_user->scoreLevel->range('0');
$zd_user->resetExpired->range('0');
$zd_user->jira->range('0');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');

$tester->loadModel('block');
helper::import($tester->app->getModulePath('', 'block') . 'control.php');
helper::import($tester->app->getModulePath('', 'block') . 'zen.php');


$testObj = new blockZenTest();
/**

title=测试 blockModel::printBugStatisticBlock()
timeout=0
cid=0

- 步骤1：正常输入 @echo_yes
- 步骤2：边界值输入 @1
- 步骤3：无效输入 @1
- 步骤4：大值输入 @1
- 步骤5：业务规则验证 @1

*/

r($testObj->printBugStatisticBlockTest((object)array(), array())) && p() && e('echo_yes'); // 步骤1：正常输入
$result = $testObj->printBugStatisticBlockTest((object)array(), array());
r($result === null) && p() && e('1'); // 步骤2：边界值输入
$result = $testObj->printBugStatisticBlockTest((object)array(), array());
r($result === null) && p() && e('1'); // 步骤3：无效输入
$result = $testObj->printBugStatisticBlockTest((object)array('id' => 999999), array());
r($result === null) && p() && e('1'); // 步骤4：大值输入
$result = $testObj->printBugStatisticBlockTest((object)array('id' => 1, 'name' => 'test'), array(1, 2));
r($result === null) && p() && e('1'); // 步骤5：业务规则验证
