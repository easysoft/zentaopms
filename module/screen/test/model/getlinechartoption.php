#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getLineChartOption();
timeout=0
cid=18243

- 步骤1：正常情况 @1
- 步骤2：边界值 @1
- 步骤3：异常输入 @1
- 步骤4：权限验证 @1
- 步骤5：业务规则 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 暂时移除zenData数据准备以避免输出干扰

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 必须包含至少5个测试步骤
r($screenTest->getLineChartOptionTest('normal_component_chart_filters')) && p() && e(1); // 步骤1：正常情况
r($screenTest->getLineChartOptionTest('empty_sql_component_chart')) && p() && e(1); // 步骤2：边界值
r($screenTest->getLineChartOptionTest('empty_component')) && p() && e(1); // 步骤3：异常输入
r($screenTest->getLineChartOptionTest('empty_chart')) && p() && e(1); // 步骤4：权限验证
r($screenTest->getLineChartOptionTest('empty_filters')) && p() && e(1); // 步骤5：业务规则