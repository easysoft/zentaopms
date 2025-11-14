#!/usr/bin/env php
<?php

/**

title=测试 productZen::getActionsForDynamic();
timeout=0
cid=17570

- 测试步骤1:获取产品1所有用户的全部动态记录 @0
- 测试步骤2:获取产品1的admin用户动态记录 @0
- 测试步骤3:获取产品1特定日期的动态记录 @0
- 测试步骤4:获取产品1动态记录按ID降序排列 @0
- 测试步骤5:获取产品0(无效产品ID)的动态记录 @0
- 测试步骤6:获取产品1按账户类型(account)筛选的动态 @0
- 测试步骤7:获取产品1的动态记录向前翻页 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('action')->loadYaml('action_year', false, 4)->gen(35);
zenData('actionrecent')->loadYaml('action_year', false, 4)->gen(35);

$actionproduct = zenData('actionproduct');
$actionproduct->action->range('1-35');
$actionproduct->product->range('1{7},2{7},3{7},4{7},5{7}');
$actionproduct->gen(35);

zenData('doclib')->loadYaml('doclib', false, 3)->gen(15);
zenData('doc')->loadYaml('doc', false, 3)->gen(5);
zenData('product')->gen(5);
zenData('project')->loadYaml('execution', false, 2)->gen(12);
zenData('user')->loadYaml('user', false, 1)->gen(3);
zenData('userview')->loadYaml('userview', false, 1)->gen(2);

su('admin');

$productTest = new productZenTest();

r($productTest->getActionsForDynamicTest('all', 'date_desc', 1, 'all', '', 'next')) && p() && e('0'); // 测试步骤1:获取产品1所有用户的全部动态记录
r($productTest->getActionsForDynamicTest('admin', 'date_desc', 1, 'all', '', 'next')) && p() && e('0'); // 测试步骤2:获取产品1的admin用户动态记录
r($productTest->getActionsForDynamicTest('all', 'date_desc', 1, 'all', date('Y-m-d'), 'next')) && p() && e('0'); // 测试步骤3:获取产品1特定日期的动态记录
r($productTest->getActionsForDynamicTest('all', 'id_desc', 1, 'all', '', 'next')) && p() && e('0'); // 测试步骤4:获取产品1动态记录按ID降序排列
r($productTest->getActionsForDynamicTest('all', 'date_desc', 0, 'all', '', 'next')) && p() && e('0'); // 测试步骤5:获取产品0(无效产品ID)的动态记录
r($productTest->getActionsForDynamicTest('all', 'date_desc', 1, 'account', '', 'next')) && p() && e('0'); // 测试步骤6:获取产品1按账户类型(account)筛选的动态
r($productTest->getActionsForDynamicTest('all', 'date_desc', 1, 'all', '', 'pre')) && p() && e('0'); // 测试步骤7:获取产品1的动态记录向前翻页