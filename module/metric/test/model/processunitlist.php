#!/usr/bin/env php
<?php

/**

title=测试 metricModel::processUnitList();
timeout=0
cid=17153

- 步骤1：默认配置下的处理结果 @工时
- 步骤2：hourPoint为0时的处理结果 @工时
- 步骤3：hourPoint为1时的处理结果 @故事点
- 步骤4：hourPoint为2时的处理结果 @功能点
- 步骤5：无hourPoint配置时的默认处理结果 @工时

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($metricTest->processUnitListTest()) && p() && e('工时'); // 步骤1：默认配置下的处理结果
global $config;
$config->custom->hourPoint = '0';
r($metricTest->processUnitListTest()) && p() && e('工时'); // 步骤2：hourPoint为0时的处理结果
$config->custom->hourPoint = '1';
r($metricTest->processUnitListTest()) && p() && e('故事点'); // 步骤3：hourPoint为1时的处理结果
$config->custom->hourPoint = '2';
r($metricTest->processUnitListTest()) && p() && e('功能点'); // 步骤4：hourPoint为2时的处理结果
unset($config->custom->hourPoint);
r($metricTest->processUnitListTest()) && p() && e('工时'); // 步骤5：无hourPoint配置时的默认处理结果