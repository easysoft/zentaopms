#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::saveMiniProgramMessage();
timeout=0
cid=15088

- 步骤1：正常情况保存req类型消息 @1
- 步骤2：正常情况保存res类型消息 @1
- 步骤3：正常情况保存ntf类型消息 @1
- 步骤4：使用数字appID保存消息 @1
- 步骤5：保存空内容消息 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/aiapp.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 由于是测试插入新数据，这里不需要准备ai_message表数据
zenData('ai_message')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiappTest = new aiappTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiappTest->saveMiniProgramMessageTest('123', 'req', 'test message')) && p() && e('1'); // 步骤1：正常情况保存req类型消息
r($aiappTest->saveMiniProgramMessageTest('456', 'res', 'response message')) && p() && e('1'); // 步骤2：正常情况保存res类型消息
r($aiappTest->saveMiniProgramMessageTest('789', 'ntf', 'notification message')) && p() && e('1'); // 步骤3：正常情况保存ntf类型消息
r($aiappTest->saveMiniProgramMessageTest(999, 'req', 'numeric appID test')) && p() && e('1'); // 步骤4：使用数字appID保存消息
r($aiappTest->saveMiniProgramMessageTest('555', 'req', '')) && p() && e('1'); // 步骤5：保存空内容消息