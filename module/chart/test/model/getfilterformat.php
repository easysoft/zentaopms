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
- 步骤6：条件过滤值的注入 payload 被拒绝 @0
- 步骤7：条件过滤操作符非白名单被拒绝 @0
- 步骤8：条件过滤字段名非法被拒绝 @0
- 步骤9：合法的字面量过滤值正常通过 @='a'
- 步骤10：IN 过滤正常通过 @IN
- 步骤11：字面量校验工具对注入值返回假 @0
- 步骤12：字面量校验工具对合法值返回真 @1

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

r(strpos($result['year']['value'], "x'") === false && strpos($result['year']['value'], "x\\'") !== false && substr($result['year']['value'], -1) === ')') && p('') && e(1); // 注入payload不能逃逸select过滤值
r(strpos($result['name']['value'], "x'") === false && strpos($result['name']['value'], "x\\'") !== false && substr($result['name']['value'], -1) === "'") && p('') && e(1); // 注入payload不能逃逸input过滤值

$injectionFilter = array(array('field' => 'name', 'type' => 'condition', 'operator' => '=', 'value' => "'a' and sleep(3) and '1'='1'"));
$result = $testObj->getFilterFormatTest($injectionFilter);
r($result) && p() && e('0'); // 步骤6：条件过滤值的注入 payload 被拒绝

$badOperatorFilter = array(array('field' => 'name', 'type' => 'condition', 'operator' => '= 1 or 1=1', 'value' => "'a'"));
$result = $testObj->getFilterFormatTest($badOperatorFilter);
r($result) && p() && e('0'); // 步骤7：条件过滤操作符非白名单被拒绝

$badFieldFilter = array(array('field' => 'name`', 'type' => 'condition', 'operator' => '=', 'value' => "'a'"));
$result = $testObj->getFilterFormatTest($badFieldFilter);
r($result) && p() && e('0'); // 步骤8：条件过滤字段名非法被拒绝

$literalFilter = array(array('field' => 'name', 'type' => 'condition', 'operator' => '=', 'value' => "'a'"));
$result = $testObj->getFilterFormatTest($literalFilter);
r($result['name']['operator'] . $result['name']['value']) && p() && e("='a'"); // 步骤9：合法的字面量过滤值正常通过

$inFilter = array(array('field' => 'name', 'type' => 'condition', 'operator' => 'IN', 'value' => 'a,b'));
$result = $testObj->getFilterFormatTest($inFilter);
r($result['name']['operator']) && p() && e('IN'); // 步骤10：IN 过滤正常通过

$bi = $tester->loadModel('bi');
r($bi->isSafeFilterValue("'a' and extractvalue(1,concat(0x7e,(select password from zt_user limit 1)))", '=')) && p() && e('0'); // 步骤11：字面量校验工具对注入值返回假
r($bi->isSafeFilterValue("'a'", '=')) && p() && e('1'); // 步骤12：字面量校验工具对合法值返回真
