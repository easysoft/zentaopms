#!/usr/bin/env php
<?php

/**

title=测试 actionModel::processProjectActions();
timeout=0
cid=14925

- 测试步骤1：空数组输入属性count @0
- 测试步骤2：包含project类型action的过滤属性count @5
- 测试步骤3：testtask类型action的映射转换属性count @3
- 测试步骤4：build类型action的映射转换属性count @2
- 测试步骤5：非项目相关类型的过滤属性count @0
- 测试步骤6：混合类型action的综合处理属性count @3
- 测试步骤7：不在映射表中的其他类型过滤属性count @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 清理测试数据
global $tester;
$tester->dao->delete()->from(TABLE_ACTION)->exec();

// 准备测试数据 - 直接插入
$actions = array(
    array('id' => 1, 'objectType' => 'project', 'action' => 'common', 'objectID' => 1, 'actor' => 'admin'),
    array('id' => 2, 'objectType' => 'project', 'action' => 'common', 'objectID' => 2, 'actor' => 'admin'),
    array('id' => 3, 'objectType' => 'project', 'action' => 'common', 'objectID' => 3, 'actor' => 'admin'),
    array('id' => 4, 'objectType' => 'project', 'action' => 'common', 'objectID' => 4, 'actor' => 'admin'),
    array('id' => 5, 'objectType' => 'project', 'action' => 'common', 'objectID' => 5, 'actor' => 'admin'),
    array('id' => 6, 'objectType' => 'testtask', 'action' => 'opened', 'objectID' => 6, 'actor' => 'admin'),
    array('id' => 7, 'objectType' => 'testtask', 'action' => 'started', 'objectID' => 7, 'actor' => 'admin'),
    array('id' => 8, 'objectType' => 'testtask', 'action' => 'closed', 'objectID' => 8, 'actor' => 'admin'),
    array('id' => 9, 'objectType' => 'build', 'action' => 'opened', 'objectID' => 9, 'actor' => 'admin'),
    array('id' => 10, 'objectType' => 'build', 'action' => 'opened', 'objectID' => 10, 'actor' => 'admin'),
    array('id' => 11, 'objectType' => 'product', 'action' => 'edited', 'objectID' => 11, 'actor' => 'admin'),
    array('id' => 12, 'objectType' => 'user', 'action' => 'edited', 'objectID' => 12, 'actor' => 'admin'),
    array('id' => 13, 'objectType' => 'story', 'action' => 'edited', 'objectID' => 13, 'actor' => 'admin'),
    array('id' => 14, 'objectType' => 'task', 'action' => 'edited', 'objectID' => 14, 'actor' => 'admin'),
    array('id' => 15, 'objectType' => 'bug', 'action' => 'edited', 'objectID' => 15, 'actor' => 'admin'),
);

foreach($actions as $action)
{
    $tester->dao->insert(TABLE_ACTION)->data($action)->exec();
}

zenData('actionrecent')->gen(0);

// 用户登录
su('admin');

// 创建测试实例
$actionTest = new actionTest();

r(count($actionTest->processProjectActionsTest(array())))          && p('count') && e('0');    // 测试步骤1：空数组输入
r(count($actionTest->processProjectActionsTest('1,2,3,4,5')))      && p('count') && e('5');    // 测试步骤2：包含project类型action的过滤
r(count($actionTest->processProjectActionsTest('6,7,8')))          && p('count') && e('3');    // 测试步骤3：testtask类型action的映射转换
r(count($actionTest->processProjectActionsTest('9,10')))           && p('count') && e('2');    // 测试步骤4：build类型action的映射转换
r(count($actionTest->processProjectActionsTest('11,12,13,14,15'))) && p('count') && e('0');    // 测试步骤5：非项目相关类型的过滤
r(count($actionTest->processProjectActionsTest('1,6,9,11,14')))    && p('count') && e('3');    // 测试步骤6：混合类型action的综合处理
r(count($actionTest->processProjectActionsTest('13,14,15')))       && p('count') && e('0');    // 测试步骤7：不在映射表中的其他类型过滤
