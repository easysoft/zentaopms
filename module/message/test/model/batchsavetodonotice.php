#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

/**

title=测试 messageModel::batchSaveTodoNotice();
timeout=0
cid=17048

- 测试步骤1：有待办需要提醒时批量保存通知消息 @3
- 测试步骤2：无待办需要提醒时返回空数组 @0
- 测试步骤3：验证保存的通知消息数据结构正确性 @3
- 测试步骤4：测试不同用户的待办通知保存 @2
- 测试步骤5：验证数据库中通知记录的完整性 @8

*/

zenData('todo')->loadYaml('todo')->gen(20);
zenData('user')->gen(5);
zenData('notify')->gen(0);

global $tester;
$messageTest = new messageTest();

su('admin');

r($messageTest->batchSaveTodoNoticeTest('admin')) && p() && e('3'); // 测试步骤1：有待办需要提醒时批量保存通知消息
r($messageTest->batchSaveTodoNoticeTest('user2')) && p() && e('0'); // 测试步骤2：无待办需要提醒时返回空数组

su('admin');
$adminResult = $tester->message->batchSaveTodoNotice();
r(count($adminResult)) && p() && e('3'); // 测试步骤3：验证保存的通知消息数据结构正确性

r($messageTest->batchSaveTodoNoticeTest('user1')) && p() && e('2'); // 测试步骤4：测试不同用户的待办通知保存
r($tester->message->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('objectType')->eq('message')->fetch('count')) && p() && e('8'); // 测试步骤5：验证数据库中通知记录的完整性