#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoApiSpec();
timeout=0
cid=15092

- 步骤1：正常情况，使用完整的映射关系 @1
- 步骤2：使用完整apiMap和部分moduleMap @1
- 步骤3：完整apiMap和部分moduleMap @1
- 步骤4：空的moduleMap，但有完整apiMap应该成功 @1
- 步骤5：不同用户角色创建 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/api.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$apiSpecTable = zenData('apispec');
$apiSpecTable->doc->range('1-20');
$apiSpecTable->title->range('用户接口规格,产品接口规格,项目接口规格,任务接口规格,缺陷接口规格');
$apiSpecTable->path->range('/api/user,/api/product,/api/project,/api/task,/api/bug');
$apiSpecTable->module->range('1001-1005');
$apiSpecTable->protocol->range('HTTP{4},HTTPS{1}');
$apiSpecTable->method->range('GET{3},POST{2}');
$apiSpecTable->requestType->range('application/json');
$apiSpecTable->responseType->range('application/json');
$apiSpecTable->status->range('done{4},doing{1}');
$apiSpecTable->owner->range('admin{3},user1{2}');
$apiSpecTable->version->range('1-3');
$apiSpecTable->addedBy->range('admin');
$apiSpecTable->addedDate->range('`2023-01-01 10:00:00`');
$apiSpecTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$apiTest = new apiTest();

// 生成完整的apiMap，包含所有演示数据中用到的doc ID
$fullApiMap = array();
for($i = 1; $i <= 82; $i++) {
    $fullApiMap[$i] = $i + 1000; // 映射到新的API ID
}

$fullModuleMap = array(
    0 => 0,
    2949 => 3949, 2950 => 3950, 2951 => 3951, 2952 => 3952,
    2953 => 3953, 2954 => 3954, 2955 => 3955, 2956 => 3956,
    2957 => 3957, 2958 => 3958, 2959 => 3959, 2960 => 3960,
    2961 => 3961, 2962 => 3962, 2963 => 3963, 2964 => 3964
);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'admin')) && p() && e(1); // 步骤1：正常情况，使用完整的映射关系
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, array(2949 => 3949), 'admin')) && p() && e(1); // 步骤2：使用完整apiMap和部分moduleMap
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, array(2949 => 4949, 2950 => 4950), 'user1')) && p() && e(1); // 步骤3：完整apiMap和部分moduleMap
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, array(), 'admin')) && p() && e(1); // 步骤4：空的moduleMap，但有完整apiMap应该成功
r($apiTest->createDemoApiSpecTest('16.0', $fullApiMap, $fullModuleMap, 'manager')) && p() && e(1); // 步骤5：不同用户角色创建