#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getPivotVersions();
timeout=0
cid=0

- 步骤1：查询存在透视表规格的透视表ID1001 - 正常情况 @array
- 步骤2：查询不存在透视表规格的透视表ID1002 - 边界值 @false  
- 步骤3：查询不存在的透视表ID9999 - 异常输入 @false
- 步骤4：查询ID为0的无效参数 - 边界值 @false
- 步骤5：查询负数ID的无效参数 - 异常输入 @false

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 准备测试数据
// 使用现有的系统数据，假设1001是有透视表规格的ID

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 测试步骤执行
r($pivotTest->getPivotVersionsTest(1001)) && p() && e('array'); // 步骤1：查询存在透视表规格的透视表ID1001 - 正常情况
r($pivotTest->getPivotVersionsTest(1002)) && p() && e('false'); // 步骤2：查询不存在透视表规格的透视表ID1002 - 边界值
r($pivotTest->getPivotVersionsTest(9999)) && p() && e('false'); // 步骤3：查询不存在的透视表ID9999 - 异常输入
r($pivotTest->getPivotVersionsTest(0)) && p() && e('false'); // 步骤4：查询ID为0的无效参数 - 边界值
r($pivotTest->getPivotVersionsTest(-1)) && p() && e('false'); // 步骤5：查询负数ID的无效参数 - 异常输入