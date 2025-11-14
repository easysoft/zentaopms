#!/usr/bin/env php
<?php

/**

title=测试 screenModel::updateMetricFilters();
timeout=0
cid=18286

- 步骤1：正常情况
 - 属性chartConfig @present
 - 属性status @enabled
- 步骤2：已有filters
 - 属性chartConfig @present
 - 属性existing @existingFilter
- 步骤3：空filters数组
 - 属性chartConfig @present
 - 属性filters @0
- 步骤4：复杂filters
 - 属性chartConfig @present
 - 属性field1 @value1
 - 属性field2 @value2
- 步骤5：chartConfig但无filters
 - 属性chartConfig @present
 - 属性newField @newValue

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($screenTest->updateMetricFiltersTest1()) && p('chartConfig,status') && e('present,enabled'); // 步骤1：正常情况
r($screenTest->updateMetricFiltersTest2()) && p('chartConfig,existing') && e('present,existingFilter'); // 步骤2：已有filters
r($screenTest->updateMetricFiltersTest3()) && p('chartConfig,filters') && e('present,0'); // 步骤3：空filters数组
r($screenTest->updateMetricFiltersTest4()) && p('chartConfig,field1,field2') && e('present,value1,value2'); // 步骤4：复杂filters
r($screenTest->updateMetricFiltersTest5()) && p('chartConfig,newField') && e('present,newValue'); // 步骤5：chartConfig但无filters