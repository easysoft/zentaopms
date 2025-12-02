#!/usr/bin/env php
<?php

/**

title=测试 metricZen::initMetricRecords();
timeout=0
cid=17197

- 步骤1：系统范围初始化 @1
- 步骤2：产品范围初始化 @4
- 步骤3：项目范围初始化 @4
- 步骤4：执行范围初始化（execution表不存在） @0
- 步骤5：无效范围初始化 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->status->range('normal{3},closed{2}');
$productTable->deleted->range('0{4},1{1}');
$productTable->shadow->range('0');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project');
$projectTable->status->range('wait{2},doing{2},closed{1}');
$projectTable->deleted->range('0{4},1{1}');
$projectTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 准备测试用的recordCommon对象
$recordCommon = new stdClass();
$recordCommon->value = 0;
$recordCommon->metricID = 1;
$recordCommon->metricCode = 'test_metric';
$recordCommon->date = helper::now();
$recordCommon->calcType = 'cron';
$recordCommon->calculatedBy = 'system';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($metricTest->initMetricRecordsZenTest($recordCommon, 'system', 'now'))) && p() && e('1'); // 步骤1：系统范围初始化
r(count($metricTest->initMetricRecordsZenTest($recordCommon, 'product', 'now'))) && p() && e('4'); // 步骤2：产品范围初始化  
r(count($metricTest->initMetricRecordsZenTest($recordCommon, 'project', 'now'))) && p() && e('4'); // 步骤3：项目范围初始化
r(count($metricTest->initMetricRecordsZenTest($recordCommon, 'execution', 'now'))) && p() && e('0'); // 步骤4：执行范围初始化（execution表不存在）
r(count($metricTest->initMetricRecordsZenTest($recordCommon, 'invalid', 'now'))) && p() && e('0'); // 步骤5：无效范围初始化