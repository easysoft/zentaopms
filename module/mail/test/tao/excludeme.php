#!/usr/bin/env php
<?php

/**

title=测试 mailModel->excludeMe();
cid=0

- toList为空 @0
- toList只有me账号 @0
- toList有me账号，和另一个账号属性1 @user1
- ccList为空 @0
- ccList只有me账号 @0
- ccList有me账号，和另一个账号属性1 @user2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$mailModel = $tester->loadModel('mail');
$mailModel->app->user->account = 'admin';

list($toList1, $ccList1) = $mailModel->excludeMe(array(), array());
list($toList2, $ccList2) = $mailModel->excludeMe(array('admin'), array('admin'));
list($toList3, $ccList3) = $mailModel->excludeMe(array('admin', 'user1'), array('admin', 'user2'));

r($toList1) && p()    && e('0');           //toList为空
r($toList2) && p()    && e('0');           //toList只有me账号
r($toList3) && p('1') && e('user1');       //toList有me账号，和另一个账号
r($ccList1) && p()    && e('0');           //ccList为空
r($ccList2) && p()    && e('0');           //ccList只有me账号
r($ccList3) && p('1') && e('user2');       //ccList有me账号，和另一个账号
