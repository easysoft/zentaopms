#!/usr/bin/env php
<?php

/**

title=测试 messageModel::deleteExpired();
timeout=0
cid=17049

- 执行messageTest模块的deleteExpiredTest方法，参数是30  @4
- 执行messageTest模块的deleteExpiredTest方法，参数是7  @4
- 执行messageTest模块的deleteExpiredTest方法  @6
- 执行$otherUserCountAfter @3
- 执行$nonMessageCountAfter @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

$table = zenData('notify');
$table->objectType->range('message{10},action{5},other{3}');
$table->toList->range('`,admin,`,`,user1,`,`,user2,`');
$table->status->range('wait{6},sended{6},read{6}');
$table->action->range('0{9},1-10');
$table->data->range('test message data');
$table->createdBy->range('admin{6},user1{6},user2{6}');
$table->gen(18);

zenData('user')->gen(1);

su('admin');

$messageTest = new messageTest();

$messageTest->objectModel->app->user->account = 'admin';

r($messageTest->deleteExpiredTest(30)) && p() && e('4');

$createdDate = date('Y-m-d H:i:s', strtotime('-8 days'));
$messageTest->objectModel->dao->update(TABLE_NOTIFY)->set('createdDate')->eq($createdDate)->where('toList')->eq(',admin,')->andWhere('objectType')->eq('message')->exec();
r($messageTest->deleteExpiredTest(7)) && p() && e('4');

zenData('notify')->gen(0);
$table = zenData('notify');
$table->objectType->range('message{6}');
$table->toList->range('`,admin,`');
$table->status->range('wait{2},sended{2},read{2}');
$table->action->range('0{3},1-3');
$table->data->range('test message for deletion');
$table->createdBy->range('admin');
$table->gen(6);

r($messageTest->deleteExpiredTest(0)) && p() && e('6');

zenData('notify')->gen(0);
$table = zenData('notify');
$table->objectType->range('message{3}');
$table->toList->range('`,user1,`,`,user2,`');
$table->status->range('wait');
$table->action->range('0');
$table->data->range('other user message');
$table->createdBy->range('user1{2},user2{1}');
$table->gen(3);

$otherUserCountBefore = $messageTest->objectModel->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('toList')->ne(',admin,')->andWhere('objectType')->eq('message')->fetch('count');
$messageTest->deleteExpiredTest(0);
$otherUserCountAfter = $messageTest->objectModel->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('toList')->ne(',admin,')->andWhere('objectType')->eq('message')->fetch('count');
r($otherUserCountAfter) && p() && e('3');

zenData('notify')->gen(0);
$table = zenData('notify');
$table->objectType->range('action{5}');
$table->toList->range('`,admin,`');
$table->status->range('wait');
$table->action->range('1-5');
$table->data->range('action notification');
$table->createdBy->range('admin');
$table->gen(5);

$nonMessageCountBefore = $messageTest->objectModel->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('objectType')->ne('message')->fetch('count');
$messageTest->deleteExpiredTest(0);
$nonMessageCountAfter = $messageTest->objectModel->dao->select('COUNT(*) as count')->from(TABLE_NOTIFY)->where('objectType')->ne('message')->fetch('count');
r($nonMessageCountAfter) && p() && e('5');