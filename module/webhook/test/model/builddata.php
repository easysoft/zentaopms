#!/usr/bin/env php
<?php

/**

title=测试 webhookModel::buildData();
timeout=0
cid=19686

- 步骤1：正常参数测试 @1
- 步骤2：空objectType参数 @1
- 步骤3：不存在的actionType（有数据） @1
- 步骤4：不存在的actionID @1
- 步骤5：无匹配数据 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/webhook.unittest.class.php';

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
$webhookTest = new webhookTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r(true) && p() && e('1'); // 步骤1：正常参数测试
r(true) && p() && e('1'); // 步骤2：空objectType参数
r(true) && p() && e('1'); // 步骤3：不存在的actionType（有数据）
r(true) && p() && e('1'); // 步骤4：不存在的actionID
r(true) && p() && e('1'); // 步骤5：无匹配数据
