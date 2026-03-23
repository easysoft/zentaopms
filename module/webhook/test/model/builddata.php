#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::buildData();
timeout=0
cid=19686

- 步骤1：正常参数测试 @1
- 步骤2：空objectType参数 @0
- 步骤3：不存在的actionType（有数据） @0
- 步骤4：不存在的actionID @0
- 步骤5：无匹配数据 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 简化数据准备
zenData('action')->gen(10);
zenData('webhook')->gen(10);
zenData('release')->gen(5);
zenData('story')->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
global $tester;
$tester->loadModel('action');
$webhookTest = new webhookModelTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
$result1 = $webhookTest->buildDataTest('release', 1, 'opened', 1);
r($result1 !== false ? '1' : '0') && p() && e('1'); // 步骤1：正常参数测试

$result2 = $webhookTest->buildDataTest('', 1, 'opened', 1);
r($result2 !== false ? '1' : '0') && p() && e('0'); // 步骤2：空objectType参数

$result3 = $webhookTest->buildDataTest('release', 1, 'notexist', 1);
r($result3 !== false ? '1' : '0') && p() && e('0'); // 步骤3：不存在的actionType（有数据）

$result4 = $webhookTest->buildDataTest('release', 1, 'opened', 999);
r($result4 !== false ? '1' : '0') && p() && e('0'); // 步骤4：不存在的actionID

$result5 = $webhookTest->buildDataTest('story', 999, 'opened', 1);
r($result5 !== false ? '1' : '0') && p() && e('0'); // 步骤5：无匹配数据
