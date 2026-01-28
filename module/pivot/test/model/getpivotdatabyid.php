#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getPivotDataByID();
timeout=0
cid=17393

- 步骤1：正常情况-获取存在的透视表ID属性id @1001
- 步骤2：正常情况-验证透视表分组属性group @85
- 步骤3：获取另一个透视表并验证ID属性id @1003
- 步骤4：边界值-不存在的ID返回0 @0
- 步骤5：异常输入-无效ID零返回0 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 初始化pivot测试数据（只加载pivot.sql）
global $tester, $app;
$appPath = $app->getAppRoot();
$sqlFile = $appPath . 'test/data/pivot.sql';
$tester->dbh->exec(file_get_contents($sqlFile));

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getPivotDataByIDTest(1001)) && p('id') && e('1001'); // 步骤1：正常情况-获取存在的透视表ID
r($pivotTest->getPivotDataByIDTest(1001)) && p('group') && e('85'); // 步骤2：正常情况-验证透视表分组
r($pivotTest->getPivotDataByIDTest(1003)) && p('id') && e('1003'); // 步骤3：获取另一个透视表并验证ID
r($pivotTest->getPivotDataByIDTest(9999)) && p() && e('0'); // 步骤4：边界值-不存在的ID返回0
r($pivotTest->getPivotDataByIDTest(0)) && p() && e('0'); // 步骤5：异常输入-无效ID零返回0