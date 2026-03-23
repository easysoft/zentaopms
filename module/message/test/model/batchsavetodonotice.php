#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

// 使用 dao 直接插入数据，避免生成 SQL 文件，并确保数据符合测试条件
// getNoticeTodos() 需要找到今天、状态为wait、begin时间在提醒范围内的待办
global $tester;
$now = helper::now();
$today = date('Y-m-d');
$ends1 = (int)date('Hi', strtotime("+60 seconds {$now}"));
$ends10 = (int)date('Hi', strtotime("+10 minute +60 seconds {$now}"));
$ends30 = (int)date('Hi', strtotime("+30 minute +60 seconds {$now}"));
// begin 时间在提醒范围内（格式：4字符字符串 HHmm，如 '1010' 表示 10:10）
$begin1 = sprintf('%04d', $ends1);
$begin10 = sprintf('%04d', $ends10);
$begin30 = sprintf('%04d', $ends30);
// begin 时间不在提醒范围内（用于user2用户）
$beginOut = sprintf('%04d', (int)date('Hi', strtotime("+2 hours {$now}")));
// 直接插入数据，确保每个用户的待办数量符合测试期望
// admin用户：3条待办在提醒范围内
for($i = 0; $i < 3; $i++)
{
    $begin = array($begin1, $begin10, $begin30)[$i];
    $tester->dao->insert(TABLE_TODO)
        ->set('account')->eq('admin')
        ->set('date')->eq($today)
        ->set('begin')->eq($begin)
        ->set('end')->eq('1400')
        ->set('type')->eq('custom')
        ->set('objectID')->eq(0)
        ->set('pri')->eq(1)
        ->set('name')->eq('测试待办' . ($i + 1))
        ->set('desc')->eq('测试描述')
        ->set('status')->eq('wait')
        ->set('private')->eq(0)
        ->set('assignedTo')->eq('admin')
        ->set('assignedBy')->eq('admin')
        ->set('assignedDate')->eq($now)
        ->exec();
}
// user1用户：2条待办在提醒范围内
for($i = 0; $i < 2; $i++)
{
    $begin = array($begin1, $begin10)[$i];
    $tester->dao->insert(TABLE_TODO)
        ->set('account')->eq('user1')
        ->set('date')->eq($today)
        ->set('begin')->eq($begin)
        ->set('end')->eq('1400')
        ->set('type')->eq('custom')
        ->set('objectID')->eq(0)
        ->set('pri')->eq(1)
        ->set('name')->eq('测试待办' . ($i + 1))
        ->set('desc')->eq('测试描述')
        ->set('status')->eq('wait')
        ->set('private')->eq(0)
        ->set('assignedTo')->eq('user1')
        ->set('assignedBy')->eq('admin')
        ->set('assignedDate')->eq($now)
        ->exec();
}
// user2用户：3条待办，但begin时间不在提醒范围内
for($i = 0; $i < 3; $i++)
{
    $tester->dao->insert(TABLE_TODO)
        ->set('account')->eq('user2')
        ->set('date')->eq($today)
        ->set('begin')->eq($beginOut)
        ->set('end')->eq('1400')
        ->set('type')->eq('custom')
        ->set('objectID')->eq(0)
        ->set('pri')->eq(1)
        ->set('name')->eq('测试待办' . ($i + 1))
        ->set('desc')->eq('测试描述')
        ->set('status')->eq('wait')
        ->set('private')->eq(0)
        ->set('assignedTo')->eq('user2')
        ->set('assignedBy')->eq('admin')
        ->set('assignedDate')->eq($now)
        ->exec();
}

zenData('user')->gen(5);
zenData('notify')->gen(0);

global $tester;
$messageTest = new messageModelTest();

su('admin');

r($messageTest->batchSaveTodoNoticeTest('admin')) && p() && e('3'); // 测试步骤1：有待办需要提醒时批量保存通知消息
r($messageTest->batchSaveTodoNoticeTest('user2')) && p() && e('0'); // 测试步骤2：无待办需要提醒时返回空数组

su('admin');
$adminResult = $tester->message->batchSaveTodoNotice();
r(count($adminResult)) && p() && e('3'); // 测试步骤3：验证保存的通知消息数据结构正确性

r($messageTest->batchSaveTodoNoticeTest('user1')) && p() && e('2'); // 测试步骤4：测试不同用户的待办通知保存
r($tester->message->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('objectType')->eq('message')->fetch('count')) && p() && e('8'); // 测试步骤5：验证数据库中通知记录的完整性