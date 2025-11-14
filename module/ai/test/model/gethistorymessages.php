#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getHistoryMessages();
timeout=0
cid=15032

- 步骤1：空数据表获取历史消息 @0
- 步骤2：不存在appID @0
- 步骤3：limit参数限制 @0
- 步骤4：limit为0 @0
- 步骤5：默认limit参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('ai_message')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->getHistoryMessagesTest(1, 10)) && p() && e('0'); // 步骤1：空数据表获取历史消息
r($aiTest->getHistoryMessagesTest(999, 10)) && p() && e('0'); // 步骤2：不存在appID
r($aiTest->getHistoryMessagesTest(1, 3)) && p() && e('0'); // 步骤3：limit参数限制
r($aiTest->getHistoryMessagesTest(1, 0)) && p() && e('0'); // 步骤4：limit为0
r($aiTest->getHistoryMessagesTest(1)) && p() && e('0'); // 步骤5：默认limit参数