#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::getHistoryMessages();
timeout=0
cid=15084

- 步骤1：查询不存在消息的应用ID @0
- 先插入测试数据 @1
- 再插入一条 @1
- 步骤2：查询存在消息的应用ID @2
- 步骤3：测试限制数量参数功能 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('ai_message')->gen(0);

su('admin');

$aiappTest = new aiappModelTest();

r(count($aiappTest->getHistoryMessagesTest(1, 20))) && p() && e('0'); // 步骤1：查询不存在消息的应用ID
r($aiappTest->saveMiniProgramMessageTest('1', 'req', 'test message 1')) && p() && e('1'); // 先插入测试数据
r($aiappTest->saveMiniProgramMessageTest('1', 'res', 'test response 1')) && p() && e('1'); // 再插入一条
r(count($aiappTest->getHistoryMessagesTest(1, 20))) && p() && e('2'); // 步骤2：查询存在消息的应用ID
r(count($aiappTest->getHistoryMessagesTest(1, 1))) && p() && e('1'); // 步骤3：测试限制数量参数功能