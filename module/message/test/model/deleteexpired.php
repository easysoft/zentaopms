#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';

$notify = zenData('notify');
$notify->objectType->range('message');
$notify->toList->range('`,admin,`,`,user1,`');
$notify->status->range('wait,sended,read');
$notify->gen(10);

zenData('user')->gen(1);

global $tester;
$tester->loadModel('message');
$tester->message->app->user->account = 'admin';

$createdDate = date('Y-m-d H:i:s', strtotime('-10 day'));
$tester->message->dao->update(TABLE_NOTIFY)->set('createdDate')->eq($createdDate)->exec();

$tester->message->config->message->browser->maxDays = 10;
$tester->message->deleteExpired();
$messages = $tester->message->dao->select('*')->from(TABLE_NOTIFY)->fetchAll();
r(count($messages)) && p() && e('10'); // 过期时间为10，检查剩余条目数

$tester->message->config->message->browser->maxDays = 5;
$tester->message->deleteExpired();
$messages = $tester->message->dao->select('*')->from(TABLE_NOTIFY)->fetchAll();
r(count($messages)) && p() && e('5'); // 过期时间为5，检查剩余条目数
