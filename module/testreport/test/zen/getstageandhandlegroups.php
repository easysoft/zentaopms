#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::getStageAndHandleGroups();
timeout=0
cid=0

- 步骤1：正常情况-返回包含2个元素的数组(stageGroups和handleGroups) @2
- 步骤2：正常情况-返回的第一个元素stageGroups是数组 @array
- 步骤3：正常情况-返回的第二个元素handleGroups是数组 @array
- 步骤4：边界值-空产品列表时，stageGroups[1][generated]为0 @0
- 步骤5：边界值-日期范围为空时，handleGroups为空数组 @0
- 步骤6：业务规则-stageGroups包含优先级1的数据结构 @1
- 步骤7：业务规则-handleGroups包含generated类型 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('bug')->gen(0);
zenData('build')->gen(0);
zenData('product')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-31', array(1, 2)))) && p() && e('2'); // 步骤1：正常情况-返回包含2个元素的数组(stageGroups和handleGroups)
r(gettype($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-31', array(1, 2))[0])) && p() && e('array'); // 步骤2：正常情况-返回的第一个元素stageGroups是数组
r(gettype($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-31', array(1, 2))[1])) && p() && e('array'); // 步骤3：正常情况-返回的第二个元素handleGroups是数组
r($testreportTest->getStageAndHandleGroupsTest(array(), '2024-01-01', '2024-01-31', array(1, 2))[0]['1']['generated']) && p() && e('0'); // 步骤4：边界值-空产品列表时，stageGroups[1][generated]为0
r(count($testreportTest->getStageAndHandleGroupsTest(array(1), '', '', array(1, 2))[1])) && p() && e('0'); // 步骤5：边界值-日期范围为空时，handleGroups为空数组
r(isset($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-31', array(1, 2))[0]['1'])) && p() && e('1'); // 步骤6：业务规则-stageGroups包含优先级1的数据结构
r(isset($testreportTest->getStageAndHandleGroupsTest(array(1), '2024-01-01', '2024-01-31', array(1, 2))[1]['generated'])) && p() && e('1'); // 步骤7：业务规则-handleGroups包含generated类型