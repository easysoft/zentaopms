#!/usr/bin/env php
<?php

/**

title=测试 messageModel::getUnreadCount();
timeout=0
cid=17056

- 执行messageTest模块的getUnreadCountTest方法，参数是'admin'  @3
- 执行messageTest模块的getUnreadCountTest方法，参数是'user1'  @3
- 执行messageTest模块的getUnreadCountTest方法，参数是'user2'  @1
- 执行messageTest模块的getUnreadCountTest方法，参数是'nonexistent'  @0
- 执行messageTest模块的getUnreadCountTest方法，参数是''  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/message.unittest.class.php';

$notify = zenData('notify');
$notify->id->range('1-10');
$notify->objectType->range('message');
$notify->objectID->range('1-10');
$notify->action->range('1-5');
$notify->toList->range('`,admin,`,`,user1,`,`,admin,`,`,user1,`,`,user2,`,`,admin,`,`,user1,`,`,user2,`,`,user3,`,`,user3,`');
$notify->status->range('wait{3},sended{4},read{3}');
$notify->createdBy->range('admin{5},user1{3},user2{2}');
$notify->createdDate->range('`2023-10-01 10:00:00`,`2023-10-02 11:00:00`,`2023-10-03 12:00:00`,`2023-10-04 13:00:00`,`2023-10-05 14:00:00`,`2023-10-06 15:00:00`,`2023-10-07 16:00:00`,`2023-10-08 17:00:00`,`2023-10-09 18:00:00`,`2023-10-10 19:00:00`');
$notify->gen(10);

zenData('user')->gen(5);

su('admin');

global $tester;
$messageTest = new messageTest();

r($messageTest->getUnreadCountTest('admin')) && p() && e('3');
r($messageTest->getUnreadCountTest('user1')) && p() && e('3');
r($messageTest->getUnreadCountTest('user2')) && p() && e('1');
r($messageTest->getUnreadCountTest('nonexistent')) && p() && e('0');
r($messageTest->getUnreadCountTest('')) && p() && e('3');