#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::deleteHistoryMessagesByID();
timeout=0
cid=15083

- 步骤1：正常情况删除历史消息（保留指定消息ID） @1
- 步骤2：使用空消息ID数组删除所有消息 @1
- 步骤3：保留不存在的消息ID @1
- 步骤4：不同appID和userID组合的删除操作 @1
- 步骤5：多个保留消息ID的删除操作 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/aiapp.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$messageTable = zenData('ai_message');
$messageTable->id->range('1-20');
$messageTable->appID->range('1{5},2{5},3{10}');
$messageTable->user->range('1{10},2{5},3{5}');
$messageTable->type->range('req{7},res{7},ntf{6}');
$messageTable->content->range('test message 1,test message 2,test message 3,test message 4,test message 5');
$messageTable->createdDate->range('`2023-01-01 10:00:00`,`2023-06-01 12:00:00`,`2023-12-01 18:00:00`');
$messageTable->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$aiappTest = new aiappTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($aiappTest->deleteHistoryMessagesByIDTest('1', '1', array(1, 2))) && p() && e('1'); // 步骤1：正常情况删除历史消息（保留指定消息ID）
r($aiappTest->deleteHistoryMessagesByIDTest('2', '1', array())) && p() && e('1'); // 步骤2：使用空消息ID数组删除所有消息
r($aiappTest->deleteHistoryMessagesByIDTest('3', '2', array(999, 1000))) && p() && e('1'); // 步骤3：保留不存在的消息ID
r($aiappTest->deleteHistoryMessagesByIDTest('1', '3', array(5))) && p() && e('1'); // 步骤4：不同appID和userID组合的删除操作
r($aiappTest->deleteHistoryMessagesByIDTest('3', '1', array(10, 11, 12))) && p() && e('1'); // 步骤5：多个保留消息ID的删除操作