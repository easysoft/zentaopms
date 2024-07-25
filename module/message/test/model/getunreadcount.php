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

su('admin');

global $tester;
$tester->loadModel('message');
$tester->message->app->user->account = 'admin';

r($tester->message->getUnreadCount()) && p() && e('3'); // 测试获取status不为 read 的条目数
