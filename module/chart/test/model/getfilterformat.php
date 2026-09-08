#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

error_reporting(E_ERROR);

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

$tester->loadModel('chart');


$testObj = new chartModelTest();
/**

title=测试 chartModel::getFilterFormat()
timeout=0
cid=0

- 步骤1：正常输入 @0
- 步骤2：边界值输入 @0
- 步骤3：无效输入 @0
- 步骤4：大值输入 @0
- 步骤5：业务规则验证 @0

*/

$result = $testObj->getFilterFormatTest(array());
r(count($result)) && p() && e('0'); // 步骤1：正常输入
$result = $testObj->getFilterFormatTest(array());
r(count($result)) && p() && e('0'); // 步骤2：边界值输入
$result = $testObj->getFilterFormatTest(array(-1));
r(count($result)) && p() && e('0'); // 步骤3：无效输入
$result = $testObj->getFilterFormatTest(array(999999));
r(count($result)) && p() && e('0'); // 步骤4：大值输入
$result = $testObj->getFilterFormatTest(array(1, 2));
r(count($result)) && p() && e('0'); // 步骤5：业务规则验证

$injectionFilters = array
(
    array('type' => 'select', 'field' => 'year', 'default' => "x') or sleep(3)-- "),
    array('type' => 'input',  'field' => 'name', 'default' => "x') or sleep(3)-- ")
);
$result = $testObj->getFilterFormatTest($injectionFilters);

r(strpos($result['year']['value'], "') or") === false && substr($result['year']['value'], -1) === ')') && p('') && e(1); // 注入payload不能逃逸select过滤值
r(strpos($result['name']['value'], "') or") === false && substr($result['name']['value'], -1) === "'") && p('') && e(1); // 注入payload不能逃逸input过滤值
