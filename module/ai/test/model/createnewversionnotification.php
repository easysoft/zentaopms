#!/usr/bin/env php
<?php

/**

title=测试 aiModel::createNewVersionNotification();
timeout=0
cid=15012

- 步骤1：正常appID为1，有2个用户(用户1和用户2) @2
- 步骤2：appID为空 @0
- 步骤3：不存在的appID @0
- 步骤4：appID为2，有2个用户(用户2和用户3) @2
- 步骤5：appID为3，有1个用户(用户4) @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('ai_message');
$table->id->range('1-20');
$table->appID->range('1{5}, 2{5}, 3{5}, 4{3}, 5{2}');
$table->user->range('1{3}, 2{3}, 3{4}, 4{5}, 5{5}');
$table->type->range('req{10}, res{8}, ntf{2}');
$table->content->range('Test message content{10}, Old notification{2}, Response content{8}');
$table->createdDate->range('`2023-01-01 10:00:00`,`2023-01-02 11:00:00`,`2023-01-03 12:00:00`');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiTest = new aiModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiTest->createNewVersionNotificationTest(1)) && p() && e('2'); // 步骤1：正常appID为1，有2个用户(用户1和用户2)
r($aiTest->createNewVersionNotificationTest('')) && p() && e('0'); // 步骤2：appID为空
r($aiTest->createNewVersionNotificationTest(999)) && p() && e('0'); // 步骤3：不存在的appID
r($aiTest->createNewVersionNotificationTest(2)) && p() && e('2'); // 步骤4：appID为2，有2个用户(用户2和用户3)
r($aiTest->createNewVersionNotificationTest(3)) && p() && e('1'); // 步骤5：appID为3，有1个用户(用户4)